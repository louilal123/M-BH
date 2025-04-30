<?php
include 'connection.php';

// Define admin credentials
$username = 'admin';
$email = 'admin@example.com';
$plain_password = 'admin';
$fullname = 'Administrator';
$role = 'superadmin';
$photo = 'default.jpg';

// Hash the password
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

// Insert into the admin table
$sql = "INSERT INTO admin (username, email, password, fullname, role, photo) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $username, $email, $hashed_password, $fullname, $role, $photo);

if ($stmt->execute()) {
    echo "Admin user created successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
