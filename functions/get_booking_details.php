<?php
session_start();
include "../admin/functions/connection.php";

if (isset($_GET['booking_id'])) {
    $bookingId = $_GET['booking_id'];
    
    $stmt = $conn->prepare("
        SELECT b.*, r.room_number, r.room_type, r.price, r.description, 
               t.name AS tenant_name, t.email, t.phone
        FROM bookings b
        JOIN rooms r ON b.room_id = r.room_id
        JOIN tenants t ON b.tenant_id = t.tenant_id
        WHERE b.booking_id = ? AND b.tenant_id = ?
    ");
    $stmt->bind_param("ii", $bookingId, $_SESSION['tenant_id']);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();
    
    if ($booking) {
        // Get payments for this booking
        $paymentStmt = $conn->prepare("
            SELECT * FROM payments 
            WHERE booking_id = ?
            ORDER BY payment_date DESC
        ");
        $paymentStmt->bind_param("i", $bookingId);
        $paymentStmt->execute();
        $payments = $paymentStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Calculate total paid
        $totalPaid = 0;
        foreach ($payments as $payment) {
            $totalPaid += $payment['amount_paid'];
        }
        
        // Calculate balance
        $balance = $booking['total_amount'] - $totalPaid;
        
        echo '<div class="space-y-6">';
        echo '<div class="grid md:grid-cols-2 gap-6">';
        echo '<div>';
        echo '<h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Booking Information</h4>';
        echo '<div class="space-y-2">';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Booking ID:</span> #' . htmlspecialchars($booking['booking_id']) . '</p>';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Status:</span> <span class="px-2 py-1 text-xs rounded-full ' . 
             ($booking['status'] == 'active' ? 'status-active' : 
              ($booking['status'] == 'pending' ? 'status-pending' : 'status-cancelled')) . '">' . 
             ucfirst(htmlspecialchars($booking['status'])) . '</span></p>';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Booking Date:</span> ' . date('M d, Y h:i A', strtotime($booking['booking_date'])) . '</p>';
        echo '</div>';
        echo '</div>';
        
        echo '<div>';
        echo '<h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Room Information</h4>';
        echo '<div class="space-y-2">';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Room:</span> Room ' . htmlspecialchars($booking['room_number']) . '</p>';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Type:</span> ' . htmlspecialchars($booking['room_type']) . '</p>';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Price:</span> ₱' . number_format($booking['price'], 2) . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="grid md:grid-cols-2 gap-6">';
        echo '<div>';
        echo '<h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Booking Dates</h4>';
        echo '<div class="space-y-2">';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Check-in:</span> ' . date('M d, Y', strtotime($booking['check_in_date'])) . '</p>';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Check-out:</span> ' . date('M d, Y', strtotime($booking['check_out_date'])) . '</p>';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Duration:</span> ' . 
             round((strtotime($booking['check_out_date']) - strtotime($booking['check_in_date'])) / (60 * 60 * 24)) . ' days</p>';
        echo '</div>';
        echo '</div>';
        
        echo '<div>';
        echo '<h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Payment Summary</h4>';
        echo '<div class="space-y-2">';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Total Amount:</span> ₱' . number_format($booking['total_amount'], 2) . '</p>';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Total Paid:</span> ₱' . number_format($totalPaid, 2) . '</p>';
        echo '<p><span class="text-slate-500 dark:text-slate-400">Balance:</span> ₱' . number_format($balance, 2) . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        if (!empty($booking['special_requests'])) {
            echo '<div>';
            echo '<h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Special Requests</h4>';
            echo '<p class="text-slate-600 dark:text-slate-300">' . nl2br(htmlspecialchars($booking['special_requests'])) . '</p>';
            echo '</div>';
        }
        
        if (!empty($payments)) {
            echo '<div>';
            echo '<h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Payment History</h4>';
            echo '<div class="overflow-x-auto">';
            echo '<table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">';
            echo '<thead class="bg-slate-50 dark:bg-slate-700">';
            echo '<tr>';
            echo '<th class="px-4 py-2 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase">Date</th>';
            echo '<th class="px-4 py-2 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase">Amount</th>';
            echo '<th class="px-4 py-2 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase">Method</th>';
            echo '<th class="px-4 py-2 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase">Status</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">';
            
            foreach ($payments as $payment) {
                echo '<tr>';
                echo '<td class="px-4 py-2 whitespace-nowrap">' . date('M d, Y', strtotime($payment['payment_date'])) . '</td>';
                echo '<td class="px-4 py-2 whitespace-nowrap">₱' . number_format($payment['amount_paid'], 2) . '</td>';
                echo '<td class="px-4 py-2 whitespace-nowrap">' . ucfirst(htmlspecialchars($payment['payment_method'])) . '</td>';
                echo '<td class="px-4 py-2 whitespace-nowrap">';
                echo '<span class="px-2 py-1 text-xs rounded-full ' . 
                     ($payment['remarks'] == 'completed' ? 'status-completed' : 
                      ($payment['remarks'] == 'pending' ? 'status-pending' : 'status-cancelled')) . '">';
                echo ucfirst(htmlspecialchars($payment['remarks']));
                echo '</span>';
                echo '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
    } else {
        echo '<div class="text-center py-8 text-slate-500 dark:text-slate-400">';
        echo '<i class="fas fa-exclamation-circle text-3xl mb-4"></i>';
        echo '<p>Booking details not found</p>';
        echo '</div>';
    }
} else {
    echo '<div class="text-center py-8 text-slate-500 dark:text-slate-400">';
    echo '<i class="fas fa-exclamation-circle text-3xl mb-4"></i>';
    echo '<p>Invalid booking ID</p>';
    echo '</div>';
}
?>