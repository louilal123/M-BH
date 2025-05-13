<?php
header('Content-Type: application/json');
session_start();
require_once 'connection.php';

$response = ['success' => false, 'message' => '', 'type' => 'error'];

try {
    if (!isset($_SESSION['admin_id'])) throw new Exception("Unauthorized access");
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception("Invalid request method");

    $required = ['booking_id', 'status'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) throw new Exception("Missing required field: $field");
    }

    $validStatuses = ['pending', 'confirmed', 'cancelled'];
    if (!in_array($_POST['status'], $validStatuses)) {
        throw new Exception("Invalid status value");
    }

    // Removed updated_at from this query
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE booking_id = ?");
    $stmt->bind_param("si", $_POST['status'], $_POST['booking_id']);
    
    if (!$stmt->execute()) throw new Exception("Failed to update booking status: " . $stmt->error);

    $response = [
        'success' => true,
        'message' => 'Booking status updated successfully!',
        'type' => 'success'
    ];

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
?>