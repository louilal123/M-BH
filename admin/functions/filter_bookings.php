<?php
include('connection.php');

// Get the selected month and year
$month = $_GET['month'];
$year = $_GET['year'];

// Query to fetch bookings for the selected month and year
$query = "SELECT * FROM bookings WHERE MONTH(booking_date) = ? AND YEAR(booking_date) = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $month, $year);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any bookings
$bookings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

// Return the result as JSON
echo json_encode($bookings);
?>
