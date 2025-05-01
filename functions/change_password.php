<?php
session_start();
include "../admin/functions/connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tenant_id = $_SESSION['tenant_id'];
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Verify current password
    $stmt = $conn->prepare("SELECT password FROM tenants WHERE tenant_id = ?");
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!password_verify($current_password, $user['password'])) {
        $_SESSION['status'] = "Current password is incorrect.";
        $_SESSION['status_icon'] = "error";
        header("Location: profile.php");
        exit();
    }

    // Check if new passwords match
    if ($new_password !== $confirm_password) {
        $_SESSION['status'] = "New passwords do not match.";
        $_SESSION['status_icon'] = "error";
      
    header("Location: ../profile.php");
        exit();
    }

    // Validate password strength
    if (strlen($new_password) < 8 || !preg_match("#[0-9]+#", $new_password)) {
        $_SESSION['status'] = "Password must be at least 8 characters and contain at least one number.";
        $_SESSION['status_icon'] = "error";
       
    header("Location: ../profile.php");
        exit();
    }

    // Hash new password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update password
    $update_stmt = $conn->prepare("UPDATE tenants SET password = ? WHERE tenant_id = ?");
    $update_stmt->bind_param("si", $hashed_password, $tenant_id);

    if ($update_stmt->execute()) {
        $_SESSION['status'] = "Password changed successfully!";
        $_SESSION['status_icon'] = "success";
    } else {
        $_SESSION['status'] = "Error changing password. Please try again.";
        $_SESSION['status_icon'] = "error";
    }

    $update_stmt->close();
    $conn->close();
    
    header("Location: ../profile.php");
    exit();
}
?>