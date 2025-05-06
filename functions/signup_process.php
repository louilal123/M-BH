<?php
session_start();
include "../admin/functions/connection.php";

// Set JSON header
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];

    $firstName = htmlspecialchars(trim($_POST['first_name']), ENT_QUOTES, 'UTF-8');
    $lastName = htmlspecialchars(trim($_POST['last_name']), ENT_QUOTES, 'UTF-8');
    $name = $firstName . ' ' . $lastName;
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $occupation = htmlspecialchars(trim($_POST['occupation']), ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars(trim($_POST['phone']), ENT_QUOTES, 'UTF-8');
    $address = isset($_POST['address']) ? htmlspecialchars(trim($_POST['address']), ENT_QUOTES, 'UTF-8') : '';

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Invalid email format.";
        echo json_encode($response);
        exit();
    }

    // Check password match
    if ($password !== $confirmPassword) {
        $response['message'] = "Passwords do not match.";
        echo json_encode($response);
        exit();
    }

    // Check if email exists
    $emailCheckStmt = $conn->prepare("SELECT email FROM tenants WHERE email = ?");
    $emailCheckStmt->bind_param("s", $email);
    $emailCheckStmt->execute();
    $emailCheckStmt->store_result();

    if ($emailCheckStmt->num_rows > 0) {
        $response['message'] = "This email is already registered.";
        $emailCheckStmt->close();
        echo json_encode($response);
        exit();
    }
    $emailCheckStmt->close();

    // Handle photo upload
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoTmpPath = $_FILES['photo']['tmp_name'];
        $photoName = time() . '_' . basename($_FILES['photo']['name']);
        $uploadDir = '../uploads/tenants/';
        $uploadPath = $uploadDir . $photoName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Validate image file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($photoTmpPath);
        
        if (!in_array($fileType, $allowedTypes)) {
            $response['message'] = "Only JPG, PNG, and GIF images are allowed.";
            echo json_encode($response);
            exit();
        }

        if (move_uploaded_file($photoTmpPath, $uploadPath)) {
            $photo = $photoName;
        } else {
            $response['message'] = "Failed to upload photo.";
            echo json_encode($response);
            exit();
        }
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert new tenant
    $stmt = $conn->prepare("INSERT INTO tenants (name, email, password, phone, address, occupation, photo, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssssss", $name, $email, $hashedPassword, $phone, $address, $occupation, $photo);

    if ($stmt->execute()) {
        // Set session variables for auto-login if desired
        $_SESSION['loggedin'] = true;
        $_SESSION['tenant_id'] = $stmt->insert_id;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['occupation'] = $occupation;
        $_SESSION['photo'] = $photo;
        
        $response['success'] = true;
        $response['message'] = "Registration successful!";
        $response['redirect'] = false; // No redirect needed
        
        // If you want to automatically login after signup
        // $response['redirect'] = "room_details.php?room_id=" . $_POST['room_id'];
    } else {
        $response['message'] = "Something went wrong. Please try again.";
    }

    $stmt->close();
    $conn->close();
    
    echo json_encode($response);
    exit();
}

// If not POST request
echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
?>