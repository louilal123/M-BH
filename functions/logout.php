<?php
// Initialize the session
session_start();
session_unset(); // Unset all session variables

// Destroy the session.
session_destroy();

// Redirect to login page
header("location: ../index.php");
exit;
?>