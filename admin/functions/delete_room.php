<?php
session_start();
include('connection.php'); // Database connection

if (isset($_GET['id'])) {
    $typeId = mysqli_real_escape_string($conn, $_GET['id']);

    try {
        // Delete the room type
        $query = "DELETE FROM rooms WHERE room_id = '$typeId'";
        if (mysqli_query($conn, $query)) {
            $_SESSION['status'] = "Room deleted successfully!";
            $_SESSION['status_icon'] = "success";
        } else {
            $_SESSION['status'] = "Failed to delete room type due to a database error.";
            $_SESSION['status_icon'] = "error";
        }
    } catch (mysqli_sql_exception $e) {
        // Handle foreign key constraint violation
        $_SESSION['status'] = "Cannot delete the room. It is referenced in other records.";
        $_SESSION['status_icon'] = "error";
    } catch (Exception $e) {
        // Handle any other exceptions
        $_SESSION['status'] = $e->getMessage();
        $_SESSION['status_icon'] = "error";
    }

    header("Location: ../rooms.php");
    exit();
}
?>
