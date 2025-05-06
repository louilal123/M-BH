<?php
session_start();
require_once 'admin/functions/connection.php';

if (!isset($_SESSION['tenant_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['booking_id'])) {
    header("Location: rooms.php");
    exit;
}

$bookingId = $_GET['booking_id'];
$tenantId = $_SESSION['tenant_id'];

// Get booking details (same as before)
$bookingQuery = $conn->prepare("
    SELECT b.*, r.room_number, r.price, r.room_type, r.photo, 
           t.name AS tenant_name, t.email AS tenant_email, t.phone AS tenant_phone
    FROM bookings b
    JOIN rooms r ON b.room_id = r.room_id
    JOIN tenants t ON b.tenant_id = t.tenant_id
    WHERE b.booking_id = ? AND b.tenant_id = ?
");
$bookingQuery->bind_param("ii", $bookingId, $tenantId);
$bookingQuery->execute();
$booking = $bookingQuery->get_result()->fetch_assoc();

if (!$booking) {
    header("Location: rooms.php?error=booking_not_found");
    exit;
}

// Calculate duration in months (same as before)
$checkIn = new DateTime($booking['check_in_date']);
$checkOut = new DateTime($booking['check_out_date']);
$interval = $checkIn->diff($checkOut);
$months = $interval->m + ($interval->y * 12);
if ($interval->d > 0) $months++;

// Process payment if form submitted (same as before)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = $_POST['payment_method'];
    $referenceNumber = $_POST['reference_number'] ?? null;
    
    $paymentStmt = $conn->prepare("
        INSERT INTO payments (
            booking_id,
            tenant_id,
            amount_due,
            amount_paid,
            payment_method,
            reference_number,
            payment_status
        ) VALUES (?, ?, ?, ?, ?, ?, 'pending')
    ");
    $paymentStmt->bind_param(
        "iiddss",
        $bookingId,
        $tenantId,
        $booking['total_amount'],
        $booking['total_amount'],
        $paymentMethod,
        $referenceNumber
    );
    
    if ($paymentStmt->execute()) {
        $paymentId = $conn->insert_id;
        
        $updateBooking = $conn->prepare("UPDATE bookings SET status = 'confirmed' WHERE booking_id = ?");
        $updateBooking->bind_param("i", $bookingId);
        $updateBooking->execute();
        
        header("Location: booking_receipt.php?payment_id=$paymentId");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "includes/header.php"; ?>
    <title>Payment | MECMEC Boarding House</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Minimal custom CSS - most styling handled by Tailwind */
        .payment-method {
            transition: all 0.2s ease;
        }
        .payment-method.selected {
            box-shadow: 0 0 0 2px #3b82f6;
        } .navbar {
    background-color:#0f172a;
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
    <?php include "includes/topnav.php"; ?>
<br> <br><br><br>
    <section class="py-12 px-4 pt-200">
        <div class="max-w-6xl mx-auto">
            <!-- Progress Steps -->
            <div class="relative mb-12">
                <div class="flex justify-between">
                    <div class="text-center">
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-sm font-medium">Booking Details</span>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-2">
                            2
                        </div>
                        <span class="text-sm font-medium">Payment</span>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full flex items-center justify-center mx-auto mb-2">
                            3
                        </div>
                        <span class="text-sm font-medium">Confirmation</span>
                    </div>
                </div>
                <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 dark:bg-gray-700 -z-10">
                    <div class="h-full bg-blue-600 transition-all duration-500" style="width: 66%"></div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Payment Form -->
                <div class="lg:w-2/3">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 mr-4">
                                <i class="fas fa-credit-card text-xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold">Payment Method</h2>
                        </div>
                        
                        <form id="paymentForm" method="POST" class="space-y-4">
                            <!-- GCash Option -->
                            <div class="payment-method border border-gray-200 dark:border-gray-700 rounded-lg p-4 cursor-pointer"
                                 onclick="selectPaymentMethod('gcash')">
                                <input type="radio" id="gcash" name="payment_method" value="gcash" class="hidden" required>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-mobile-alt text-blue-500"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold">GCash</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Pay using your GCash account</p>
                                    </div>
                                </div>
                                <div id="gcash-reference" class="reference-input mt-3 hidden">
                                    <label class="block text-sm font-medium mb-2">GCash Reference Number</label>
                                    <input type="text" name="reference_number" 
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700"
                                           placeholder="Enter reference number">
                                </div>
                            </div>
                            
                            <!-- Bank Transfer Option -->
                            <div class="payment-method border border-gray-200 dark:border-gray-700 rounded-lg p-4 cursor-pointer"
                                 onclick="selectPaymentMethod('bank_transfer')">
                                <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer" class="hidden">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-university text-green-500"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold">Bank Transfer</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Direct bank transfer</p>
                                    </div>
                                </div>
                                <div id="bank-reference" class="reference-input mt-3 hidden">
                                    <label class="block text-sm font-medium mb-2">Bank Reference Number</label>
                                    <input type="text" name="reference_number" 
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700"
                                           placeholder="Enter reference number">
                                </div>
                            </div>
                            
                            <!-- Cash Option -->
                            <div class="payment-method border border-gray-200 dark:border-gray-700 rounded-lg p-4 cursor-pointer"
                                 onclick="selectPaymentMethod('cash')">
                                <input type="radio" id="cash" name="payment_method" value="cash" class="hidden">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/20 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-money-bill-wave text-yellow-500"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold">Cash Payment</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Pay in person at our office</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
                                <a href="booking.php?room_id=<?= $booking['room_id'] ?>" 
                                   class="text-blue-600 dark:text-blue-400 hover:underline font-medium flex items-center">
                                    <i class="fas fa-arrow-left mr-2"></i> Back to booking
                                </a>
                                <button type="submit" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200 flex items-center">
                                    <i class="fas fa-receipt mr-2"></i> Complete Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Booking Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 sticky top-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 mr-4">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <h2 class="text-xl font-bold">Booking Summary</h2>
                        </div>
                        
                        <div class="mb-6">
                            <img src="uploads/rooms/<?= htmlspecialchars($booking['photo']) ?>" 
                                 alt="Room <?= htmlspecialchars($booking['room_number']) ?>" 
                                 class="w-full h-48 object-cover rounded-lg mb-4">
                            <h3 class="text-lg font-bold">Room <?= htmlspecialchars($booking['room_number']) ?></h3>
                            <p class="text-gray-500 dark:text-gray-400"><?= htmlspecialchars($booking['room_type']) ?></p>
                        </div>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Tenant:</span>
                                <span class="font-medium"><?= htmlspecialchars($booking['tenant_name']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Check-in:</span>
                                <span><?= date('M j, Y', strtotime($booking['check_in_date'])) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Check-out:</span>
                                <span><?= date('M j, Y', strtotime($booking['check_out_date'])) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Duration:</span>
                                <span><?= $months ?> Month<?= $months > 1 ? 's' : '' ?></span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Monthly Rate:</span>
                                <span>₱<?= number_format($booking['price'], 2) ?></span>
                            </div>
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total Amount:</span>
                                <span class="text-blue-600 dark:text-blue-400">₱<?= number_format($booking['total_amount'], 2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    function selectPaymentMethod(method) {
        // Update UI
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('selected');
            el.classList.remove('border-blue-500');
            el.classList.add('border-gray-200', 'dark:border-gray-700');
        });
        
        const selected = event.currentTarget;
        selected.classList.add('selected', 'border-blue-500');
        selected.classList.remove('border-gray-200', 'dark:border-gray-700');
        
        // Check the radio button
        document.getElementById(method).checked = true;
        
        // Show/hide reference inputs
        document.querySelectorAll('.reference-input').forEach(el => {
            el.classList.add('hidden');
        });
        
        if (method === 'gcash') {
            document.getElementById('gcash-reference').classList.remove('hidden');
        } else if (method === 'bank_transfer') {
            document.getElementById('bank-reference').classList.remove('hidden');
        }
    }
    </script>

    <?php include "includes/footer.php"; ?>
</body>
</html>