
<?php
session_start(); // Start the session

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
<h2>Basic authentication flow with session management.</h2>
<hr>
    <h1>Welcome,<i><u> <?php echo $_SESSION['username']; ?>!</i></u></h1>
    <button ><a href="logout.php">Logout</a></button>
    </div>
</body>
</html>
