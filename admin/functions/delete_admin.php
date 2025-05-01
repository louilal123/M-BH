<?php
session_start();
include('connection.php');

if (isset($_GET['id'])) {
    $adminId = intval($_GET['id']); // sanitize input

    $sql = "DELETE FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $adminId);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Admin deleted successfully!";
        $_SESSION['status_icon'] = "success";
    } else {
        $_SESSION['status'] = "Admin deletion failed!";
        $_SESSION['status_icon'] = "error";
    }

    $stmt->close();
    $conn->close();
    header("Location: ../users.php");
    exit();
} else {
    $_SESSION['status'] = "No admin ID provided!";
    $_SESSION['status_icon'] = "warning";
    header("Location: ../users.php");
    exit();
}
