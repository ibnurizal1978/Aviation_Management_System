<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
$db_server        = '127.0.0.1';
$db_user          = 'root';
$db_password      = 'Database@123';
$db_name          = 'db_aviation';
$conn 			  = new mysqli($db_server,$db_user,$db_password,$db_name) or die (mysqli_error());

//add next due
$sql 	= "SELECT aircraft_parts_id,mth,installed_date FROM tbl_aircraft_parts_2 WHERE mth<>0";
$h		= mysqli_query($conn,$sql);
while($row = mysqli_fetch_assoc($h)) {
	$sql2 = "UPDATE tbl_aircraft_parts_2 SET next_due = DATE_ADD('".$row['installed_date']."', INTERVAL ".$row['mth']." MONTH) WHERE aircraft_parts_id = '".$row['aircraft_parts_id']."'";
	echo $sql2.'<br/>';
	mysqli_query($conn,$sql2);		
}
