<?php
// admin/functions/roccupancy-chart.php
require 'connection.php';

$occupancyQuery = $conn->prepare("
    SELECT 
        r.room_type,
        COUNT(b.booking_id) AS occupied_rooms
    FROM rooms r
    LEFT JOIN bookings b ON r.room_id = b.room_id 
        AND b.status IN ('confirmed', 'active')
    GROUP BY r.room_type
");
$occupancyQuery->execute();
$result = $occupancyQuery->get_result();

$occupancy = [];
while ($row = $result->fetch_assoc()) {
    $occupancy[] = [
        'label' => $row['room_type'],
        'value' => (int) $row['occupied_rooms']
    ];
}

echo json_encode(['occupancy' => $occupancy]);
