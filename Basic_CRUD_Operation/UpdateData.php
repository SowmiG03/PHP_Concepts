<?php
include "function.php";
//function for updation of data
echo Update();  
if(isset($_GET['id']))
{
    //id got from the url
    $id=$_GET['id'];
    $query="select id,name from CRUD where id=$id";
    $result=mysqli_query($connection,$query);
    $no_of_rows=mysqli_num_rows($result);
    if($no_of_rows>0)
    {

    while($row=mysqli_fetch_assoc($result))
    {
        $id=$row['id'];
        $name=$row['name'];

    }


}
else{
 echo "Record not found";
}

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
    <h2>Update Data</h2>
<form method="post" action="UpdateData.php">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <input type="text" name="name" value="<?php echo $name ?>">
    <input type="submit" name="update" value="update">
 

</form>
</body>
</html>

<?php

}
?>