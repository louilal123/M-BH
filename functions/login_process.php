<?php
session_start();
include "../admin/functions/connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    
    // Get client info for history
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $stmt = $conn->prepare("SELECT * FROM tenants WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            // Login successful - record success
            $log_stmt = $conn->prepare("INSERT INTO login_history (tenant_id, ip_address, user_agent, login_status) VALUES (?, ?, ?, 'success')");
            $log_stmt->bind_param("iss", $user['tenant_id'], $ip_address, $user_agent);
            $log_stmt->execute();
            
            $_SESSION["loggedin"] = true;
            $_SESSION["tenant_id"] = $user["tenant_id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["occupation"] = $user["occupation"];
            $_SESSION["status1"] = "Welcome back!";
            $_SESSION["photo"] = $user["photo"];
            $_SESSION["status_icon1"] = "success";
            header("Location: ../index.php");
            exit();
        } else {
            // Record failed password attempt
            $log_stmt = $conn->prepare("INSERT INTO login_history (tenant_id, ip_address, user_agent, login_status) VALUES (?, ?, ?, 'failed_password')");
            $log_stmt->bind_param("iss", $user['tenant_id'], $ip_address, $user_agent);
            $log_stmt->execute();
            
            $_SESSION["status1"] = "Incorrect password.";
            $_SESSION["status_icon1"] = "error";
        }
    } else {
        $log_stmt = $conn->prepare("INSERT INTO login_history (tenant_id, ip_address, user_agent, login_status) VALUES (NULL, ?, ?, 'failed_email')");
        $log_stmt->bind_param("ss", $ip_address, $user_agent);
        $log_stmt->execute();
        
        $_SESSION["status1"] = "Credentials doesn't match any account.";
        $_SESSION["status_icon1"] = "error";
    }

    // Show login modal again
    $_SESSION["show_login_modal"] = true;
    header("Location: ../index.php");
    exit();
}
?>