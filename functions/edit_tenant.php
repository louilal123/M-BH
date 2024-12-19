<?php
session_start();
include('connection.php');

// Check if form is submitted
if (isset($_POST['tenant_id'])) {
    $tenant_id = $_POST['tenant_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $photo = null;

    // Check if a photo was uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileSize = $_FILES['photo']['size'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Validate file extension and size
        if (in_array($fileExt, $allowedExtensions) && $fileSize <= 2 * 1024 * 1024) {
            $uploadDir = '../uploads/tenants/';
            $newFileName = uniqid('tenant_', true) . '.' . $fileExt;

            // Create uploads directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $photo = $newFileName;
            } else {
                $_SESSION['status'] = "Failed to upload photo.";
                $_SESSION['status_icon'] = "error";
                header("Location: ../tenants.php");
                exit();
            }
        } else {
            $_SESSION['status'] = "Invalid file type or size.";
            $_SESSION['status_icon'] = "error";
            header("Location: ../tenants.php");
            exit();
        }
    }

    // Prepare SQL query
    $query = "UPDATE tenants SET name = ?, email = ?, contact = ?, address = ?, status = ?";
    $params = [$name, $email, $contact, $address, $status];
    $types = "sssss";

    // Add photo to query if uploaded
    if ($photo) {
        $query .= ", photo = ?";
        $params[] = $photo;
        $types .= "s";
    }

    $query .= " WHERE tenant_id = ?";
    $params[] = $tenant_id;
    $types .= "i";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Tenant information updated successfully.";
        $_SESSION['status_icon'] = "success";
    } else {
        $_SESSION['status'] = "Failed to update tenant information.";
        $_SESSION['status_icon'] = "error";
    }

    $stmt->close();
    header("Location: ../tenants.php");
    exit();
}

// Close the database connection
$conn->close();
?>
