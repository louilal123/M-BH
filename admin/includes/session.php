<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    http_response_code(404); 
    include('404.html'); 
    
    exit;
}

$admin_fullname = $_SESSION['fullname'];
$admin_username = $_SESSION['username'];
$admin_email = $_SESSION['email'];
$admin_role = $_SESSION['role'];
$admin_photo = $_SESSION['photo'];



include "functions/functions.php";
updateRoomAvailability();

?>