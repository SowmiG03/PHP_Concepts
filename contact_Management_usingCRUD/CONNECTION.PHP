<?php
$connection=mysqli_connect("localhost","root","","contact_form");

if(!$connection)
{
    die("connection error".mysqli_connect_error());
}
?>