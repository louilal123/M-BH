<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = $_POST['adminId'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $role = $_POST['role'];
    $photo = $_FILES['photo']['name'] ?? null;

    if ($photo) {
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($photo);
        move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);

        $sql = "UPDATE admin SET email=?, username=?, fullname=?, role=?, photo=? WHERE admin_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $email, $username, $fullname, $role, $photo, $adminId);
    } else {
        $sql = "UPDATE admin SET email=?, username=?, fullname=?, role=? WHERE admin_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $email, $username, $fullname, $role, $adminId);
    }

    if ($stmt->execute()) {
        $_SESSION['status'] = "Admin updated successfully!";
        $_SESSION['status_icon'] = "success";
        header("Location: ../users.php");
    exit();
    } else {
        $_SESSION['status'] = "Failed to update admin!";
        $_SESSION['status_icon'] = "error";
        header("Location: ../users.php");
    exit();
    }

    $stmt->close();
    $conn->close();
    
}
?>
