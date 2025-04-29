<?php
include('connection.php');

if (isset($_GET['id'])) {
    $adminId = $_GET['id'];

    // Delete the admin
    $sql = "DELETE FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $adminId);

    if ($stmt->execute()) {
        header("Location: ../admin_management.php?success=Admin deleted successfully");
    } else {
        header("Location: ../admin_management.php?error=Failed to delete admin");
    }

    $stmt->close();
    $conn->close();
}
?>
