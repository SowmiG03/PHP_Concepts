<?php
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // For simplicity, let's assume the credentials by default
    if ($username == 'sample' && $password == '5403') {
        $_SESSION['username'] = $username;
        header('Location: welcome_page.php');
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
   
</head>
<body>
    
    <div class="login-container">
    <h2>Basic authentication flow with session management.</h2>
    <hr>
        <h3>Login</h3>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Type 'sample' as input" required>
            <label for="password">Password:</label>
            <input type="password" id="password"  placeholder="Type '5403' as input" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
