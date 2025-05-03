<?php
session_start();
include 'connection.php';

if (isset($_GET['id'])) {
    $room_id = $_GET['id'];
    
    try {
        // First, get all images associated with this room
        $stmt = $conn->prepare("SELECT image_path FROM room_images WHERE room_id = ?");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Delete all image files
        while ($row = $result->fetch_assoc()) {
            $image_path = '../uploads/rooms/' . $row['image_path'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        // Then delete the room and its images from database
        $conn->begin_transaction();
        
        // Delete images first
        $stmt = $conn->prepare("DELETE FROM room_images WHERE room_id = ?");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        
        // Now delete the room
        $stmt = $conn->prepare("DELETE FROM rooms WHERE room_id = ?");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        
        $conn->commit();
        
        $_SESSION['success'] = 'Room deleted successfully';
        $_SESSION['status_icon'] = 'success';
        $_SESSION['status'] = 'Room deleted successfully';
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = 'Error deleting room: ' . $e->getMessage();
    }
    
    header('location: ../rooms.php');
} else {
    $_SESSION['error'] = 'Invalid request';
    header('location: ../rooms.php');
}
?>