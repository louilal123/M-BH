<?php
session_start();
include('connection.php');

if (isset($_GET['id'])) {
    $booking_id = intval($_GET['id']); // sanitize input

    // Get room_id before deleting
    $room_query = $conn->query("SELECT room_id FROM bookings WHERE booking_id = $booking_id");
    $room_data = $room_query->fetch_assoc();
    $room_id = $room_data['room_id'];

    $sql = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        // Check if room has no other active bookings
        $check_room = $conn->query("SELECT COUNT(*) as count FROM bookings 
            WHERE room_id = $room_id 
            AND status NOT IN ('cancelled', 'completed')");
        $room_count = $check_room->fetch_assoc()['count'];
        
        if ($room_count == 0) {
            $conn->query("UPDATE rooms SET availability = 'available' WHERE room_id = $room_id");
        }
        
        $_SESSION['status'] = "Booking deleted successfully!";
        $_SESSION['status_icon'] = "success";
    } else {
        $_SESSION['status'] = "Booking deletion failed!";
        $_SESSION['status_icon'] = "error";
    }

    $stmt->close();
    $conn->close();
    header("Location: ../bookings.php");
    exit();
} else {
    $_SESSION['status'] = "No booking ID provided!";
    $_SESSION['status_icon'] = "warning";
    header("Location: ../bookings.php");
    exit();
}
?>