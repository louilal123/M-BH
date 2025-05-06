<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $tenant_id = $_POST['tenant_id'];
    $room_id = $_POST['room_id'];
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];
    $status = $_POST['status'];
    $special_requests = $_POST['special_requests'];
    $total_amount = $_POST['total_amount'];

    // Get previous room_id and status
    $prev_data = $conn->query("SELECT room_id, status FROM bookings WHERE booking_id = $booking_id");
    $prev_row = $prev_data->fetch_assoc();
    $prev_room_id = $prev_row['room_id'];
    $prev_status = $prev_row['status'];

    // Check if dates are valid
    if (strtotime($check_out_date) <= strtotime($check_in_date)) {
        $_SESSION['status'] = "Check-out date must be after check-in date!";
        $_SESSION['status_icon'] = "error";
        header("Location: ../bookings.php");
        exit();
    }

    // Check if new room is available for the selected dates (excluding current booking)
    if ($room_id != $prev_room_id) {
        $check_availability = $conn->prepare("SELECT * FROM bookings 
            WHERE room_id = ? 
            AND booking_id != ?
            AND status NOT IN ('cancelled', 'completed')
            AND ((check_in_date <= ? AND check_out_date >= ?) 
            OR (check_in_date <= ? AND check_out_date >= ?))");
        $check_availability->bind_param("iissss", $room_id, $booking_id, $check_out_date, $check_out_date, $check_in_date, $check_in_date);
        $check_availability->execute();
        $result = $check_availability->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['status'] = "Room is not available for the selected dates!";
            $_SESSION['status_icon'] = "error";
            header("Location: ../bookings.php");
            exit();
        }
    }

    $sql = "UPDATE bookings 
            SET tenant_id = ?, room_id = ?, check_in_date = ?, check_out_date = ?, 
                status = ?, special_requests = ?, total_amount = ?
            WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissssdi", $tenant_id, $room_id, $check_in_date, $check_out_date, $status, $special_requests, $total_amount, $booking_id);

    if ($stmt->execute()) {
        // Update room availability based on status changes
        if ($prev_status != $status || $room_id != $prev_room_id) {
            // Reset previous room to available if no other active bookings
            if ($prev_room_id != $room_id) {
                $check_prev_room = $conn->query("SELECT COUNT(*) as count FROM bookings 
                    WHERE room_id = $prev_room_id 
                    AND status NOT IN ('cancelled', 'completed')
                    AND booking_id != $booking_id");
                $prev_room_count = $check_prev_room->fetch_assoc()['count'];
                
                if ($prev_room_count == 0) {
                    $conn->query("UPDATE rooms SET availability = 'available' WHERE room_id = $prev_room_id");
                }
            }
            
            // Update new room status
            if ($status == 'confirmed') {
                $conn->query("UPDATE rooms SET availability = 'occupied' WHERE room_id = $room_id");
            } elseif ($status == 'cancelled' || $status == 'completed') {
                $conn->query("UPDATE rooms SET availability = 'available' WHERE room_id = $room_id");
            }
        }
        
        $_SESSION['status'] = "Booking updated successfully!";
        $_SESSION['status_icon'] = "success";
    } else {
        $_SESSION['status'] = "Failed to update booking!";
        $_SESSION['status_icon'] = "error";
    }

    $stmt->close();
    $conn->close();
    header("Location: ../bookings.php");
    exit();
}
?>