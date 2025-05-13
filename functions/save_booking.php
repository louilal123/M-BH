<?php
// C:\xampp\htdocs\M-BH\functions\save_booking.php
header('Content-Type: application/json');
session_start();
require_once '../admin/functions/connection.php';

$response = ['success' => false, 'message' => '', 'booking_id' => 0, 'type' => 'error'];

try {
    if (!isset($_SESSION['tenant_id'])) throw new Exception("Session expired. Please login again.");
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception("Invalid request method.");

    $required = ['room_id', 'check_in_date', 'check_out_date', 'total_amount'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) throw new Exception("Missing required field: $field");
    }

    $conn->begin_transaction();

    $bookingQuery = $conn->prepare("
        INSERT INTO bookings 
        (tenant_id, room_id, check_in_date, check_out_date, booking_date, status, special_requests, total_amount) 
        VALUES (?, ?, ?, ?, NOW(), 'pending', ?, ?)
    ");
    
    $special_requests = $_POST['special_requests'] ?? '';
    $total_amount = (float)$_POST['total_amount'];
    
    $bookingQuery->bind_param(
        "iisssd",
        $_SESSION['tenant_id'],
        $_POST['room_id'],
        $_POST['check_in_date'],
        $_POST['check_out_date'],
        $special_requests,
        $total_amount
    );
    
    if (!$bookingQuery->execute()) throw new Exception("Failed to create booking: " . $bookingQuery->error);
    
    $bookingId = $conn->insert_id;
    $conn->commit();
    unset($_SESSION['pending_booking']);

    $response = [
        'success' => true,
        'message' => 'Booking saved successfully! Redirecting to payment...',
        'booking_id' => $bookingId,
        'type' => 'success'
    ];

} catch (Exception $e) {
    if (isset($conn) && $conn instanceof mysqli) $conn->rollback();
    $response['message'] = $e->getMessage();
    $response['type'] = 'error';
    error_log("Booking Error: " . $e->getMessage());
}

ob_end_clean();
echo json_encode($response);
exit;
?>