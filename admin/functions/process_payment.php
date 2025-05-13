<?php
session_start();
include('connection.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    $required = ['booking_id', 'amount_paid', 'payment_method'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
            exit;
        }
    }

    // Sanitize inputs
    $booking_id = intval($_POST['booking_id']);
    $amount_paid = floatval($_POST['amount_paid']);
    $payment_method = $conn->real_escape_string(trim($_POST['payment_method']));
    $reference_number = isset($_POST['reference_number']) ? $conn->real_escape_string(trim($_POST['reference_number'])) : '';
    $remarks = isset($_POST['remarks']) ? $conn->real_escape_string(trim($_POST['remarks'])) : '';

    // Validate amount
    if ($amount_paid <= 0) {
        echo json_encode(['success' => false, 'message' => 'Amount must be greater than 0']);
        exit;
    }

    // Handle file upload
    $proof_photo = '';
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

    // Process payment
    $payment_date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO payments (booking_id, amount_paid, payment_method, reference_number, payment_date, proof_photo, remarks) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idsssss", $booking_id, $amount_paid, $payment_method, $reference_number, $payment_date, $proof_photo, $remarks);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Payment processed successfully']);
    } else {
        // Delete uploaded file if database insert failed
        if (!empty($proof_photo) && file_exists($upload_dir . $proof_photo)) {
            unlink($upload_dir . $proof_photo);
        }
        echo json_encode(['success' => false, 'message' => 'Failed to process payment: ' . $conn->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>