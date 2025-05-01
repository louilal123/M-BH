<?php
//C:\xampp\htdocs\M-BH\admin\functions\connection.php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bh_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>