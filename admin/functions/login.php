<?php
session_start();
include 'connection.php'; // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // --- Login security logic ---
    $max_attempts = 3;
    $lockout_duration = 60; // seconds

    // Initialize if not set
    if (!isset($_SESSION['login_attempts'])) $_SESSION['login_attempts'] = 0;
    if (!isset($_SESSION['lockout_time'])) $_SESSION['lockout_time'] = null;

    // Check for lockout
    if ($_SESSION['login_attempts'] >= $max_attempts) {
        $elapsed = time() - $_SESSION['lockout_time'];
        if ($elapsed < $lockout_duration) {
            $_SESSION['status'] = "Too many failed attempts. Try again in " . (60 - $elapsed) . " seconds.";
            $_SESSION['status_icon'] = "warning";
            header("Location: ../index.php");
            exit;
        } else {
            // Reset after lockout period
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lockout_time'] = null;
        }
    }

    // Prepare and execute SQL query
    $sql = "SELECT * FROM admin WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check credentials
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            // Successful login - reset attempts
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lockout_time'] = null;

            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['photo'] = $row['photo'];

            $_SESSION['status'] = "Login Successfully";
            $_SESSION['status_icon'] = "success";
            header("Location: ../dashboard.php");
            exit;
        } else {
            // Wrong password
            $_SESSION['login_attempts'] += 1;
            if ($_SESSION['login_attempts'] >= $max_attempts) {
                $_SESSION['lockout_time'] = time();
            }
            $_SESSION['status'] = "Invalid Username or Password!";
            $_SESSION['status_icon'] = "error";
            header("Location: ../index.php");
            exit;
        }
    } else {
        // Invalid user
        $_SESSION['login_attempts'] += 1;
        if ($_SESSION['login_attempts'] >= $max_attempts) {
            $_SESSION['lockout_time'] = time();
        }
        $_SESSION['status'] = "Invalid Username or Password!";
        $_SESSION['status_icon'] = "error";
        header("Location: ../index.php");
        exit;
    }
}
?>
