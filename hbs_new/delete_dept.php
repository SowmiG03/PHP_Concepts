<?php 
include('assets/conn.php');
$id=$_GET['id'];
  
$delete=$conn->query("delete from `school_department` where school_id='$id'");
	if($delete){
		header('location:view_dept.php');
	}
?>