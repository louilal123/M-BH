<?php
session_start();
include('connection.php'); // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $typeId = mysqli_real_escape_string($conn, $_POST['typeId']);
    $typeName = mysqli_real_escape_string($conn, $_POST['typeName']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $query = "UPDATE room_types SET type_name = '$typeName', description = '$description' WHERE type_id = '$typeId'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['status'] = "Room type updated successfully!";
        $_SESSION['status_icon'] = "success";
    } else {
        $_SESSION['status'] = "Failed to update room type.";
        $_SESSION['status_icon'] = "error";
    }

    header("Location: ../rooms.php");
    exit();
}
?>
