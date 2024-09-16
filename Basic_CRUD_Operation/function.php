<?php
//database connectivity 
$connection=mysqli_connect("localhost","root","","test");
if(!$connection)
{
    die ("error".mysqli_connect_error());
}

//function for insertion of data
function Create(){
    global $connection;
    if(isset($_POST['submit']))
    {
        $name=$_POST['name'];
       $query="insert into CRUD (name) values('$name')";
        $result=mysqli_query($connection,$query);
        if($result)
        {
         echo "<script>alert('Inserted successfully');</script>";
        }
        else{
         echo "Insertion failed";
        }
    
    }
}

//function for fetching(View) data from database
function Read(){
    global $connection;
    if(isset($_POST['submit']))
{
  
    $q="select * from CRUD";
    $res=mysqli_query($connection,$q);
   echo '<table>';
        while($row=mysqli_fetch_assoc($res))
        {
            echo '<tr>';
            echo '<td>'.$row['id'].'</td>';
            echo '<td>'.$row['name'].'</td>';
            echo '<td><a href="UpdateData.php?id=' . $row['id'] . '">update</a></td>';
            echo '<td><a href="DeleteData.php?id=' . $row['id'] . '">delete</a></td>';
            echo '</tr>';
         
        }
        echo '</table>';
}

}
//function for modification of data
function Update(){
    global $connection;
if(isset($_POST['update']))
{

   $name=$_POST['name'];
   $id=$_POST['id'];
      
        $query="update CRUD set name='$name' where id=$id";
        $result=mysqli_query($connection,$query);
        if($result)
        {
         echo "<script>alert('Updated successfully');</script>";
        }
        else{
         echo "updation failed";
        }
       
    }
   }


//function for deleting the data
function Delete(){
   global $connection;
   if(isset($_GET['id']))
{
    $id=$_GET['id'];
   $query="delete from CRUD where id=$id";
    $res=mysqli_query($connection,$query);
    if($res)
    {
      echo "<script>alert(' Id:".$id." Deleted successfully');</script>";   
    }
    else{
      echo "Deletion failed";
    }



}
}
?>