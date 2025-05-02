<?php
session_start();
include('connection.php'); // Database connection

if (isset($_GET['room_type_id'])) {
    $typeId = mysqli_real_escape_string($conn, $_GET['room_type_id']);

    // Delete the room type
    $query = "DELETE FROM room_types WHERE room_type_id = '$typeId'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['status'] = "Room type deleted successfully!";
        $_SESSION['status_icon'] = "success";
    } else {
        $_SESSION['status'] = "Failed to delete room type.";
        $_SESSION['status_icon'] = "error";
    }

    header("Location: ../room_types.php");
    exit();
}
?>
