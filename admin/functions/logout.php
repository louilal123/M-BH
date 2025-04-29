<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

$_SESSION['status'] = "Logout successfully!";
$_SESSION['status_icon'] = "error";
header("Location: ../index.php"); 
exit;
?>
