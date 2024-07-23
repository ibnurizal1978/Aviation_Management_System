<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
$db_server        = '127.0.0.1';
$db_user          = 'root';
$db_password      = 'Database@123';
$db_name          = 'db_aviation';
$conn 			  = new mysqli($db_server,$db_user,$db_password,$db_name) or die (mysqli_error());

date_default_timezone_set('Asia/Jakarta');
//$tanggal = date('Y-m-d', mktime(0,0,0,date('m'),date('d')-1,date('Y')));
$tnggal = date('Y-m-d');

//Ini untuk masukin data afml_detail ke afml_price

$sql1a       = "SELECT a.afml_date, as afml_date, a.afml_detail_id as afml_detail_id, a.afml_page_no as afml_page_no, b.afml_captain_user_id as afml_captain_user_id, a.afml_date FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE a.afml_date = '".$tanggal."'";
$h1a         = mysqli_query($conn, $sql1a);
while($row1a       = mysqli_fetch_assoc($h1a)) {
	$sql2a 	= "INSERT INTO tbl_afml_price (afml_page_no, afml_detail_id, afml_date, user_id) VALUES ('".$row1a['afml_page_no']."','".$row1a['afml_detail_id']."', '".$row1a['afml_date']."', '".$row1a['afml_captain_user_id']."')";
	mysqli_query($conn, $sql2a);
	echo $sql2a.'<br/>'; 
}
echo '<hr/>';
$sql1b       = "SELECT a.afml_date, as afml_date, a.afml_detail_id as afml_detail_id, a.afml_page_no as afml_page_no, b.afml_copilot_user_id as afml_copilot_user_id FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE a.afml_date = '".$tanggal."'";
$h1b         = mysqli_query($conn, $sql1b);
while($row1b       = mysqli_fetch_assoc($h1b)) {
	$sql2b 	= "INSERT INTO tbl_afml_price (afml_page_no, afml_detail_id, afml_date, user_id) VALUES ('".$row1b['afml_page_no']."','".$row1b['afml_detail_id']."','".$row1a['afml_date']."', '".$row1b['afml_copilot_user_id']."')";
	mysqli_query($conn, $sql2b);
	echo $sql2b.'<br/>'; 
}

echo '<hr/>';
$sql1c       = "SELECT a.afml_date, as afml_date, a.afml_detail_id as afml_detail_id, a.afml_page_no as afml_page_no, b.afml_engineer_on_board_user_id as afml_engineer_on_board_user_id FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE a.afml_date = '".$tanggal."'";
$h1c         = mysqli_query($conn, $sql1c);
while($row1c       = mysqli_fetch_assoc($h1c)) {
	$sql2c 	= "INSERT INTO tbl_afml_price (afml_page_no, afml_detail_id, afml_date, user_id) VALUES ('".$row1c['afml_page_no']."','".$row1c['afml_detail_id']."','".$row1a['afml_date']."', '".$row1c['afml_engineer_on_board_user_id']."')";
	mysqli_query($conn, $sql2c);
	echo $sql2c.'<br/>'; 
}