<?php
session_start();
include "../admin/functions/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $bookingId = $_POST['booking_id'];
    
    // Verify that the booking belongs to the logged-in tenant
    $stmt = $conn->prepare("SELECT tenant_id FROM bookings WHERE booking_id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $booking = $result->fetch_assoc();
        if ($booking['tenant_id'] == $_SESSION['tenant_id']) {
            // Update booking status to cancelled
            $updateStmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = ?");
            $updateStmt->bind_param("i", $bookingId);
            
            if ($updateStmt->execute()) {
                // Get the updated booking data to return
                $updatedStmt = $conn->prepare("SELECT * FROM bookings WHERE booking_id = ?");
                $updatedStmt->bind_param("i", $bookingId);
                $updatedStmt->execute();
                $updatedResult = $updatedStmt->get_result();
                $updatedBooking = $updatedResult->fetch_assoc();
                
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Booking has been cancelled successfully',
                    'booking' => $updatedBooking
                ]);
                exit;
            }
        }
    }
    
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to cancel booking'
    ]);
    exit;
}

header("location: ../profile.php");
exit;
?>