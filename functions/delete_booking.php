<?php
session_start();
include('connection.php'); // Include database connection

if (isset($_GET['id'])) {
    $bookingId = mysqli_real_escape_string($conn, $_GET['id']);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Step 1: Delete payments associated with this booking
        $deletePaymentsQuery = "DELETE FROM payments WHERE booking_id = '$bookingId'";
        $conn->query($deletePaymentsQuery);

        // Step 2: Delete the booking itself
        $deleteBookingQuery = "DELETE FROM bookings WHERE booking_id = '$bookingId'";
        $conn->query($deleteBookingQuery);

        // Commit the transaction
        $conn->commit();

        $_SESSION['status'] = "Booking and associated payments deleted successfully!";
        $_SESSION['status_icon'] = "success";
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        $_SESSION['status'] = "Failed to delete booking: " . $e->getMessage();
        $_SESSION['status_icon'] = "error";
    }

    header("Location: ../room_assignment.php");
    exit();
}
?>
