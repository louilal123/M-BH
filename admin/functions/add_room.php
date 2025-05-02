<?php
include 'connection.php';
include '../includes/session.php';

if(isset($_POST['add_room'])) {
    $room_number = $_POST['room_number'];
    $room_type_id = $_POST['room_type_id'];
    $status = $_POST['status']; // Will now be numeric (0, 1, or 2)
    
    // Check if room number already exists
    $check_query = "SELECT * FROM rooms WHERE room_number = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $room_number);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if($result->num_rows > 0) {
        $_SESSION['error'] = 'Room number already exists!';
        header('location: ../rooms.php');
        exit();
    }
    
    // Get room type details for default price
    $price_query = "SELECT price FROM room_types WHERE room_type_id = ?";
    $price_stmt = $conn->prepare($price_query);
    $price_stmt->bind_param("i", $room_type_id);
    $price_stmt->execute();
    $price_result = $price_stmt->get_result();
    $price = 0;
    
    if($price_result->num_rows > 0) {
        $row = $price_result->fetch_assoc();
        $price = $row['price'];
    }
    
    // Insert new room
    $insert_query = "INSERT INTO rooms (room_number, room_type_id, status, price, description, created_at) 
                     VALUES (?, ?, ?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_query);
    $description = "Room " . $room_number;
    $insert_stmt->bind_param("siids", $room_number, $room_type_id, $status, $price, $description);
    
    if($insert_stmt->execute()) {
        $_SESSION['success'] = 'Room added successfully! You can now upload images for this room.';
        header('location: ../rooms.php');
    } else {
        $_SESSION['error'] = 'Error adding room: ' . $conn->error;
        header('location: ../rooms.php');
    }
} else {
    $_SESSION['error'] = 'Invalid request!';
    header('location: ../rooms.php');
}
?>