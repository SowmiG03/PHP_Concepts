

<?php
    include 'CONNECTION.php';
if(isset($_GET['id']))
{
    {
        $id=$_GET['id'];
    }
    
$sql="delete from contact where id='$id'";
$res=mysqli_query($connection,$sql);

if($res)
{
    echo '<script>alert("deleted sucessfully '.$id.'")</script>';
}
else{
    echo "unable to delete";
}
}
else{
    echo " something went wrong";
}

?>