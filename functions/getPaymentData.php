<?php
include('connection.php'); // Database connection

$query = "SELECT 
    SUM(CASE WHEN payment_status = 'paid' THEN amount_paid ELSE 0 END) AS total_paid,
    SUM(CASE WHEN payment_status = 'pending' THEN amount_due ELSE 0 END) AS total_not_paid
FROM payments";

$result = $conn->query($query);
$data = $result->fetch_assoc();

// Return data as JSON
echo json_encode([
    'totalPaid' => $data['total_paid'],
    'totalNotPaid' => $data['total_not_paid']
]);
?>
