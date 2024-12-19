<?php
session_start();
include "connection.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "connection.php";

    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $contact = htmlspecialchars(trim($_POST['contact']));
    $address = htmlspecialchars(trim($_POST['address']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid email format.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../tenants.php");
        exit();
    }

    // Check for duplicate email
    $emailCheckStmt = $conn->prepare("SELECT email FROM tenants WHERE email = ?");
    $emailCheckStmt->bind_param("s", $email);
    $emailCheckStmt->execute();
    $emailCheckStmt->store_result();

    if ($emailCheckStmt->num_rows > 0) {
        $_SESSION['status'] = "This email is already registered.";
        $_SESSION['status_icon'] = "error";
        $emailCheckStmt->close();
        header("Location: ../tenants.php");
        exit();
    }
    $emailCheckStmt->close();

    // Handle Photo Upload
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoTmpPath = $_FILES['photo']['tmp_name'];
        $photoName = time() . '_' . basename($_FILES['photo']['name']);
        $uploadDir = '../uploads/tenants/';
        $uploadPath = $uploadDir . $photoName;

        // Ensure upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the file
        if (move_uploaded_file($photoTmpPath, $uploadPath)) {
            $photo = $photoName;
        } else {
            $_SESSION['status'] = "Failed to upload photo.";
            $_SESSION['status_icon'] = "error";
            header("Location: ../tenants.php");
            exit();
        }
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO tenants (name, email, password, contact, address, photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $hashedPassword, $contact, $address, $photo);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Tenant successfully added.";
        $_SESSION['status_icon'] = "success";
        header("Location: ../tenants.php");
        exit();
    } else {
        $_SESSION['status'] = "Something went wrong. Please try again.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../tenants.php");
        exit();
    }

    $stmt->close();
}

?>
