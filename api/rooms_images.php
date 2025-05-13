<?php
// C:\xampp\htdocs\M-BH\api\rooms_images.php
header('Content-Type: application/json');
require_once '../admin/functions/connection.php';

if (isset($_GET['room_id'])) {
    $room_id = $conn->real_escape_string($_GET['room_id']);
    $query = "SELECT image_id, image_path FROM room_images WHERE room_id = $room_id";
    $result = $conn->query($query);
    
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
    
    echo json_encode($images);
} else {
    echo json_encode([]);
}

$conn->close();
?>