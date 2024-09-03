<?php
// Start the session
session_start();

// Destroy the session
session_destroy();

// Optionally, unset all session variables
session_unset();

// Redirect to login page or home page after logout
header("Location: login.php");
exit();
?>
