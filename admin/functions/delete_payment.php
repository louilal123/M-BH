<?php
session_start();
include('connection.php');

// Check if payment ID is passed
if (isset($_GET['id'])) {
    $payment_id = intval($_GET['id']); // Sanitize input

    // Prepare delete query
    $query = "DELETE FROM payments WHERE payment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $payment_id);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Payment deleted successfully!";
        $_SESSION['status_icon'] = "success";
    } else {
        $_SESSION['status'] = "Failed to delete payment. Error: " . $stmt->error;
        $_SESSION['status_icon'] = "error";
    }

    $stmt->close();
    $conn->close();
    header("Location: ../payments.php");
    exit;
}
