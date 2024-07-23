<?php 
ini_set('display_errors',1);  error_reporting(E_ALL);
$db_server        = '127.0.0.1';
$db_user          = 'root';
$db_password      = 'Database@123';
$db_name          = 'db_aviation';
$conn 			  = new mysqli($db_server,$db_user,$db_password,$db_name) or die (mysqli_error());

$sql_d    = "SELECT user_certificate_id, user_certificate_file,user_certificate_date, certificate_master_id FROM tbl_user_certificate WHERE user_certificate_next = '0000-00-00'";
$h_d      = mysqli_query($conn,$sql_d) or die(mysqli_error());
while($row_d    = mysqli_fetch_assoc($h_d)) {

	$sql_next 	= "SELECT certificate_master_duration FROM tbl_certificate_master where certificate_master_id = '".$row_d['certificate_master_id']."' AND certificate_master_duration <> 0";
	$h_next 	= mysqli_query($conn,$sql_next) or die(mysqli_error());
	while($row_next	= mysqli_fetch_assoc($h_next)) {
		$sql 	= "UPDATE tbl_user_certificate SET user_certificate_next = DATE_ADD('".$row_d['user_certificate_date']."', INTERVAL ".$row_next['certificate_master_duration']." MONTH) WHERE user_certificate_id = '".$row_d['user_certificate_id']."'";	
		mysqli_query($conn, $sql);	
		echo $sql.'<br/>';
	} 
}
//mysqli_query($conn,$sql);

