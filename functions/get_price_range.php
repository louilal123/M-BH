<?php
include "../admin/functions/connection.php";

$minPriceQuery = "SELECT MIN(price) as min_price FROM rooms";
$maxPriceQuery = "SELECT MAX(price) as max_price FROM rooms";

$minResult = $conn->query($minPriceQuery);
$maxResult = $conn->query($maxPriceQuery);

$minPrice = $minResult->fetch_assoc()['min_price'];
$maxPrice = $maxResult->fetch_assoc()['max_price'];

header('Content-Type: application/json');
echo json_encode([
    'min' => (int)$minPrice,
    'max' => (int)$maxPrice
]);
?>