<?php
session_start();
include('connection.php');

// Validate input
function validate_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

if (isset($_POST['roomId']) && isset($_POST['roomNumber']) && isset($_POST['roomType']) && isset($_POST['price']) && isset($_POST['description']) && isset($_POST['availability'])) {
    $roomId = validate_input($_POST['roomId']);
    $roomNumber = validate_input($_POST['roomNumber']);
    $roomType = validate_input($_POST['roomType']);
    $price = validate_input($_POST['price']);
    $description = validate_input($_POST['description']);
    $availability = validate_input($_POST['availability']);

    // Handle file upload if there is a new photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = $_FILES['photo']['name'];
        $photoTemp = $_FILES['photo']['tmp_name'];
        $photoPath = "../assets/uploads/" . $photo;
        move_uploaded_file($photoTemp, $photoPath);
    } else {
        // If no new photo, keep the old one
        $query = "SELECT photo FROM rooms WHERE room_id = '$roomId'";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $photo = $row['photo'];
    }

    // Update room details, including availability
    $query = "UPDATE rooms 
              SET room_number = '$roomNumber', room_type = '$roomType', price = '$price', description = '$description', photo = '$photo', availability = '$availability'
              WHERE room_id = '$roomId'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['status'] = "Room updated successfully!";
        $_SESSION['status_icon'] = "success";
    } else {
        $_SESSION['status'] = "Failed to update room.";
        $_SESSION['status_icon'] = "error";
    }

    header("Location: ../rooms.php");
    exit();
}
?>
