<?php
include('connection.php');

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Payment ID required']);
    exit;
}

$payment_id = intval($_GET['id']);

$sql = "SELECT p.*, b.booking_id, t.name, t.tenant_id, r.room_number
        FROM payments p
        JOIN bookings b ON p.booking_id = b.booking_id
        JOIN tenants t ON b.tenant_id = t.tenant_id
        JOIN rooms r ON b.room_id = r.room_id
        WHERE p.payment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Payment not found']);
    exit;
}

$payment = $result->fetch_assoc();

// Calculate balance
$balance_sql = "SELECT b.total_amount, 
               (SELECT COALESCE(SUM(amount_paid), 0) FROM payments WHERE booking_id = b.booking_id) as paid_amount
               FROM bookings b WHERE b.booking_id = ?";
$balance_stmt = $conn->prepare($balance_sql);
$balance_stmt->bind_param("i", $payment['booking_id']);
$balance_stmt->execute();
$balance_result = $balance_stmt->get_result();
$balance_data = $balance_result->fetch_assoc();

$payment['balance'] = $balance_data['total_amount'] - $balance_data['paid_amount'];

echo json_encode(['success' => true, ...$payment]);

$stmt->close();
$balance_stmt->close();
$conn->close();
?>