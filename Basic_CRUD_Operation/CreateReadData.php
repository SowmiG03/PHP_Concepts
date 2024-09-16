<?php
//include the function.php file so that only we can use the function of create() and read()
include "function.php";

//function for insert data
Create();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=
    , initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2> Basic CRUD Operation</h2>
<form method="post" action="CreateReadData.php">
    <input type="text" name="name" placeholder="Enter the Name">
    <input type="submit" name="submit" value="submit">
    
</form>

<?php
//function to view the data
Read();
?>
</body>
</html>