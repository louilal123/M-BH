<?php
session_start();
include('connection.php');  // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form values
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $admin_id = $_SESSION['admin_id'];

    // Handle file upload for profile image
    $photo = $_FILES['photo']['name'];
    $target_dir = "../assets/uploads/";  
    $target_file = $target_dir . basename($photo);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate file extension (only allow jpg, jpeg, and png)
    $allowed_extensions = ['jpg', 'jpeg', 'png'];

    // Check if a new photo was uploaded
    if ($photo) {
        // Validate file type
        if (!in_array($imageFileType, $allowed_extensions)) {
            $_SESSION['status'] = "Only JPG, JPEG, and PNG files are allowed!";
            $_SESSION['status_icon'] = "error";
            header("Location: ../profile.php");
            exit();
        }

        // Handle file upload error
        if ($_FILES['photo']['error'] != 0) {
            $_SESSION['status'] = "File upload error: " . $_FILES['photo']['error'];
            $_SESSION['status_icon'] = "error";
            header("Location: ../profile.php");
            exit();
        }

        // If a new photo is uploaded, move it to the target directory
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            // Update the session with the new photo
            $_SESSION['photo'] = basename($photo);  // Save the new photo file name in the session
            $photo = basename($photo);  // Set photo to the uploaded file name for the database
        } else {
            $_SESSION['status'] = "Failed to upload photo.";
            $_SESSION['status_icon'] = "error";
            header("Location: ../profile.php");
            exit();
        }
    } else {
        // If no new photo was uploaded, fetch the existing photo from the database
        $query = "SELECT `photo` FROM `admin` WHERE `admin_id` = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $photo = $admin['photo'];  // Keep the old photo if no new file was uploaded
    }

    // Update the profile information in the database
    $query = "UPDATE `admin` SET `fullname` = ?, `email` = ?, `username` = ?, `photo` = ? WHERE `admin_id` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $fullName, $email, $username, $photo, $admin_id);

    // Execute the update query
    if ($stmt->execute()) {
        $_SESSION['status'] = "Profile updated successfully!";
        $_SESSION['status_icon'] = "success";
        header("Location: ../profile.php");
        exit();
    } else {
        $_SESSION['status'] = "Failed to update profile. Try again later.";
        $_SESSION['status_icon'] = "error";
        header("Location: ../profile.php");
        exit();
    }
}
?>
