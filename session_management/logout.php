<?php
session_start(); // Start the Session
session_destroy(); // Destroy the Session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
<h2>Basic authentication flow with session management.</h2>
<hr>
    <h1>You have been logged out.</h1>
    <button ><a href="login.php">Login again</a></button>
   
    </div>
</body>
</html>
