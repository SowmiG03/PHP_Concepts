

<?php
include 'CONNECTION.php';
//print_r($_POST);
if(isset($_POST['submitbtn']))
{
    $firstname=$_POST['firstname'];
    $lastname=$_POST['lastname'];
    $phonenumber=$_POST['phonenumber'];
    $email=$_POST['email'];
    $gender=$_POST['gender'];
    $dob=$_POST['dob'];
    $tags=$_POST['tags'];
    $address=$_POST['address'];
    $contact_type=$_POST['contact_type'];
}

$sql="INSERT INTO contact(firstname,lastname,phonenumber,email,gender,dob,tags,address,contact_type) VALUES ('$firstname','$lastname','$phonenumber','$email','$gender','$dob','$tags','$address','$contact_type') ";

$res=mysqli_query($connection,$sql);
if($res)

{
    
    echo 'alert("Record inserted successfuly")';
    header("Location:create.php");
    
}
else{
   
     echo "Not inserted <br>";
    
}

?>
<button><a href="index.php">Go back</a></button>
