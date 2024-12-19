<?php
session_start(); // Start the session to store and display messages
include('connection.php'); // Include your database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $booking_id = $_POST['booking_id'];
    $tenant_id = $_POST['tenant'];
    $room_id = $_POST['room'];
    $checkin_date = $_POST['checkin'];

    // Calculate check-out date (30 days after check-in date)
    $checkout_date = date('Y-m-d', strtotime($checkin_date . ' + 30 days'));

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Step 1: Update the booking record
        $bookingQuery = "UPDATE bookings
                         SET tenant_id = ?, room_id = ?, check_in_date = ?, check_out_date = ?
                         WHERE booking_id = ?";
        $stmt = $conn->prepare($bookingQuery);
        $stmt->bind_param('iissi', $tenant_id, $room_id, $checkin_date, $checkout_date, $booking_id);
        $stmt->execute();
        $stmt->close();

        // Step 2: Get the current room availability
        $roomQuery = "SELECT availability FROM rooms WHERE room_id = ?";
        $roomStmt = $conn->prepare($roomQuery);
        $roomStmt->bind_param('i', $room_id);
        $roomStmt->execute();
        $roomStmt->bind_result($room_availability);
        $roomStmt->fetch();
        $roomStmt->close();

        // Step 3: If the room is changed, update the availability status
        if ($room_availability != 0) { // Only update if room is available
            $updateRoomQuery = "UPDATE rooms SET availability = 0 WHERE room_id = ?";
            $updateRoomStmt = $conn->prepare($updateRoomQuery);
            $updateRoomStmt->bind_param('i', $room_id);
            $updateRoomStmt->execute();
            $updateRoomStmt->close();
        }

        // Step 4: Update the payment record (first payment based on the new check-in date)
        // Fetch the room price
        $roomPriceQuery = "SELECT price FROM rooms WHERE room_id = ?";
        $roomPriceStmt = $conn->prepare($roomPriceQuery);
        $roomPriceStmt->bind_param('i', $room_id);
        $roomPriceStmt->execute();
        $roomPriceStmt->bind_result($room_price);
        $roomPriceStmt->fetch();
        $roomPriceStmt->close();

        // Update the payment record (assuming you want to change the payment record for the new check-in date)
        $paymentQuery = "UPDATE payments
                         SET amount_due = ?, payment_date = ?
                         WHERE booking_id = ? AND tenant_id = ?";
        $payment_date = date('Y-m-d', strtotime($checkin_date)); // Set payment date to the check-in date
        $paymentStmt = $conn->prepare($paymentQuery);
        $paymentStmt->bind_param('dsii', $room_price, $payment_date, $booking_id, $tenant_id);
        $paymentStmt->execute();
        $paymentStmt->close();

        // Commit the transaction
        $conn->commit();

        $_SESSION['status'] = 'Booking updated successfully!';
        $_SESSION['status_icon'] = 'success';
        header("Location: ../room_assignment.php"); // Redirect to the room assignment page
        exit;
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        $_SESSION['status'] = 'Error: ' . $e->getMessage();
        $_SESSION['status_icon'] = 'error';
        header("Location: ../room_assignment.php"); // Redirect to the room assignment page
        exit;
    }
}
?>
