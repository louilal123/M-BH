<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $photo = $_FILES['photo']['name'];
    $targetDir = "../assets/uploads/";
    $targetFile = $targetDir . basename($photo);
    move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);

    $sql = "INSERT INTO admin (email, username, fullname, role, password, photo) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $email, $username, $fullname, $role, $password, $photo);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Admin added successfully!";
        $_SESSION['status_icon'] = "success";
        header("Location: ../users.php");
        exit();
    } else {
        $_SESSION['status'] = "Failed to add admin!";
        $_SESSION['status_icon'] = "error";
        header("Location: ../users.php");
        exit();
    }

    $stmt->close();
    $conn->close();
  
}
?>
