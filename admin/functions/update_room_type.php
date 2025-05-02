<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_room_type'])) {
    // Get form data
    $room_type_id = $_POST['room_type_id'];
    $typeName = $_POST['typeName'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $features = $_POST['features'];

    // Validate required fields
    if (empty($typeName) || empty($description) || empty($price)) {
        $_SESSION['status'] = 'Please fill in all required fields.';
        $_SESSION['status_icon'] = 'error';
        header('Location: ../room_types.php');
        exit();
    }

    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE room_types SET 
                          type_name = ?, 
                          description = ?, 
                          price = ?, 
                          features = ? 
                          WHERE room_type_id = ?");
    
    $stmt->bind_param("ssdsi", $typeName, $description, $price, $features, $room_type_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['status'] = 'Room type updated successfully!';
            $_SESSION['status_icon'] = 'success';
        } else {
            $_SESSION['status'] = 'No changes were made to the room type.';
            $_SESSION['status_icon'] = 'info';
        }
    } else {
        $_SESSION['status'] = 'Error updating room type: ' . $stmt->error;
        $_SESSION['status_icon'] = 'error';
    }

    $stmt->close();
    $conn->close();
    
    header('Location: ../room_types.php');
    exit();
}
?>