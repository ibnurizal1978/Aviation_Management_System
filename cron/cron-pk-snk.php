<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
$db_server        = '127.0.0.1';
$db_user          = 'root';
$db_password      = 'Database@123';
$db_name          = 'db_aviation';
$conn 			  = new mysqli($db_server,$db_user,$db_password,$db_name) or die (mysqli_error());

//add next due
$sql 	= "SELECT item_number,ata_code,position,description,part_number,serial_number,mth,hrs,ldg FROM tbl_aircraft_parts WHERE aircraft_master_id  = 7";
$h		= mysqli_query($conn,$sql);
while($row = mysqli_fetch_assoc($h)) {

	//echo $row['item_number'].' - '.$row['description'].'<br/>';
	$sql2 = "INSERT INTO tbl_aircraft_parts (item_number,ata_code,position,description,part_number,serial_number,mth,ldg,hrs,aircraft_master_id,created_date,user_id,client_id) VALUES ('".$row['item_number']."','".$row['ata_code']."','".$row['position']."','".$row['description']."','".$row['part_number']."','".$row['serial_number']."','".$row['mth']."','".$row['ldg']."','".$row['hrs']."',8,UTC_TIMESTAMP(),1,1)";
	echo $sql2.'<br/>';
	mysqli_query($conn,$sql2);		
}
