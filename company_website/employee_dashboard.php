<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: login.php");
    exit();
}

// Connect to the database <link rel="stylesheet" href
$mysqli = new mysqli('localhost', 'root', '', 'company_db');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch admin details
$result = $mysqli->query("SELECT * FROM users WHERE role = 'admin'");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
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
        <h2>Employee Dashboard</h2>
        <h3>Admin Details:</h3>
        <table>
            <tr>
    <th>
        Employee NAme
    </th>
    </tr>
       
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                <td>
                    <?php echo $row['username']; ?></td>
                </tr>
            <?php endwhile; ?>
     
        </table>
    </div>
</body>
</html>
<?php
$mysqli->close();
?>
