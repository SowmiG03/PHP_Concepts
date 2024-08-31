<?php
include 'CONNECTION.php';
if(isset($_POST['update'])&&$_POST['update']=='Update')
{
    $id=$_POST['id'];
    $firstname=$_POST['firstname'];
    $lastname=$_POST['lastname'];
    $phonenumber=$_POST['phonenumber'];
    $email=$_POST['email'];
    $gender=$_POST['gender'];
    $dob=$_POST['dob'];
    $tags=$_POST['tags'];
    $address=$_POST['address'];
    $contact_type=$_POST['contact_type'];


$sql="update `contact` set firstname='$firstname', lastname='$lastname',phonenumber='$phonenumber',email='$email', 
gender='$gender',dob='$dob',tags='$tags',address='$address',contact_type='$contact_type' where id='$id'";

$res=mysqli_query($connection,$sql);
if($res){
    echo 'alert("Updated Successfully")';
    header("Location:create.php");
}
else{
    echo 'alert("Unable to Update")';
    header("Location:update.php");
}

}

mysqli_close($connection);



?>

</body>
</html>



