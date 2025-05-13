<?php
header('Content-Type: application/json');
require_once '../admin/functions/connection.php';

// For security, you might want to add authentication here
$query = "SELECT tenant_id, name, email, phone, occupation, status FROM tenants";
$result = $conn->query($query);

$tenants = [];
while ($row = $result->fetch_assoc()) {
    $tenants[] = $row;
}

echo json_encode($tenants);
$conn->close();
?>