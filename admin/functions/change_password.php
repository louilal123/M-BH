<?php
session_start();
include('connection.php');  // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form values
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $renew_password = $_POST['renew_password'];

    // Fetch the logged-in user's data from the database
    $user_id = $_SESSION['admin_id'];  // Assuming you store the logged-in admin's ID in the session
    $query = "SELECT * FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id); // Bind user ID to the query
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user) {
        // Verify the current password
        if (password_verify($current_password, $user['password'])) {
            // Check if the new password and confirm password match
            if ($new_password === $renew_password) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $update_query = "UPDATE admin SET password = ? WHERE admin_id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("si", $hashed_password, $user_id);

                if ($update_stmt->execute()) {
                    $_SESSION['status'] = "Password updated successfully!";
                    $_SESSION['status_icon'] = "success";
                    header("Location: ../profile.php");
                    exit();
                } else {
                    $_SESSION['status'] = "Failed to update password. Try again later.";
                    $_SESSION['status_icon'] = "error";
                    header("Location: ../profile.php");
                    exit();

                }
            } else {
                $_SESSION['status'] = "New password and confirm password do not match.";
                $_SESSION['status_icon'] = "error";
                header("Location: ../profile.php");
                exit();
            }
        } else {
            $_SESSION['status'] = "Current password is incorrect.";
            $_SESSION['status_icon'] = "error";
            header("Location: ../profile.php");
            exit();
        }
    } else {
        $_SESSION['status'] = "User not found.";
        $_SESSION['status_icon'] = "error";
    }

    // Redirect back to the change password page
    header("Location: ../profile.php");
    exit();
}
?>
