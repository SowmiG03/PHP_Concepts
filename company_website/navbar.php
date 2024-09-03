<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started yet
} // Start the session

if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = null; // Set a default null value if not logged in
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333;
        }

        li {
            float: left;
        }

        li a, .dropbtn {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        li a:hover:not(.active) {
            background-color: black55;
        }

        .dropdown:hover .dropbtn{
            background-color: black;
        }

        .active {
            background-color:#111;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            
        }

        .dropdown {
            float: right;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color:#111;
            
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: white;
            
        }

        .dropdown:hover .dropdown-content {
            display: block;
            background-color: white;
            color: black;
        }
    </style>
</head>
<body>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="register.php">Register</a></li>
        <li><a href="login.php">Login</a></li>
       
       

        <?php if ($_SESSION['username']): ?>
        <!-- If the user is logged in, show their name with a dropdown -->
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
            <div class="dropdown-content">
                <a href="logout.php">Logout</a>
            </div>
        </li>
        <?php else: ?>
        <!-- If the user is not logged in, show the About link -->
        <li style="float:right" class="active">Welcome</li>
        <?php endif; ?>
    </ul>
</body>
</html>
