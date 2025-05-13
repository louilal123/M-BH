
<?php
// C:\xampp\htdocs\M-BH\admin\functions\revenue-chart.php
require 'connection.php';

$revenueData = [];
$months = [];

$query = $conn->prepare("
    SELECT 
        DATE_FORMAT(payment_date, '%Y-%m') AS month, 
        SUM(amount_paid) AS total_revenue
    FROM payments
    WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY month
    ORDER BY month
");
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    $months[] = $row['month'];
    $revenueData[] = (float) $row['total_revenue'];
}

echo json_encode([
    'months' => $months,
    'revenue' => $revenueData
]);
