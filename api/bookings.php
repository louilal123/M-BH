<?php
header('Content-Type: application/json');
require_once '../admin/functions/connection.php';

// For security, you might want to add authentication here
$query = "SELECT b.booking_id, t.name as tenant_name, r.room_number, 
          b.check_in_date, b.check_out_date, b.booking_date, 
          b.status, b.total_amount 
          FROM bookings b
          JOIN tenants t ON b.tenant_id = t.tenant_id
          JOIN rooms r ON b.room_id = r.room_id
          ORDER BY b.booking_date DESC";
$result = $conn->query($query);

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode($bookings);
$conn->close();
?>