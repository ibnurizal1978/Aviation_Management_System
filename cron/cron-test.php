<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
$db_server        = '127.0.0.1';
$db_user          = 'root';
$db_password      = 'Database@123';
$db_name          = 'db_aviation';
$conn 			  = new mysqli($db_server,$db_user,$db_password,$db_name) or die (mysqli_error());

//ini cuma buat test aja, any script can be put in here
$sql 	= "SELECT afml_page_no, afml_block_on, afml_block_off, afml_block_hrs, afml_to, afml_ldg, afml_flt_hrs, afml_ldg_cycle, afml_captain_user_id FROM tbl_afml a INNER JOIN  tbl_afml_detail b USING (afml_page_no)";
$h		= mysqli_query($conn,$sql);
while($row = mysqli_fetch_assoc($h)) {
	echo $row['afml_captain_user_id'].' - '.$row['afml_page_no'].' - '.$row['afml_flt_hrs'].'<br/>';

	$sql_hrs1 = "INSERT INTO tbl_afml_pilot_hours (afml_page_no,user_id,afml_block_on,afml_block_off,afml_block_hrs,afml_to,afml_ldg,afml_flt_hrs,afml_ldg_cycle,client_id) VALUES ('".$row['afml_page_no']."','".$row['afml_captain_user_id']."','".$row['afml_block_on']."', '".$row['afml_block_off']."', '".$row['afml_block_hrs']."', '".$row['afml_to']."', '".$row['afml_ldg']."', '".$row['afml_flt_hrs']."' ,'".$row['afml_ldg_cycle']."', '".$_SESSION['client_id']."')";
	//echo $sql_hrs1.'<br/>';
	//mysqli_query($conn,$sql_hrs1);		
}
