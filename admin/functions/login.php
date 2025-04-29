<?php
session_start();
include 'connection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute SQL query
    $sql = "SELECT * FROM admin WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['password'])) {
            // Successful login
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['photo'] = $row['photo'];

            $_SESSION['status'] = "Login Succesfully";
            $_SESSION['status_icon'] = "success";
            header("Location: ../dashboard.php");
            exit;
        } else {
            // Invalid password
            $_SESSION['status'] = "Invalid Username or Email!";
            $_SESSION['status_icon'] = "error";
            header("Location: ../index.php");
            exit;
        }
    } else {
        // Invalid username/email
        $_SESSION['status'] = "Invalid Username or Email!";
        $_SESSION['status_icon'] = "error";
        header("Location: ../index.php");
        exit;
    }
}
?>
