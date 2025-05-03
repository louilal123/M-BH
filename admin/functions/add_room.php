<?php
session_start();
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) {
    // Validate inputs
    $errors = [];
    
    $room_number = trim($_POST['room_number']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $room_type = trim($_POST['room_type']);
    $availability = intval($_POST['availability']);
    
    // Basic validation
    if (empty($room_number)) {
        $errors[] = "❌ Room number is required";
    }
    
    if (empty($description)) {
        $errors[] = "❌ Description is required";
    }
    
    if (!is_numeric($price) || $price <= 0) {
        $errors[] = "❌ Valid price is required";
    }
    
    if (empty($room_type)) {
        $errors[] = "❌ Room type is required";
    }
    
    // Check if room number already exists
    $check_stmt = $conn->prepare("SELECT room_id FROM rooms WHERE room_number = ?");
    $check_stmt->bind_param("s", $room_number);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    if ($check_stmt->num_rows > 0) {
        $errors[] = "❌ Room number already exists";
    }
    $check_stmt->close();
    
    // Handle file uploads
    $photo_path = '';
    $upload_errors = [];
    
// Set upload directory
$upload_dir = '../../uploads/rooms/';

// Main photo upload
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photo = $_FILES['photo'];

    // Validate image
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (!in_array($photo['type'], $allowed_types)) {
        $upload_errors[] = "❌ Main photo must be a JPG, PNG, or GIF";
    } elseif ($photo['size'] > $max_size) {
        $upload_errors[] = "❌ Main photo must be less than 5MB";
    } else {
        // Generate unique filename
        $ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
        $photo_filename = 'room_' . time() . '_main.' . $ext;
        $photo_destination = $upload_dir . $photo_filename;

        // Check if directory is writable
        if (!is_writable($upload_dir)) {
            $upload_errors[] = "❌ Upload directory is not writable";
        } elseif (move_uploaded_file($photo['tmp_name'], $photo_destination)) {
            $photo_path = $photo_filename;
        } else {
            $upload_errors[] = "❌ Failed to upload main photo. Error: " . error_get_last()['message'];
        }
    }
} else {
    $upload_errors[] = "❌ Main photo is required. Error code: " . $_FILES['photo']['error'];
}

    
    // If no errors, proceed with database insertion
    if (empty($errors) && empty($upload_errors)) {
        $conn->begin_transaction();
        
        try {
            // Insert room data
            $stmt = $conn->prepare("INSERT INTO rooms (room_number, photo, description, price, availability, room_type) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssdss", $room_number, $photo_path, $description, $price, $availability, $room_type);
            $stmt->execute();
            $room_id = $stmt->insert_id;
            $stmt->close();
            
            // Handle additional images
            if (!empty($_FILES['additional_images']['name'][0])) {
                $additional_images = reArrayFiles($_FILES['additional_images']);
                
                foreach ($additional_images as $image) {
                    if ($image['error'] === UPLOAD_ERR_OK) {
                        // Validate image
                        if (in_array($image['type'], $allowed_types) && $image['size'] <= $max_size) {
                            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                            $image_filename = 'room_' . $room_id . '_' . time() . '_' . uniqid() . '.' . $ext;
                            $image_destination = '../../uploads/rooms/' . $image_filename;
                            
                            if (move_uploaded_file($image['tmp_name'], $image_destination)) {
                                $img_stmt = $conn->prepare("INSERT INTO room_images (room_id, image_path) VALUES (?, ?)");
                                $img_stmt->bind_param("is", $room_id, $image_filename);
                                $img_stmt->execute();
                                $img_stmt->close();
                            }
                        }
                    }
                }
            }
            
            $conn->commit();
            
            $_SESSION['status_icon'] = 'success';
            $_SESSION['status'] = "✅ Room added successfully!";
            header("Location: ../rooms.php");
            exit();
            
        } catch (Exception $e) {
            $conn->rollback();
            
            // Delete any uploaded files if transaction failed
            if (!empty($photo_path)) {
                @unlink('.../../uploads/rooms/' . $photo_path);
            }
            
            $_SESSION['status_icon'] = 'error';
            $_SESSION['status'] = "❌ Error adding room: " . $e->getMessage();
            header("Location: ../rooms.php");
            exit();
        }
    } else {
        // Combine all errors
        $all_errors = array_merge($errors, $upload_errors);
        $_SESSION['status_icon'] = 'error';
        $_SESSION['status'] = implode("<br>", $all_errors);
        header("Location: ../rooms.php");
        exit();
    }
} else {
    $_SESSION['status_icon'] = 'error';
    $_SESSION['status'] = "❌ Invalid request";
    header("Location: ../rooms.php");
    exit();
}

// Helper function to reorganize multiple files array
function reArrayFiles($file_post) {
    $file_array = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);
    
    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_array[$i][$key] = $file_post[$key][$i];
        }
    }
    
    return $file_array;
}
?>