<?php
session_start();
include('connection.php');

// Validate input
function validate_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Process Edit Payment
if (isset($_POST['payment_id'])) {
    $payment_id = validate_input($_POST['payment_id']);
    $amount_due = validate_input($_POST['amount_due']);
    $amount_paid = validate_input($_POST['amount_paid']);
    $payment_method = validate_input($_POST['payment_method']);
    $payment_status = validate_input($_POST['payment_status']);

    // Check if values are numeric
    if (!is_numeric($amount_due) || !is_numeric($amount_paid)) {
        $_SESSION['status'] = "Amount Due and Amount Paid must be numeric.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../payments.php");
        exit;
    }

    // Check if Amount Paid is greater than Amount Due
    if ($amount_paid > $amount_due) {
        $_SESSION['status'] = "Amount Paid cannot be greater than Amount Due.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../payments.php");
        exit;
    }

    // Check if the payment exists
    $payment_check_query = "SELECT payment_id FROM payments WHERE payment_id = ?";
    $payment_stmt = $conn->prepare($payment_check_query);
    $payment_stmt->bind_param('i', $payment_id);
    $payment_stmt->execute();
    $payment_result = $payment_stmt->get_result();

    if ($payment_result->num_rows == 0) {
        $_SESSION['status'] = "Payment record not found.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../payments.php");
        exit;
    }

    // Update payment in database
    $query = "UPDATE payments SET amount_due = ?, amount_paid = ?, payment_method = ?, payment_status = ? WHERE payment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iisss', $amount_due, $amount_paid, $payment_method, $payment_status, $payment_id);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Payment updated successfully!";
        $_SESSION['status_icon'] = "success";
        header("Location: ../payments.php");
    } else {
        $_SESSION['status'] = "Failed to update payment.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../payments.php");
    }

    $stmt->close();
    $conn->close();
}
