<?php
session_start();
include "../admin/functions/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = htmlspecialchars(trim($_POST['first_name']), ENT_QUOTES, 'UTF-8');
    $lastName = htmlspecialchars(trim($_POST['last_name']), ENT_QUOTES, 'UTF-8');
    $name = $firstName . ' ' . $lastName;
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $occupation = htmlspecialchars(trim($_POST['occupation']), ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars(trim($_POST['phone']), ENT_QUOTES, 'UTF-8');
    $address = isset($_POST['address']) ? htmlspecialchars(trim($_POST['address']), ENT_QUOTES, 'UTF-8') : '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid email format.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../index.php");
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['status'] = "Passwords do not match.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../index.php");
        exit();
    }

    $emailCheckStmt = $conn->prepare("SELECT email FROM tenants WHERE email = ?");
    $emailCheckStmt->bind_param("s", $email);
    $emailCheckStmt->execute();
    $emailCheckStmt->store_result();

    if ($emailCheckStmt->num_rows > 0) {
        $_SESSION['status'] = "This email is already registered.";
        $_SESSION['status_icon'] = "error";
        $emailCheckStmt->close();
        header("Location: ../index.php");
        exit();
    }
    $emailCheckStmt->close();

    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoTmpPath = $_FILES['photo']['tmp_name'];
        $photoName = time() . '_' . basename($_FILES['photo']['name']);
        $uploadDir = '../uploads/tenants/';
        $uploadPath = $uploadDir . $photoName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($photoTmpPath, $uploadPath)) {
            $photo = $photoName;
        } else {
            $_SESSION['status'] = "Failed to upload photo.";
            $_SESSION['status_icon'] = "error";
            header("Location: ../index.php");
            exit();
        }
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO tenants (name, email, password, phone, address, occupation, photo, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssssss", $name, $email, $hashedPassword, $phone, $address, $occupation, $photo);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Registration successful. You may now sign up with your account!";
        $_SESSION['status_icon'] = "success";
        $_SESSION['show_login_modal'] = true;
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['status'] = "Something went wrong. Please try again.";
        $_SESSION['status_icon'] = "error";
        $_SESSION['show_login_modal'] = true;
        header("Location: ../index.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
