<?php
include 'connection.php';

header('Content-Type: application/json');

if (!isset($_POST['room_id']) || empty($_FILES['file'])) {
    echo json_encode(['error' => 'Invalid request']);
    exit();
}

$room_id = (int)$_POST['room_id'];
$allowed = ['image/jpeg', 'image/png', 'image/gif'];

try {
    $file = $_FILES['file'];
    
    // Validate file
    if (!in_array($file['type'], $allowed)) {
        throw new Exception('Only JPG, PNG, and GIF images are allowed');
    }
    
    if ($file['size'] > 5000000) { // 5MB
        throw new Exception('Image size must be less than 5MB');
    }
    
    // Generate unique filename
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . uniqid() . '.' . $ext;
    $destination = '../uploads/rooms/' . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception('Failed to upload image');
    }
    
    // Save to database
    $stmt = $conn->prepare("INSERT INTO room_images (room_id, image_path) VALUES (?, ?)");
    $stmt->bind_param("is", $room_id, $filename);
    $stmt->execute();
    
    echo json_encode([
        'success' => true,
        'image_id' => $conn->insert_id,
        'image_path' => $filename
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>