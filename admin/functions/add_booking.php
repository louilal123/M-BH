<?php
session_start(); // Start the session to store and display messages
include('connection.php'); // Include your database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $tenant_id = $_POST['tenant'];
    $room_id = $_POST['room'];
    $checkin_date = $_POST['checkin'];

    // Calculate check-out date (30 days after check-in date)
    $checkout_date = date('Y-m-d', strtotime($checkin_date . ' + 30 days'));

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Step 1: Insert the new booking record
        $bookingQuery = "INSERT INTO bookings (tenant_id, room_id, check_in_date, check_out_date)
                         VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($bookingQuery);
        $stmt->bind_param('iiss', $tenant_id, $room_id, $checkin_date, $checkout_date);
        $stmt->execute();

        // Step 2: Get the ID of the new booking (last inserted record)
        $booking_id = $stmt->insert_id;
        $stmt->close();

        // Step 3: Calculate the monthly payment (room price * 1 for the first month)
        // Fetch room price
        $roomQuery = "SELECT price FROM rooms WHERE room_id = ?";
        $roomStmt = $conn->prepare($roomQuery);
        $roomStmt->bind_param('i', $room_id);
        $roomStmt->execute();
        $roomStmt->bind_result($room_price);
        $roomStmt->fetch();
        $roomStmt->close(); // Properly close the statement

        // Step 4: Insert payment record for the first month
        $paymentQuery = "INSERT INTO payments (booking_id, tenant_id, amount_due, amount_paid, payment_date, payment_status)
                         VALUES (?, ?, ?, 0, ?, 'pending')";
        $payment_date = date('Y-m-d', strtotime($checkin_date)); // Set payment date to check-in date
        $paymentStmt = $conn->prepare($paymentQuery);
        $paymentStmt->bind_param('iiis', $booking_id, $tenant_id, $room_price, $payment_date);
        $paymentStmt->execute();
        $paymentStmt->close();

        // Step 5: Update room availability (set to 0 for booked room)
        $updateRoomQuery = "UPDATE rooms SET availability = 0 WHERE room_id = ?";
        $updateRoomStmt = $conn->prepare($updateRoomQuery);
        $updateRoomStmt->bind_param('i', $room_id);
        $updateRoomStmt->execute();
        $updateRoomStmt->close();

        // Commit transaction
        $conn->commit();

        $_SESSION['status'] = 'Tenant Assigned successfully!';
        $_SESSION['status_icon'] = 'success';
        header("Location: ../room_assignment.php"); // Change the location as needed
        exit;
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        $_SESSION['status'] = 'Error: ' . $e->getMessage();
        $_SESSION['status_icon'] = 'error';
        header("Location: ../room_assignment.php"); // Change the location as needed
        exit;
    }
}
?>
