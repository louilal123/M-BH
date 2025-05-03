<?php
include 'connection.php';

header('Content-Type: application/json');

if (!isset($_GET['room_id'])) {
    echo json_encode(['error' => 'Room ID is required']);
    exit();
}

$room_id = (int)$_GET['room_id'];

try {
    $stmt = $conn->prepare("SELECT * FROM room_images WHERE room_id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
    
    echo json_encode($images);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>