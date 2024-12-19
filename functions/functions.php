<?php
include('connection.php'); // Include database connection

function updateRoomAvailability() {
    global $conn;

    // Query to get all rooms and their booking dates
    $query = "SELECT r.room_id, b.check_in_date, b.check_out_date
              FROM rooms r
              LEFT JOIN bookings b ON r.room_id = b.room_id
              WHERE r.availability != 'available'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Loop through each room and check its availability
        while ($room = $result->fetch_assoc()) {
            $roomId = $room['room_id'];
            $checkInDate = $room['check_in_date'];
            $checkOutDate = $room['check_out_date'];

            // Query to check if the room is available within the booking dates
            $checkQuery = "SELECT booking_id FROM bookings 
                           WHERE room_id = ? 
                           AND ((check_in_date BETWEEN ? AND ?) 
                           OR (check_out_date BETWEEN ? AND ?) 
                           OR (check_in_date <= ? AND check_out_date >= ?))";

            if ($stmt = $conn->prepare($checkQuery)) {
                $stmt->bind_param("issssss", $roomId, $checkInDate, $checkOutDate, $checkInDate, $checkOutDate, $checkInDate, $checkOutDate);
                $stmt->execute();
                $stmt->store_result();

                // If no bookings exist, update the availability to 'available'
                if ($stmt->num_rows == 0) {
                    $updateQuery = "UPDATE rooms SET availability = 'available' WHERE room_id = ?";
                    if ($updateStmt = $conn->prepare($updateQuery)) {
                        $updateStmt->bind_param("i", $roomId);
                        $updateStmt->execute();
                        $updateStmt->close();
                    }
                }

                $stmt->close();
            }
        }
    }
}

updateRoomAvailability();
?>
