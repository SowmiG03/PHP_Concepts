<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Homepage</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    text-align: center;
}

.container {
    width: 50%;
    margin: 0 auto;
    padding: 20px;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 100px;
}
 
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="container">
        <h1>Welcome to Our Company</h1>
        <p>Please <a href="login.php">login</a> or <a href="register.php">register</a> to access your account.</p>
    </div>
</body>
</html>
