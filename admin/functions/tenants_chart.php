<?php
include 'connection.php';

$currentMonth = date('m');
$currentYear = date('Y');

$sql = "SELECT COUNT(*) AS total FROM tenants 
        WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $currentMonth, $currentYear);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$totalTenants = $result['total'] ?? 0;

echo json_encode(['month' => date('F'), 'total' => $totalTenants]);
?>
