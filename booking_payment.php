<?php
session_start();
require_once 'admin/functions/connection.php';

if (!isset($_SESSION['tenant_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingData = [
        'room_id' => $_POST['room_id'],
        'check_in_date' => $_POST['check_in_date'],
        'check_out_date' => $_POST['check_out_date'],
        'special_requests' => $_POST['special_requests'] ?? '',
        'total_amount' => $_POST['total_amount']
    ];
    
    $_SESSION['pending_booking'] = json_encode($bookingData);
} elseif (!isset($_SESSION['pending_booking'])) {
    header("Location: rooms.php");
    exit;
} else {
    $bookingData = json_decode($_SESSION['pending_booking'], true);
}

$roomQuery = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
$roomQuery->bind_param("i", $bookingData['room_id']);
$roomQuery->execute();
$room = $roomQuery->get_result()->fetch_assoc();

if (!$room) {
    header("Location: rooms.php?error=room_not_found");
    exit;
}

$tenantQuery = $conn->prepare("SELECT name, email, phone FROM tenants WHERE tenant_id = ?");
$tenantQuery->bind_param("i", $_SESSION['tenant_id']);
$tenantQuery->execute();
$tenant = $tenantQuery->get_result()->fetch_assoc();

// Calculate duration in days for display
$checkIn = new DateTime($bookingData['check_in_date']);
$checkOut = new DateTime($bookingData['check_out_date']);
$duration = $checkIn->diff($checkOut)->days;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "includes/header.php"; ?>
    <title>Payment | MECMEC Boarding House</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script><style>
        .payment-method {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .payment-method.selected {
            box-shadow: 0 0 0 2px #3b82f6;
        }
        .payment-method input[type="radio"]:checked + div {
            background-color: #f0f7ff;
        } .dark .payment-method input[type="radio"]:checked + div {
        background-color: transparent; /* Darker blue for dark mode */
    }
        .navbar {
            background-color:#0f172a;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .reference-input {
            display: none;
        }
        .reference-input.show {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
    <?php include "includes/topnav.php"; ?>
    <br><br><br><br>

    <section class="py-12 px-4">
        <div class="max-w-6xl mx-auto">
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
                        <span class="text-sm font-medium">Success</span>
                    </div>
                </div>
                <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 dark:bg-gray-700 -z-10">
                    <div class="h-full bg-blue-600 transition-all duration-500" style="width: 66%"></div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <div class="lg:w-2/3">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 mr-4">
                                <i class="fas fa-credit-card text-xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold">Payment Method</h2>
                        </div>
                        
                        <form id="paymentForm" method="POST" action="functions/save_booking.php" class="space-y-4">
                            <input type="hidden" name="room_id" value="<?= $bookingData['room_id'] ?>">
                            <input type="hidden" name="check_in_date" value="<?= $bookingData['check_in_date'] ?>">
                            <input type="hidden" name="check_out_date" value="<?= $bookingData['check_out_date'] ?>">
                            <input type="hidden" name="special_requests" value="<?= htmlspecialchars($bookingData['special_requests']) ?>">
                            <input type="hidden" name="total_amount" value="<?= $bookingData['total_amount'] ?>">
                            
                          <div class="payment-method border border-gray-200 dark:border-gray-700 rounded-lg p-4"
                                onclick="selectPaymentMethod('gcash')">
                                <input type="radio" id="gcash" name="payment_method" value="gcash" class="hidden">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-mobile-alt text-blue-500"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold">GCash</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Pay using your GCash account</p>
                                    </div>
                                </div>

                                <div id="gcash-reference" class="reference-input mt-3">
                                    <label for="gcash_reference" class="block text-sm font-medium mb-2">GCash Reference Number</label>
                                    <input type="text" id="gcash_reference" name="gcash_reference"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700"
                                        placeholder="Enter GCash number"
                                        required>
                                    <span id="gcash_error" class="text-red-500 text-sm mt-1 hidden">Invalid GCash number. Please use a Globe number like 09551234567.</span>
                                </div>
                            </div>
                            
                            <div class="payment-method border border-gray-200 dark:border-gray-700 rounded-lg p-4"
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
                                <a href="booking.php" class="text-blue-600 dark:text-blue-400 hover:underline font-medium flex items-center">
                                    <i class="fas fa-arrow-left mr-2"></i> Cancel
                                </a>
                             <button type="submit" id="submitBtn" disabled
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200 flex items-center opacity-50 cursor-not-allowed">
                                <i class="fas fa-receipt mr-2"></i> Complete Payment
                            </button>

                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="lg:w-1/3">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 sticky top-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 mr-4">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <h2 class="text-xl font-bold">Booking Summary</h2>
                        </div>
                        
                        <div class="mb-6">
                            <img src="uploads/rooms/<?= htmlspecialchars($room['photo']) ?>" 
                                 alt="Room <?= htmlspecialchars($room['room_number']) ?>" 
                                 class="w-full h-48 object-cover rounded-lg mb-4">
                            <h3 class="text-lg font-bold">Room <?= htmlspecialchars($room['room_number']) ?></h3>
                            <p class="text-gray-500 dark:text-gray-400"><?= htmlspecialchars($room['room_type']) ?></p>
                        </div>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Tenant:</span>
                                <span class="font-medium"><?= htmlspecialchars($tenant['name']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Check-in:</span>
                                <span><?= date('M j, Y', strtotime($bookingData['check_in_date'])) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Check-out:</span>
                                <span><?= date('M j, Y', strtotime($bookingData['check_out_date'])) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Duration:</span>
                                <span><?= $duration ?> Day<?= $duration > 1 ? 's' : '' ?></span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Daily Rate:</span>
                                <span>₱<?= number_format($room['price'], 2) ?></span>
                            </div>
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total Amount:</span>
                                <span class="text-blue-600 dark:text-blue-400">₱<?= number_format($bookingData['total_amount'], 2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<script>
  const gcashInput = document.getElementById('gcash_reference');
  const gcashError = document.getElementById('gcash_error');
  const submitBtn = document.getElementById('submitBtn');

  const gcashRegex = /^09(4[5]|5\d|6\d|7\d|15|16|17|27|35|37|38)\d{7}$/;

  gcashInput.addEventListener('input', () => {
    const isValid = gcashRegex.test(gcashInput.value);

    if (isValid) {
      gcashError.classList.add('hidden');
      gcashInput.classList.remove('border-red-500');
      gcashInput.classList.add('border-gray-300', 'dark:border-gray-600');
      submitBtn.disabled = false;
      submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
      gcashError.classList.remove('hidden');
      gcashInput.classList.remove('border-gray-300', 'dark:border-gray-600');
      gcashInput.classList.add('border-red-500');
      submitBtn.disabled = true;
      submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
  });
</script>

    <script>
            function selectPaymentMethod(method) {
                document.querySelectorAll('.payment-method').forEach(el => {
                    el.classList.remove('selected');
                });
                
                document.querySelectorAll('.reference-input').forEach(el => {
                    el.classList.remove('show');
                });
                
                const radio = document.getElementById(method);
                if (radio) {
                    radio.checked = true;
                    radio.closest('.payment-method').classList.add('selected');
                    
                    if (method === 'gcash') {
                        document.getElementById('gcash-reference').classList.add('show');
                    } else if (method === 'bank_transfer') {
                        document.getElementById('bank-reference').classList.add('show');
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                selectPaymentMethod('gcash');
                
                document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethod) {
                alert('Please select a payment method');
                return;
            }
            
            let referenceNumber = '';
            
            // Check reference number for methods that require it
            if (paymentMethod.value === 'gcash') {
                const referenceInput = document.querySelector('input[name="gcash_reference"]');
                if (!referenceInput || !referenceInput.value.trim()) {
                    alert('Please enter a GCash reference number');
                    return;
                }
                referenceNumber = referenceInput.value.trim();
            } 
            else if (paymentMethod.value === 'bank_transfer') {
                const referenceInput = document.querySelector('input[name="bank_reference"]');
                if (!referenceInput || !referenceInput.value.trim()) {
                    alert('Please enter a bank reference number');
                    return;
                }
                referenceNumber = referenceInput.value.trim();
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            
            const formData = new FormData(this);
            
            if (referenceNumber) {
                formData.append('reference_number', referenceNumber);
            }
            formData.append('tenant_id', <?= $_SESSION['tenant_id'] ?? 0 ?>);

                    fetch(this.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => { throw new Error(text) });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.booking_id) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                background: '#d4edda',
                                iconColor: '#155724',
                                color: '#155724'
                            }).then(() => {
                                window.location.href = `booking_success.php?booking_id=${data.booking_id}`;
                            });
                        } else {
                            throw new Error(data.message || 'Payment processing failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        
                        // Parse the error if it's JSON
                        let errorMessage = error.message;
                        try {
                            const errorData = JSON.parse(error.message);
                            errorMessage = errorData.message || error.message;
                        } catch (e) {
                            // Not JSON, use original message
                        }
                        
                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true,
                            background: '#f8d7da',
                            iconColor: '#721c24',
                            color: '#721c24'
                        });
                        
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    });
                });
            });
    </script>

    <?php include "includes/footer.php"; ?>
</body>
</html>