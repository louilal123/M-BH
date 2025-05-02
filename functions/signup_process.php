<?php
// functions/signup_process.php
session_start();
include "../admin/functions/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $firstName = htmlspecialchars(trim($_POST['first_name']));
    $lastName = htmlspecialchars(trim($_POST['last_name']));
    $name = $firstName . ' ' . $lastName;
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $occupation = htmlspecialchars(trim($_POST['occupation']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $address = isset($_POST['address']) ? htmlspecialchars(trim($_POST['address'])) : '';
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid email format.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../index.php");
        exit();
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $_SESSION['status'] = "Passwords do not match.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../index.php");
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
        header("Location: ../index.php");
        exit();
    }
    $emailCheckStmt->close();
// Handle Photo Upload (optional)
$photo = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photoTmpPath = $_FILES['photo']['tmp_name'];
    $photoName = time() . '_' . basename($_FILES['photo']['name']);
    $uploadDir = 'uploads/tenants/';
    $uploadPath = $uploadDir . $photoName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($photoTmpPath, $uploadPath)) {
        $photo = $photoName; // ✅ Save only the filename
    } else {
        $_SESSION['status'] = "Failed to upload photo.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../index.php");
        exit();
    }
}



    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO tenants (name, email, password, phone, address, occupation, photo, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssssss", $name, $email, $hashedPassword, $phone,  $address, $occupation, $photo);

    if ($stmt->execute()) {
        // Get the newly created user
        // $result = $conn->query("SELECT * FROM tenants WHERE email = '$email'");
        // $user = $result->fetch_assoc();
        
        // Store data in session variables
        // $_SESSION['loggedin'] = true;
        // $_SESSION['tenant_id'] = $user['tenant_id'];
        // $_SESSION['name'] = $user['name'];
        // $_SESSION['email'] = $user['email'];
        // $_SESSION['occupation'] = $user['occupation'];
        
        $_SESSION['status'] = "Registration successful. You may now signup with your account!";
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