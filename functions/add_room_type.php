<?php
session_start(); 
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
    $typeName = $_POST['typeName'];
    $description = $_POST['description'];

    $typeName = mysqli_real_escape_string($conn, $typeName);
    $description = mysqli_real_escape_string($conn, $description);

    if (empty($typeName) || empty($description)) {
        $_SESSION['status'] = 'Both fields are required.';
        $_SESSION['status_icon'] = 'error';
        header('Location: ../room_types.php');
        exit();
    }

    $query = "INSERT INTO room_types (type_name, description) VALUES ('$typeName', '$description')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['status'] = 'Room type added successfully!';
        $_SESSION['status_icon'] = 'success';
        header('Location: ../room_types.php');
        exit();
    } else {
        $_SESSION['status'] = 'Error Adding Room tpe'  . mysqli_error($conn);
        $_SESSION['status_icon'] = 'error: ';
        header('Location: ../room_types.php');
        exit();
    }
}
?>
