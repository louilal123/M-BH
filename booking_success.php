<?php
session_start();
require_once 'admin/functions/connection.php';
require_once 'functions/load_tenant_data.php';

$tenantData = null;

if (isset($_SESSION['tenant_id'])) {
    $tenantData = loadTenantData($conn, $_SESSION['tenant_id']);
}

if (!isset($_GET['booking_id'])) {
    header("Location: 404.php");
    exit;
}

if (!isset($_SESSION['tenant_id'])) {
    header("Location: login.php");
    exit;
}

$bookingId = intval($_GET['booking_id']);
$tenantId = intval($_SESSION['tenant_id']);

$query = $conn->prepare("
    SELECT b.*, r.room_number, r.room_type, r.photo, r.description, 
           p.payment_method, p.reference_number, p.payment_date
    FROM bookings b
    JOIN rooms r ON b.room_id = r.room_id
    LEFT JOIN payments p ON b.booking_id = p.booking_id
    WHERE b.booking_id = ? AND b.tenant_id = ?
    ORDER BY p.payment_date DESC
    LIMIT 1
");

$query->bind_param("ii", $bookingId, $tenantId);
$query->execute();
$result = $query->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    header("Location: rooms.php");
    exit;
}

$checkIn = new DateTime($booking['check_in_date']);
$checkOut = new DateTime($booking['check_out_date']);
$interval = $checkIn->diff($checkOut);
$days = $interval->days;
$months = $interval->m + ($interval->y * 12);
$durationDisplay = '';
if ($months > 0) {
    $durationDisplay = $months . ' month' . ($months > 1 ? 's' : '');
    if ($interval->d > 0) {
        $durationDisplay .= ' and ' . $interval->d . ' day' . ($interval->d > 1 ? 's' : '');
    }
} else {
    $durationDisplay = $days . ' day' . ($days > 1 ? 's' : '');
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <?php include "includes/header.php"; ?>
    <title>Booking Confirmation | MECMEC Boarding House</title>
    <style>
        .navbar {
            background-color:#0f172a;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .card {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        .dark .card {
            background-color: #1e293b;
        }
        .divider {
            height: 1px;
            width: 100%;
            background-color: #e2e8f0;
            margin: 1rem 0;
        }
        .dark .divider {
            background-color: #334155;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background-color: #3b82f6;
            color: white;
            font-weight: 500;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background-color: #e2e8f0;
            color: #0f172a;
            font-weight: 500;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        .btn-secondary:hover {
            background-color: #cbd5e1;
        }
        .dark .btn-secondary {
            background-color: #334155;
            color: #f1f5f9;
        }
        .dark .btn-secondary:hover {
            background-color: #475569;
        }
        .step-number {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qr-code-container {
            max-width: 200px;
            margin: 0 auto;
            padding: 1rem;
            background: white;
            border-radius: 0.5rem;
            text-align: center;
        }
        .qr-code-container img {
            width: 100%;
            height: auto;
        }
        .print-only {
            display: none;
        }
        @media print {
            @page {
                size: A4 portrait;
                margin: 0.5cm;
            }
            body {
                background: white !important;
                color: black !important;
                font-size: 12pt;
                padding: 0;
                margin: 0;
            }
            header, footer {
                display: none;
            }
            .no-print {
                display: none !important;
            }
            .print-only {
                display: block !important;
            }
            .card {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                background: transparent !important;
            }
            .receipt-container {
                width: 100%;
                max-width: 21cm;
                margin: 0 auto;
                padding: 0.5cm;
            }
            .receipt-header {
                text-align: center;
                margin-bottom: 1rem;
                border-bottom: 2px solid #000;
                padding-bottom: 1rem;
            }
            .receipt-body {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
            }
            .receipt-section {
                flex: 1;
                min-width: 250px;
            }
            .receipt-footer {
                margin-top: 1rem;
                text-align: center;
                font-size: 0.9em;
                color: #666;
            }
            .qr-code-container {
                max-width: 150px;
                margin: 0 auto;
            }
            .text-right {
                text-align: right;
            }
            .text-center {
                text-align: center;
            }
            .font-bold {
                font-weight: bold;
            }
            .border-t {
                border-top: 1px solid #000;
            }
            .mt-4 {
                margin-top: 1rem;
            }
            .mb-4 {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-700 dark:bg-slate-900 dark:text-slate-200 transition-colors duration-300">
    <?php include "includes/topnav.php"; ?>

    <div class="pt-24 pb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Progress Steps -->
            <div class="relative mb-12 no-print">
                <div class="flex justify-between">
                    <div class="text-center">
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-sm font-medium">Booking Details</span>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-sm font-medium">Payment</span>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-sm font-medium">Success</span>
                    </div>
                </div>
                <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 dark:bg-gray-700 -z-10">
                    <div class="h-full bg-blue-600 transition-all duration-500" style="width: 100%"></div>
                </div>
            </div>

            <!-- Print Content -->
            <div class="receipt-container print-only">
                <div class="receipt-header">
                    <h1 class="text-2xl font-bold">MECMEC BOARDING HOUSE</h1>
                    <p>123 Boarding Street, City</p>
                    <p>Phone: (123) 456-7890 | Email: info@mecmec.com</p>
                    <h2 class="text-xl font-bold mt-2">BOOKING RECEIPT</h2>
                    <p class="text-sm"><?= date('F j, Y') ?></p>
                </div>

                <div class="receipt-body">
                    <div class="receipt-section">
                        <h3 class="font-bold border-t pt-2">BOOKING DETAILS</h3>
                        <p><strong>Booking ID:</strong> <?= $booking['booking_id'] ?></p>
                        <p><strong>Booking Date:</strong> <?= date('M j, Y h:i A', strtotime($booking['booking_date'])) ?></p>
                        <p><strong>Room:</strong> <?= htmlspecialchars($booking['room_number']) ?> (<?= htmlspecialchars($booking['room_type']) ?>)</p>
                        <p><strong>Check-in:</strong> <?= date('M j, Y', strtotime($booking['check_in_date'])) ?></p>
                        <p><strong>Check-out:</strong> <?= date('M j, Y', strtotime($booking['check_out_date'])) ?></p>
                        <p><strong>Duration:</strong> <?= $durationDisplay ?></p>
                    </div>

                    <div class="receipt-section">
                        <h3 class="font-bold border-t pt-2">TENANT INFORMATION</h3>
                        <p><strong>Name:</strong> <?= htmlspecialchars($tenantData['name']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($tenantData['email']) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($tenantData['phone']) ?></p>
                    </div>

                    <div class="receipt-section">
                        <h3 class="font-bold border-t pt-2">PAYMENT DETAILS</h3>
                        <p><strong>Payment Method:</strong> <?= isset($booking['payment_method']) ? ucwords(str_replace('_', ' ', $booking['payment_method'])) : 'Pending' ?></p>
                        <?php if (isset($booking['reference_number']) && $booking['reference_number']): ?>
                        <p><strong>Reference No.:</strong> <?= htmlspecialchars($booking['reference_number']) ?></p>
                        <?php endif; ?>
                      
                    </div>
                      <div class="receipt-section">
                          <p><strong>Payment Date:</strong> <?= isset($booking['payment_date']) ? date('M j, Y h:i A', strtotime($booking['payment_date'])) : 'Pending' ?></p>
                        <p class="font-bold mt-4"><strong>Total Amount:</strong> ₱<?= number_format($booking['total_amount'], 2) ?></p>
                    </div>
                </div>

                <div class="receipt-footer">
                    <p>Thank you for your booking! Please present this receipt upon check-in.</p>
                    <p class="mt-2">For inquiries, please contact us at (123) 456-7890</p>
                </div>
            </div>

            <!-- Regular Content -->
            <div class="text-center mb-12 no-print">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 dark:bg-green-900 rounded-full mb-6">
                    <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold mb-4">Booking Confirmed!</h1>
                <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    Thank you for your booking. Here are your booking details.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 no-print">
                <!-- Booking Summary -->
                <div class="lg:col-span-2">
                    <div class="card p-6 md:p-8 mb-8">
                        <h2 class="text-2xl font-bold mb-6">Booking Summary</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="p-4 bg-slate-100 dark:bg-slate-800 rounded-lg">
                                <h3 class="font-medium text-lg mb-4">Reservation Details</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 dark:text-slate-400">Booking ID:</span>
                                        <span class="font-medium"><?= $booking['booking_id'] ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 dark:text-slate-400">Room:</span>
                                        <span><?= htmlspecialchars($booking['room_number']) ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 dark:text-slate-400">Room Type:</span>
                                        <span><?= htmlspecialchars($booking['room_type']) ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 dark:text-slate-400">Check-in:</span>
                                        <span><?= date('M j, Y', strtotime($booking['check_in_date'])) ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 dark:text-slate-400">Check-out:</span>
                                        <span><?= date('M j, Y', strtotime($booking['check_out_date'])) ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 dark:text-slate-400">Duration:</span>
                                        <span><?= $durationDisplay ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 dark:text-slate-400">Booking Date:</span>
                                        <span><?= date('M j, Y h:i A', strtotime($booking['booking_date'])) ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-slate-100 dark:bg-slate-800 rounded-lg">
                                <h3 class="font-medium text-lg mb-4">Payment Details</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 dark:text-slate-400">Payment Method:</span>
                                        <span class="capitalize"><?= isset($booking['payment_method']) ? str_replace('_', ' ', $booking['payment_method']) : 'Pending' ?></span>
                                    </div>
                                    <?php if (isset($booking['reference_number']) && $booking['reference_number']): ?>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 dark:text-slate-400">Reference:</span>
                                        <span><?= htmlspecialchars($booking['reference_number']) ?></span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="border-t border-slate-200 dark:border-slate-700 my-2"></div>
                                    <div class="flex justify-between font-bold">
                                        <span>Total Due:</span>
                                        <span class="text-primary">₱<?= number_format($booking['total_amount'], 2) ?></span>
                                    </div>
                                </div>

                                <?php if (!isset($booking['payment_method']) || $booking['payment_method'] === 'gcash'): ?>
                                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800/50">
                                    <h4 class="font-medium mb-3">GCash Payment Instructions</h4>
                                    <div class="qr-code-container mb-3">
                                        <!-- admin\assets\img\image.png -->
                                        <img src="admin\assets\img\image.png" alt="GCash QR Code">
                                        <p class="text-sm mt-2">Scan to pay</p>
                                    </div>
                                    <div class="text-sm">
                                        <p class="mb-2">1. Open GCash app</p>
                                        <p class="mb-2">2. Tap "Scan QR"</p>
                                        <p class="mb-2">3. Scan the QR code above</p>
                                        <p class="mb-2">4. Enter amount: ₱<?= number_format($booking['total_amount'], 2) ?></p>
                                        <p>5. Complete payment</p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="p-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800/50">
                            <h3 class="font-medium text-lg mb-4">What's Next?</h3>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-blue-500 mt-1 mr-3"></i>
                                    <span>We've sent a confirmation email with your booking details</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-blue-500 mt-1 mr-3"></i>
                                    <span>Your room will be prepared for your arrival</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-blue-500 mt-1 mr-3"></i>
                                    <span>Contact us if you have any questions</span>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-6">
                            <button onclick="window.print()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-lg transition duration-200 flex items-center">
                                <i class="fas fa-print mr-2"></i> Print Receipt
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Room Summary -->
                <div class="lg:col-span-1">
                    <div class="card p-6 sticky top-6">
                        <h2 class="text-xl font-bold mb-6">Room Information</h2>
                        
                        <?php if (isset($booking['photo']) && $booking['photo']): ?>
                        <img src="uploads/rooms/<?= htmlspecialchars($booking['photo']) ?>" 
                             alt="Room <?= htmlspecialchars($booking['room_number']) ?>" 
                             class="w-full h-48 object-cover rounded-lg mb-4">
                        <?php endif; ?>
                        
                        <h3 class="text-lg font-bold mb-2">Room <?= htmlspecialchars($booking['room_number']) ?></h3>
                        <p class="text-slate-600 dark:text-slate-300 mb-4"><?= isset($booking['description']) ? htmlspecialchars($booking['description']) : 'No description available.' ?></p>
                        
                        <div class="divider"></div>
                        
                        <div class="flex flex-col mt-6 space-y-3">
                            <a href="tenant_dashboard.php" class="btn-primary text-center">
                                <i class="fas fa-user-circle mr-2"></i> View Dashboard
                            </a>
                            <a href="rooms.php" class="btn-secondary text-center">
                                <i class="fas fa-home mr-2"></i> Browse Rooms
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>

    <script>
        // Print functionality
        function printReceipt() {
            window.print();
        }
    </script>
</body>
</html>