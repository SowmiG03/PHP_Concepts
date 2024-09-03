<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
   <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
   
}
.register{
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
        <h2 class="register">Register</h2><br>
        <form action="register_process.php" method="POST">
        <label>UserName:</label>
            <input type="text" name="username" placeholder="Enter your username" required><br><br>
            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter your password" required><br><br>
            <label>Select Role:</label>
            <select name="role" required>
                <option value="employee">Employee</option>
                <option value="admin">Admin</option>
            </select><br><br>
            <input type="submit" class="btn" value="Register">
        </form>
    </div>
</body>
</html>
