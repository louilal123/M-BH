<?php
session_start();
include('connection.php');

if (isset($_POST['tenant_id'])) {
    $tenant_id = $_POST['tenant_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE tenants SET status = ? WHERE tenant_id = ?");
    $stmt->bind_param("si", $status, $tenant_id);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Tenant account status updated successfully.";
        $_SESSION['status_icon'] = "success";
    } else {
        $_SESSION['status'] = "Failed to update tenant account status.";
        $_SESSION['status_icon'] = "error";
    }

    $stmt->close();
    header("Location: ../tenants.php");
    exit();
}

$conn->close();
?>