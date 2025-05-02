<?php
session_start(); 
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_room_type'])) {
    // Get form data
    $typeName = $_POST['typeName'];
    $description = $_POST['description'];
    $price = isset($_POST['price']) ? $_POST['price'] : 0;
    $features = isset($_POST['features']) ? $_POST['features'] : '';

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO room_types (type_name, description, price, features) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $typeName, $description, $price, $features);

    if ($stmt->execute()) {
        $_SESSION['status'] = 'Room type added successfully!';
        $_SESSION['status_icon'] = 'success';
    } else {
        $_SESSION['status'] = 'Error adding room type: ' . $stmt->error;
        $_SESSION['status_icon'] = 'error';
    }

    $stmt->close();
    $conn->close();
    
    header('Location: ../room_types.php');
    exit();
}
?>