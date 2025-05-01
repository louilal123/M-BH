<?php
session_start();
include "../admin/functions/connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM tenants WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            // Login successful
            $_SESSION["loggedin"] = true;
            $_SESSION["tenant_id"] = $user["tenant_id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["occupation"] = $user["occupation"];
            $_SESSION["status"] = "Welcome back!";
            $_SESSION["photo"] = $user["photo"] ?? 'default.jpg'; // Default photo if not set
            $_SESSION["status_icon"] = "success";
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION["status"] = "Incorrect password.";
            $_SESSION["status_icon"] = "error";
        }
    } else {
        $_SESSION["status"] = "Credentials doesnt macth any account.";
        $_SESSION["status_icon"] = "error";
    }

    // Show login modal again
    $_SESSION["show_login_modal"] = true;
    header("Location: ../index.php");
    exit();
}
?>
