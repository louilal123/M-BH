<?php
session_start();
include "../admin/functions/connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tenant_id = $_SESSION['tenant_id'];
    $name = htmlspecialchars(trim($_POST['name']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $occupation = htmlspecialchars(trim($_POST['occupation']));
    $address = htmlspecialchars(trim($_POST['address']));

    $stmt = $conn->prepare("UPDATE tenants SET name = ?, phone = ?, occupation = ?, address = ? WHERE tenant_id = ?");
    $stmt->bind_param("ssssi", $name, $phone, $occupation, $address, $tenant_id);

    if ($stmt->execute()) {
        // Update session variables
        $_SESSION['name'] = $name;
        $_SESSION['occupation'] = $occupation;
        
        $_SESSION['status'] = "Profile updated successfully!";
        $_SESSION['status_icon'] = "success";
    } else {
        $_SESSION['status'] = "Error updating profile. Please try again.";
        $_SESSION['status_icon'] = "error";
    }

    $stmt->close();
    $conn->close();
    
    header("Location: ../profile.php");
    exit();
}
?>