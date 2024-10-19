<?php 
include('assets/conn.php');
$id=$_GET['id'];
  
$delete=$conn->query("delete from `hall_details` where hall_id='$id'");
	if($delete){
		header('location:view_modify_hall.php');
	}
?>