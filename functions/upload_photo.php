<?php
// upload_photo.php - Located at C:\xampp\htdocs\M-BH\functions\upload_photo.php
session_start();
include "../admin/functions/connection.php";

// Set content type to JSON first thing
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(401);
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
}

// Check if this is a POST request with a file
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_FILES['photo'])) {
    http_response_code(400);
    die(json_encode(['status' => 'error', 'message' => 'Invalid request']));
}

// Set upload directory relative to root
$target_dir = __DIR__ . "/../uploads/tenants/";
if (!file_exists($target_dir)) {
    if (!mkdir($target_dir, 0777, true)) {
        die(json_encode(['status' => 'error', 'message' => 'Failed to create upload directory']));
    }
}

// Generate unique filename
$filename = time() . '_' . basename($_FILES["photo"]["name"]);
$target_file = $target_dir . $filename;
$relative_path = "uploads/tenants/" . $filename; // This is what will be stored in DB

// Validate image file
$check = getimagesize($_FILES["photo"]["tmp_name"]);
if ($check === false) {
    die(json_encode(['status' => 'error', 'message' => 'File is not an image']));
}

// Check file size (5MB max)
if ($_FILES["photo"]["size"] > 5000000) {
    die(json_encode(['status' => 'error', 'message' => 'File is too large (max 5MB)']));
}

// Allow only certain file formats
$imageFileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
    die(json_encode(['status' => 'error', 'message' => 'Only JPG, JPEG, PNG & GIF files are allowed']));
}

// Try to upload file
if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
    // Update database with just the filename (not full path)
    $update_stmt = $conn->prepare("UPDATE tenants SET photo = ? WHERE tenant_id = ?");
    $update_stmt->bind_param("si", $relative_path, $_SESSION['tenant_id']);
    
    if ($update_stmt->execute()) {
        // Update session
        $_SESSION['photo'] = $relative_path;
        echo json_encode([
            'status' => 'success', 
            'message' => 'Profile photo updated successfully',
            'photo_url' => $relative_path
        ]);
    } else {
        // Delete the uploaded file if DB update failed
        @unlink($target_file);
        echo json_encode([
            'status' => 'error', 
            'message' => 'Database error: ' . $conn->error
        ]);
    }
    $update_stmt->close();
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Sorry, there was an error uploading your file'
    ]);
}
?>