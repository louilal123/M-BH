<?php
include 'connection.php';
include '../includes/session.php';

if (isset($_POST['edit_room'])) {
    $room_id = $_POST['room_id'];
    $room_number = $_POST['room_number'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $room_type = $_POST['room_type'];
    $availability = $_POST['availability'];
    $current_photo = $_POST['current_photo'] ?? '';

    // Validation
    $errors = [];
    
    if (empty($room_number)) {
        $errors[] = 'Room number is required';
    }
    
    if (!is_numeric($price) || $price <= 0) {
        $errors[] = 'Price must be a positive number';
    }
    
    if (empty($room_type)) {
        $errors[] = 'Room type is required';
    }
    
    if (!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
        header('location: ../rooms.php');
        exit();
    }

    // Handle file upload
    $photo = $current_photo;
    if (!empty($_FILES['photo']['name'])) {
        $file_name = $_FILES['photo']['name'];
        $file_temp = $_FILES['photo']['tmp_name'];
        $file_size = $_FILES['photo']['size'];
        $file_type = $_FILES['photo']['type'];
        
        // Validate image
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file_type, $allowed)) {
            $_SESSION['error'] = 'Only JPG, PNG, and GIF images are allowed';
            header('location: ../rooms.php');
            exit();
        }
        
        if ($file_size > 5000000) { // 5MB
            $_SESSION['error'] = 'Image size must be less than 5MB';
            header('location: ../rooms.php');
            exit();
        }
        
        $photo = time() . '_' . $file_name;
        move_uploaded_file($file_temp, '../uploads/rooms/' . $photo);
        
        // Delete old photo if it exists and isn't the default
        if (!empty($current_photo) && $current_photo !== 'default-room.jpg') {
            unlink('../uploads/rooms/' . $current_photo);
        }
    }

    // Update database
    try {
        $conn->begin_transaction();
        
        // Update room data
        $stmt = $conn->prepare("UPDATE rooms SET room_number = ?, description = ?, price = ?, 
                               room_type = ?, availability = ?, photo = ? WHERE room_id = ?");
        $stmt->bind_param("ssdsssi", $room_number, $description, $price, $room_type, $availability, $photo, $room_id);
        $stmt->execute();
        
        // Handle additional images
        if (!empty($_FILES['additional_images']['name'][0])) {
            foreach ($_FILES['additional_images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['additional_images']['error'][$key] === UPLOAD_ERR_OK) {
                    $file_name = $_FILES['additional_images']['name'][$key];
                    $file_type = $_FILES['additional_images']['type'][$key];
                    $file_size = $_FILES['additional_images']['size'][$key];
                    
                    // Validate image
                    if (!in_array($file_type, $allowed)) {
                        throw new Exception('Invalid file type for additional image');
                    }
                    
                    if ($file_size > 5000000) {
                        throw new Exception('Additional image size must be less than 5MB');
                    }
                    
                    $new_name = time() . '_' . $file_name;
                    move_uploaded_file($tmp_name, '../uploads/rooms/' . $new_name);
                    
                    // Insert image record
                    $img_stmt = $conn->prepare("INSERT INTO room_images (room_id, image_path) VALUES (?, ?)");
                    $img_stmt->bind_param("is", $room_id, $new_name);
                    $img_stmt->execute();
                }
            }
        }
        
        $conn->commit();
        $_SESSION['success'] = 'Room updated successfully';
        header('location: ../rooms.php');
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = 'Error updating room: ' . $e->getMessage();
        header('location: ../rooms.php');
    }
} else {
    $_SESSION['error'] = 'Invalid request';
    header('location: ../rooms.php');
}
?>