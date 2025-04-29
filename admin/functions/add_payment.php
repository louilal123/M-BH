<?php
session_start();
include('connection.php');

// Validate input
function validate_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Process Add Payment
if (isset($_POST['tenant']) && isset($_POST['amount_due']) && isset($_POST['amount_paid']) && isset($_POST['payment_method']) && isset($_POST['payment_status'])) {
    $tenant_id = validate_input($_POST['tenant']);
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

    $payment_date = date("Y-m-d");

    // Insert into database
    $query = "INSERT INTO payments (tenant_id, amount_due, amount_paid, payment_date, payment_method, payment_status) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iissss', $tenant_id, $amount_due, $amount_paid, $payment_date, $payment_method, $payment_status);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Payment added successfully!";
        $_SESSION['status_icon'] = "success";
        header("Location: ../payments.php");
    } else {
        $_SESSION['status'] = "Failed to add payment.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../payments.php");
    }

    $stmt->close();
    $conn->close();
}
