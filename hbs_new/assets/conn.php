<?php
$servername = "localhost";
$username = "root";
$password = "";
$connname = "hbs_3";

// Create connection
$conn = new mysqli($servername, $username, $password, $connname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>