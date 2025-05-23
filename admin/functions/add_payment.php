<?php
session_start();
include('connection.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate required fields
$required = ['booking_id', 'amount', 'payment_method', 'payment_date'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
        exit;
    }
}

// Get booking details to validate payment
$booking_id = intval($_POST['booking_id']);
$sql = "SELECT b.total_amount, 
        (SELECT COALESCE(SUM(amount_paid), 0) FROM payments WHERE booking_id = b.booking_id) as paid_amount
        FROM bookings b WHERE b.booking_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Booking not found']);
    exit;
}

$booking = $result->fetch_assoc();
$balance = $booking['total_amount'] - $booking['paid_amount'];
$amount = floatval($_POST['amount']);

// Validate payment amount doesn't exceed balance
if ($amount > $balance) {
    echo json_encode(['success' => false, 'message' => 'Payment amount cannot exceed remaining balance of ₱' . number_format($balance, 2)]);
    exit;
}

// Handle file upload
$proof_photo = null;
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

// Insert payment
$sql = "INSERT INTO payments (booking_id, amount_paid, payment_method, reference_number, payment_date, proof_photo, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("idsssss", $booking_id, $amount, $payment_method, $reference_number, $payment_date, $proof_photo, $remarks);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Payment recorded successfully']);
} else {
    // Delete uploaded file if insert failed
    if ($proof_photo && file_exists($upload_dir . $proof_photo)) {
        unlink($upload_dir . $proof_photo);
    }
    echo json_encode(['success' => false, 'message' => 'Failed to record payment: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>