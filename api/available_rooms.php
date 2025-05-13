<?php
header('Content-Type: application/json');
require_once '../admin/functions/connection.php';

$response = ['success' => false, 'data' => []];

try {
    $query = "SELECT r.room_id, r.room_number, r.description, r.price, r.room_type, 
              (SELECT GROUP_CONCAT(image_path) FROM room_images WHERE room_id = r.room_id) as images
              FROM rooms r WHERE r.availability = 1";
    $result = $conn->query($query);

    if ($result) {
        $response['success'] = true;
        while ($row = $result->fetch_assoc()) {
            $row['images'] = $row['images'] ? explode(',', $row['images']) : [];
            $response['data'][] = $row;
        }
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>