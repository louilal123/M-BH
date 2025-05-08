<?php
require_once '../admin/functions/connection.php';

header('Content-Type: application/json');

if (!isset($_GET['room_id']) || !is_numeric($_GET['room_id'])) {
    echo json_encode([]);
    exit;
}

$roomId = intval($_GET['room_id']);
$query = "SELECT filename FROM room_images WHERE room_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $roomId);
$stmt->execute();
$result = $stmt->get_result();

$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row;
}

echo json_encode($images);
?>