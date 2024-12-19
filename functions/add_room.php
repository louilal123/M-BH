<?php
session_start();
include('connection.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roomNumber = mysqli_real_escape_string($conn, $_POST['roomNumber']);
    $roomType = mysqli_real_escape_string($conn, $_POST['roomType']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Handle photo upload
  // Handle photo upload
$photo = $_FILES['photo']['name'];
$targetDir = "../assets/uploads/";
$targetFile = $targetDir . basename($photo);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if the file is an actual image only if a file is uploaded
if (!empty($photo)) {
    $check = getimagesize($_FILES['photo']['tmp_name']);
    if ($check === false) {
        $_SESSION['status'] = "File is not an image.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../rooms.php");
        exit();
    }

    // Check file size (5MB limit)
    if ($_FILES['photo']['size'] > 5000000) {
        $_SESSION['status'] = "File is too large (max 5MB).";
        $_SESSION['status_icon'] = "error";
        header("Location: ../rooms.php");
        exit();
    }

    // Allow only certain formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $_SESSION['status'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../rooms.php");
        exit();
    }

    // Upload file
    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
        $_SESSION['status'] = "Error uploading the photo.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../rooms.php");
        exit();
    }
} else {
    $photo = null; // No photo uploaded
}


   // Check if the room number already exists in the database using a prepared statement
$checkRoomQuery = "SELECT room_number FROM rooms WHERE room_number = ?";
$stmt = $conn->prepare($checkRoomQuery);
$stmt->bind_param("s", $roomNumber); // Assuming room_number is stored as a string
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    // Room number already exists, show error message
    $_SESSION['status'] = "Room number already exists.";
    $_SESSION['status_icon'] = "error";
    header("Location: ../rooms.php");
    exit();
}

    // Insert data into the database if room number is unique
    $query = "INSERT INTO rooms (room_number, room_type, price, description, photo) 
              VALUES ('$roomNumber', '$roomType', '$price', '$description', '$photo')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['status'] = "Room added successfully!";
        $_SESSION['status_icon'] = "success";
        header("Location: ../rooms.php");
        exit();
    } else {
        $_SESSION['status'] = "Failed to add the room. Please try again.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../rooms.php");
        exit();
    }
}
?>
