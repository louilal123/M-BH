<?php
session_start();
require_once 'admin/functions/connection.php';


if (!isset($_GET['booking_id']) || !isset($_SESSION['tenant_id'])) {
    header("Location: rooms.php");
    exit;
}

$bookingId = $_GET['booking_id'];
$tenantId = $_SESSION['tenant_id'];

// Get booking details
$bookingQuery = $conn->prepare("
    SELECT b.*, r.room_number, r.room_type 
    FROM bookings b
    JOIN rooms r ON b.room_id = r.room_id
    WHERE b.booking_id = ? AND b.tenant_id = ?
");
$bookingQuery->bind_param("ii", $bookingId, $tenantId);
$bookingQuery->execute();
$booking = $bookingQuery->get_result()->fetch_assoc();

if (!$booking) {
    header("Location: rooms.php?error=booking_not_found");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <?php include "includes/header.php"; ?>
    <title>Booking Confirmation | MECMEC Boarding House</title>
</head>
<body class="bg-slate-50 text-slate-700 dark:bg-slate-900 dark:text-slate-200 transition-colors duration-300">
    <?php include "includes/topnav.php"; ?>

    <main class="container mx-auto px-4 py-12 max-w-4xl">
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 dark:bg-green-900 rounded-full mb-6">
                <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold mb-4">Booking Confirmed!</h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                Thank you for your booking. Your reservation is now pending payment.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                <h2 class="text-xl font-bold mb-6">Booking Details</h2>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Booking ID</span>
                        <span class="font-medium"><?php echo $booking['booking_id']; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Room</span>
                        <span><?php echo htmlspecialchars($booking['room_number']); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Room Type</span>
                        <span><?php echo htmlspecialchars($booking['room_type']); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Check-in</span>
                        <span><?php echo date('M j, Y', strtotime($booking['check_in_date'])); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Check-out</span>
                        <span><?php echo date('M j, Y', strtotime($booking['check_out_date'])); ?></span>
                    </div>
                    <div class="border-t border-slate-200 dark:border-slate-700 my-3"></div>
                    <div class="flex justify-between font-medium">
                        <span>Total Amount</span>
                        <span>₱<?php echo number_format($booking['total_amount'], 2); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                <h2 class="text-xl font-bold mb-6">Payment Information</h2>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Payment Method</span>
                        <span class="capitalize"><?php echo str_replace('_', ' ', $booking['payment_method']); ?></span>
                    </div>
                    
                    <?php if ($booking['payment_method'] === 'gcash'): ?>
                        <div class="bg-slate-100 dark:bg-slate-700 rounded-lg p-4 mt-4">
                            <h3 class="font-medium mb-3">GCash Payment Instructions</h3>
                            <ol class="list-decimal list-inside space-y-2 text-sm">
                                <li>Open your GCash app</li>
                                <li>Send payment to <span class="font-medium">0917-123-4567</span></li>
                                <li>Use reference number: <span class="font-medium">BOOK-<?php echo $booking['booking_id']; ?></span></li>
                                <li>Upload payment proof below</li>
                            </ol>
                            
                            <form class="mt-4">
                                <label class="block text-sm font-medium mb-2">Upload Payment Proof</label>
                                <input type="file" class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-primary/10 file:text-primary
                                    hover:file:bg-primary/20
                                ">
                                <button type="submit" class="mt-2 px-4 py-2 bg-primary text-white rounded-lg text-sm">
                                    Submit Proof
                                </button>
                            </form>
                        </div>
                    <?php elseif ($booking['payment_method'] === 'bank_transfer'): ?>
                        <div class="bg-slate-100 dark:bg-slate-700 rounded-lg p-4 mt-4">
                            <h3 class="font-medium mb-3">Bank Transfer Instructions</h3>
                            <div class="text-sm space-y-2">
                                <p><span class="font-medium">Bank:</span> BPI</p>
                                <p><span class="font-medium">Account Name:</span> MECMEC Boarding House</p>
                                <p><span class="font-medium">Account Number:</span> 1234-5678-90</p>
                                <p><span class="font-medium">Amount:</span> ₱<?php echo number_format($booking['total_amount'], 2); ?></p>
                                <p><span class="font-medium">Reference:</span> BOOK-<?php echo $booking['booking_id']; ?></p>
                            </div>
                            
                            <form class="mt-4">
                                <label class="block text-sm font-medium mb-2">Upload Payment Proof</label>
                                <input type="file" class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-primary/10 file:text-primary
                                    hover:file:bg-primary/20
                                ">
                                <button type="submit" class="mt-2 px-4 py-2 bg-primary text-white rounded-lg text-sm">
                                    Submit Proof
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="bg-slate-100 dark:bg-slate-700 rounded-lg p-4 mt-4">
                            <p class="text-sm">Please complete your payment at the front desk within 24 hours to confirm your booking.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="text-center">
            <a href="rooms.php" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary-dark">
                Back to Rooms
            </a>
            <a href="tenant_dashboard.php" class="ml-4 inline-flex items-center px-6 py-3 border border-slate-300 dark:border-slate-600 text-base font-medium rounded-lg shadow-sm text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700">
                View My Bookings
            </a>
        </div>
    </main>

    <?php include "includes/footer.php"; ?>
</body>
</html>