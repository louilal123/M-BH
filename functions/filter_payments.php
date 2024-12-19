<?php
// Include the database connection file
include('connection.php');

// Get the selected month and year from the AJAX request
$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

// Check if both month and year are provided
if ($month && $year) {
    // Prepare the SQL query to fetch payments based on the selected month and year
    $query = "
        SELECT payment_id, booking_id, tenant_id, amount_due, amount_paid, payment_date, payment_method, payment_status
        FROM payments
        WHERE YEAR(payment_date) = ? AND MONTH(payment_date) = ?
    ";

    // Prepare and execute the query
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $year, $month); // Bind year and month as integers
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are results
        if ($result->num_rows > 0) {
            $payments = [];
            while ($row = $result->fetch_assoc()) {
                $payments[] = $row;
            }

            // Return the filtered payments as a JSON response
            echo json_encode($payments);
        } else {
            // No payments found
            echo json_encode([]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Failed to prepare the query."]);
    }
} else {
    echo json_encode(["error" => "Invalid month or year."]);
}

$conn->close();
?>
