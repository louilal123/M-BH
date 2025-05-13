<?php
session_start();
include('connection.php');

if (isset($_GET['id'])) {
    $payment_id = intval($_GET['id']);
    
    // First get the proof photo path
    $sql = "SELECT proof_photo FROM payments WHERE payment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $payment = $result->fetch_assoc();
        $proof_photo = $payment['proof_photo'];
        
        // Delete the payment record
        $delete_sql = "DELETE FROM payments WHERE payment_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $payment_id);
        
        if ($delete_stmt->execute()) {
            // Delete the proof photo file
            if (!empty($proof_photo)) {
                $file_path = '../assets/uploads/payments/' . $proof_photo;
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }
            }
            
            $_SESSION['status'] = "Payment deleted successfully!";
            $_SESSION['status_icon'] = "success";
            header("Location: ../payments.php");
            exit();
        } else {
            $_SESSION['status'] = "Failed to delete payment: " . $conn->error;
            $_SESSION['status_icon'] = "error";
            header("Location: ../payments.php");
            exit();
        }
    } else {
        $_SESSION['status'] = "Payment not found";
        $_SESSION['status_icon'] = "error";
        header("Location: ../payments.php");
        exit();
    }
} else {
    $_SESSION['status'] = "Invalid request";
    $_SESSION['status_icon'] = "error";
    header("Location: ../payments.php");
    exit();
}
?>