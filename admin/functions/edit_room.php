<?php
session_start();
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_room'])) {
    $room_id = intval($_POST['room_id']);
    
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

    // Set upload directory
    $upload_dir = '../../uploads/rooms/';
    
    if (!empty($errors)) {
        $_SESSION['status_icon'] = 'error';
        $_SESSION['status'] = implode("<br>", $errors);
        header("Location: ../rooms.php");
        exit();
    }

    $conn->begin_transaction();
    
    try {
        // Handle main photo update
        $photo_path = $_POST['current_photo'];
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photo = $_FILES['photo'];
            
            // Validate image
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            if (!in_array($photo['type'], $allowed_types)) {
                throw new Exception("Main photo must be a JPG, PNG, or GIF");
            } elseif ($photo['size'] > $max_size) {
                throw new Exception("Main photo must be less than 5MB");
            } else {
                // Generate unique filename
                $ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
                $photo_filename = 'room_' . time() . '_main.' . $ext;
                $photo_destination = $upload_dir . $photo_filename;
                
                if (move_uploaded_file($photo['tmp_name'], $photo_destination)) {
                    // Delete old photo if exists
                    if (!empty($_POST['current_photo']) && file_exists($upload_dir . $_POST['current_photo'])) {
                        @unlink($upload_dir . $_POST['current_photo']);
                    }
                    $photo_path = $photo_filename;
                } else {
                    throw new Exception("Failed to upload main photo");
                }
            }
        }
        
        // Update room details
        $stmt = $conn->prepare("UPDATE rooms SET 
            room_number = ?, 
            photo = ?, 
            description = ?, 
            price = ?, 
            availability = ?, 
            room_type = ? 
            WHERE room_id = ?");
        $stmt->bind_param("sssdssi", $room_number, $photo_path, $description, $price, $availability, $room_type, $room_id);
        $stmt->execute();
        $stmt->close();
        
        // Handle image deletions
        if (!empty($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $image_id) {
                $image_id = intval($image_id);
                // Get image path first
                $stmt = $conn->prepare("SELECT image_path FROM room_images WHERE image_id = ?");
                $stmt->bind_param("i", $image_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $image = $result->fetch_assoc();
                $stmt->close();
                
                if ($image) {
                    // Delete from database
                    $stmt = $conn->prepare("DELETE FROM room_images WHERE image_id = ?");
                    $stmt->bind_param("i", $image_id);
                    $stmt->execute();
                    $stmt->close();
                    
                    // Delete file
                    if (file_exists($upload_dir . $image['image_path'])) {
                        @unlink($upload_dir . $image['image_path']);
                    }
                }
            }
        }
        
        // Handle new image uploads
        if (!empty($_FILES['additional_images']['name'][0])) {
            $additional_images = reArrayFiles($_FILES['additional_images']);
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            foreach ($additional_images as $image) {
                if ($image['error'] === UPLOAD_ERR_OK) {
                    // Validate image
                    if (in_array($image['type'], $allowed_types) && $image['size'] <= $max_size) {
                        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                        $image_filename = 'room_' . $room_id . '_' . time() . '_' . uniqid() . '.' . $ext;
                        $image_destination = $upload_dir . $image_filename;
                        
                        if (move_uploaded_file($image['tmp_name'], $image_destination)) {
                            $img_stmt = $conn->prepare("INSERT INTO room_images (room_id, image_path) VALUES (?, ?)");
                            $img_stmt->bind_param("is", $room_id, $image_filename);
                            $img_stmt->execute();
                            $img_stmt->close();
                        } else {
                            throw new Exception("Failed to upload additional image: " . $image['name']);
                        }
                    } else {
                        throw new Exception("Invalid image type or size for: " . $image['name']);
                    }
                }
            }
        }
        
        $conn->commit();
        
        $_SESSION['status_icon'] = 'success';
        $_SESSION['status'] = "✅ Room updated successfully!";
        header("Location: ../rooms.php");
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['status_icon'] = 'error';
        $_SESSION['status'] = "❌ Error updating room: " . $e->getMessage();
        header("Location: ../rooms.php");
        exit();
    }
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