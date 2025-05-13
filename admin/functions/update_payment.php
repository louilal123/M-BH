<?php
session_start();
include('connection.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate required fields
$required = ['payment_id', 'booking_id', 'amount', 'payment_method', 'payment_date'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
        exit;
    }
}

$payment_id = intval($_POST['payment_id']);
$booking_id = intval($_POST['booking_id']);
$amount = floatval($_POST['amount']);

// Get current payment details
$current_sql = "SELECT proof_photo FROM payments WHERE payment_id = ?";
$current_stmt = $conn->prepare($current_sql);
$current_stmt->bind_param("i", $payment_id);
$current_stmt->execute();
$current_result = $current_stmt->get_result();

if ($current_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Payment not found']);
    exit;
}

$current_data = $current_result->fetch_assoc();
$current_proof = $current_data['proof_photo'];

// Get booking details to validate payment amount
$booking_sql = "SELECT b.total_amount, 
               (SELECT COALESCE(SUM(amount_paid), 0) FROM payments WHERE booking_id = b.booking_id AND payment_id != ?) as paid_amount
               FROM bookings b WHERE b.booking_id = ?";
$booking_stmt = $conn->prepare($booking_sql);
$booking_stmt->bind_param("ii", $payment_id, $booking_id);
$booking_stmt->execute();
$booking_result = $booking_stmt->get_result();

if ($booking_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Booking not found']);
    exit;
}

$booking = $booking_result->fetch_assoc();
$balance = $booking['total_amount'] - $booking['paid_amount'];

// Validate payment amount doesn't exceed balance
if ($amount > $balance) {
    echo json_encode(['success' => false, 'message' => 'Payment amount cannot exceed remaining balance of ₱' . number_format($balance, 2)]);
    exit;
}

// Handle file upload
$proof_photo = $current_proof;
if (isset($_FILES['proof_photo']) && $_FILES['proof_photo']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_type = $_FILES['proof_photo']['type'];
    
    if (!in_array($file_type, $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, and GIF files are allowed']);
        exit;
    }

    $upload_dir = '../assets/uploads/payments/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Delete old file if exists
    if ($current_proof && file_exists($upload_dir . $current_proof)) {
        unlink($upload_dir . $current_proof);
    }

    $file_ext = pathinfo($_FILES['proof_photo']['name'], PATHINFO_EXTENSION);
    $proof_photo = 'payment_' . time() . '.' . $file_ext;
    $target_file = $upload_dir . $proof_photo;

    if (!move_uploaded_file($_FILES['proof_photo']['tmp_name'], $target_file)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload payment proof']);
        exit;
    }
}

// Prepare data
$payment_method = $conn->real_escape_string(trim($_POST['payment_method']));
$reference_number = !empty($_POST['reference_number']) ? $conn->real_escape_string(trim($_POST['reference_number'])) : null;
$payment_date = $conn->real_escape_string(trim($_POST['payment_date']));
$remarks = !empty($_POST['remarks']) ? $conn->real_escape_string(trim($_POST['remarks'])) : null;

// Update payment
$sql = "UPDATE payments SET 
        amount_paid = ?,
        payment_method = ?,
        reference_number = ?,
        payment_date = ?,
        proof_photo = ?,
        remarks = ?
        WHERE payment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("dsssssi", $amount, $payment_method, $reference_number, $payment_date, $proof_photo, $remarks, $payment_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Payment updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update payment: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>