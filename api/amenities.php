<?php
header('Content-Type: application/json');
require_once '../admin/functions/connection.php';

$response = ['success' => false, 'data' => []];

try {
    $query = "SELECT amenity_id, name, description, icon FROM amenities";
    $result = $conn->query($query);

    if ($result) {
        $response['success'] = true;
        while ($row = $result->fetch_assoc()) {
            $response['data'][] = $row;
        }
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>