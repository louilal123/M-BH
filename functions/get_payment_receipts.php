<?php
session_start();
include "../admin/functions/connection.php";

if (!isset($_SESSION["loggedin"])) {
    header("location: ../login.php");
    exit;
}

if (isset($_GET['payment_id'])) {
    $paymentId = $_GET['payment_id'];
    
    $stmt = $conn->prepare("
        SELECT p.*, b.room_id, b.check_in_date, b.check_out_date, 
               r.room_number, r.room_type, r.price,
               t.name AS tenant_name, t.email, t.phone
        FROM payments p
        JOIN bookings b ON p.booking_id = b.booking_id
        JOIN rooms r ON b.room_id = r.room_id
        JOIN tenants t ON b.tenant_id = t.tenant_id
        WHERE p.payment_id = ? AND b.tenant_id = ?
    ");
    $stmt->bind_param("ii", $paymentId, $_SESSION['tenant_id']);
    $stmt->execute();
    $payment = $stmt->get_result()->fetch_assoc();
    
    if ($payment) {
        echo '<div class="space-y-6">';
        echo '<div class="text-center border-b border-slate-200 dark:border-slate-700 pb-4">';
        echo '<h2 class="text-2xl font-bold">MECMEC Boarding House</h2>';
        echo '<p class="text-slate-500 dark:text-slate-400">Payment Receipt</p>';
        echo '</div>';
        
        echo '<div class="grid md:grid-cols-2 gap-6">';
        echo '<div>';
        echo '<h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Payment Information</h4>';
        echo '<div class="space-y-2">';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Receipt #:</span> ' . htmlspecialchars($payment['payment_id']) . '</p>';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Date:</span> ' . date('M d, Y h:i A', strtotime($payment['payment_date'])) . '</p>';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Status:</span> <span class="px-2 py-1 text-xs rounded-full ' . 
             ($payment['remarks'] == 'completed' ? 'status-completed' : 
              ($payment['remarks'] == 'pending' ? 'status-pending' : 'status-cancelled')) . '">' . 
             ucfirst(htmlspecialchars($payment['remarks'])) . '</span></p>';
        echo '</div>';
        echo '</div>';
        
        echo '<div>';
        echo '<h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Booking Information</h4>';
        echo '<div class="space-y-2">';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Room:</span> Room ' . htmlspecialchars($payment['room_number']) . '</p>';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Dates:</span> ' . date('M d, Y', strtotime($payment['check_in_date'])) . ' to ' . date('M d, Y', strtotime($payment['check_out_date'])) . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="border-t border-b border-slate-200 dark:border-slate-700 py-4 my-4">';
        echo '<div class="flex justify-between">';
        echo '<span class="font-medium">Amount Paid:</span>';
        echo '<span class="font-bold">₱' . number_format($payment['amount_paid'], 2) . '</span>';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="grid md:grid-cols-2 gap-6">';
        echo '<div>';
        echo '<h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Payment Method</h4>';
        echo '<div class="space-y-2">';
        echo '<p>' . ucfirst(htmlspecialchars($payment['payment_method'])) . '</p>';
        if (!empty($payment['reference_number'])) {
            echo '<p><span class="text-slate-500 dark:text-slate-400">Reference #:</span> ' . htmlspecialchars($payment['reference_number']) . '</p>';
        }
        echo '</div>';
        echo '</div>';
        
        echo '<div>';
        echo '<h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Tenant Information</h4>';
        echo '<div class="space-y-2">';
        echo '<p>' . htmlspecialchars($payment['tenant_name']) . '</p>';
        echo '<p>' . htmlspecialchars($payment['email']) . '</p>';
        echo '<p>' . htmlspecialchars($payment['phone']) . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="text-center text-slate-500 dark:text-slate-400 text-sm mt-8">';
        echo '<p>Thank you for your payment!</p>';
        echo '<p>This is an official receipt from MECMEC Boarding House</p>';
        echo '</div>';
        
        echo '</div>';
    } else {
        echo '<div class="text-center py-8 text-slate-500 dark:text-slate-400">';
        echo '<i class="fas fa-exclamation-circle text-3xl mb-4"></i>';
        echo '<p>Payment details not found</p>';
        echo '</div>';
    }
} else {
    echo '<div class="text-center py-8 text-slate-500 dark:text-slate-400">';
    echo '<i class="fas fa-exclamation-circle text-3xl mb-4"></i>';
    echo '<p>Invalid payment ID</p>';
    echo '</div>';
}
?>