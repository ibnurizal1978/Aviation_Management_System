<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
$db_server        = '127.0.0.1';
$db_user          = 'root';
$db_password      = 'Database@123';
$db_name          = 'db_aviation';
$conn 			  = new mysqli($db_server,$db_user,$db_password,$db_name) or die (mysqli_error());

//add next due
$sql 	= "SELECT aircraft_parts_id,hrs FROM tbl_aircraft_parts WHERE hrs<>0";
$h		= mysqli_query($conn,$sql);
while($row = mysqli_fetch_assoc($h)) {
	$sql2 = "UPDATE tbl_aircraft_parts SET hrs2 = '".$row['hrs'].":00:00' WHERE aircraft_parts_id = '".$row['aircraft_parts_id']."'";
	echo $sql2.'<br/>';
	mysqli_query($conn,$sql2);		
}
