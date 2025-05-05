<?php
session_start();
require_once '../admin/functions/connection.php';

// Clear any previous output
ob_clean();
header('Content-Type: application/json');

if (!isset($_SESSION['tenant_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to book a room']);
    exit;
}

$response = ['success' => false, 'message' => ''];

try {
    // Validate input
    $required = ['room_id', 'check_in_date', 'check_out_date'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    $tenantId = $_SESSION['tenant_id'];
    $roomId = (int)$_POST['room_id'];
    $checkIn = $_POST['check_in_date'];
    $checkOut = $_POST['check_out_date'];
    $specialRequests = $_POST['special_requests'] ?? '';

    // Validate dates
    if (strtotime($checkOut) <= strtotime($checkIn)) {
        throw new Exception("Check-out date must be after check-in date");
    }

    // Check room availability
    $stmt = $conn->prepare("SELECT COUNT(*) FROM bookings 
        WHERE room_id = ? 
        AND status IN ('pending', 'confirmed')
        AND (
            (check_in_date <= ? AND check_out_date >= ?) OR
            (check_in_date <= ? AND check_out_date >= ?) OR
            (check_in_date >= ? AND check_out_date <= ?)
        )");
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("issssss", $roomId, $checkOut, $checkIn, $checkIn, $checkOut, $checkIn, $checkOut);
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $stmt->bind_result($conflictingBookings);
    $stmt->fetch();
    $stmt->close();

    if ($conflictingBookings > 0) {
        throw new Exception("Room is not available for the selected dates");
    }

    // Get room price
    $roomQuery = $conn->prepare("SELECT price FROM rooms WHERE room_id = ?");
    if (!$roomQuery) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $roomQuery->bind_param("i", $roomId);
    $roomQuery->execute();
    $roomResult = $roomQuery->get_result();
    $room = $roomResult->fetch_assoc();
    $roomQuery->close();

    if (!$room) {
        throw new Exception("Room not found");
    }
// Calculate duration and total
$duration = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
$months = ceil($duration / 30);
$totalAmount = $room['price'] * $months;

// Create booking with total amount
$stmt = $conn->prepare("INSERT INTO bookings (
    tenant_id, 
    room_id, 
    check_in_date, 
    check_out_date, 
    special_requests,
    total_amount,
    status
) VALUES (?, ?, ?, ?, ?, ?, 'pending')");

if (!$stmt) {
    throw new Exception("Prepare failed: " . $conn->error);
}

$stmt->bind_param(
    "iisssd",
    $tenantId,
    $roomId,
    $checkIn,
    $checkOut,
    $specialRequests,
    $totalAmount
);

if ($stmt->execute()) {
    $bookingId = $conn->insert_id;
    $response = [
        'success' => true,
        'message' => 'Booking created successfully',
        'booking_id' => $bookingId,
        'total_amount' => $totalAmount  // Return in response
    ];
} else {
    throw new Exception("Database error: " . $stmt->error);
}
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Ensure no other output is sent
ob_end_clean();
echo json_encode($response);
exit;
?>