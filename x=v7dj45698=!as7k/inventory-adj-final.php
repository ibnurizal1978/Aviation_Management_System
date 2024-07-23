<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$adj_date         = input_data(filter_var($_POST['adj_date'],FILTER_SANITIZE_STRING));
$description      = input_data(filter_var($_POST['description'],FILTER_SANITIZE_STRING));

if($adj_date == '') {
	echo "<script>";
	echo "alert('Date is empty!'); window.location.href=history.back()";
	echo "</script>";
exit();
}

$adj_date_y   = substr($adj_date,6,4);
$adj_date_m   = substr($adj_date,3,2);
$adj_date_d   = substr($adj_date,0,2);
$adj_date_f   = $adj_date_y.'-'.$adj_date_m.'-'.$adj_date_d;

$tgl  = date('Y-m-d');
$sql 	= "UPDATE tbl_parts_adj_log SET adj_date = '".$adj_date_f."', description = '".$description."', adj_status = 'COMPLETED' WHERE date(created_date) = '".$tgl."' AND adj_status = 'PENDING'";
//echo $sql;
mysqli_query($conn, $sql);

$sql2 	= "SELECT * FROM tbl_parts_adj_log WHERE adj_date = '".$adj_date_f."' AND adj_status = 'COMPLETED'";
$h2 	= mysqli_query($conn, $sql2);
while($row2 	= mysqli_fetch_assoc($h2)) {

	$sql3 	= "UPDATE tbl_parts_location_stock SET qty = '".$row2['new_qty']."' WHERE parts_rack_location_id = '".$row2['parts_rack_location_id']."' AND parts_id = '".$row2['parts_id']."'";
	mysqli_query($conn, $sql3);

	$sql_data_log = "INSERT INTO tbl_parts_location_stock_log SET parts_id = '".$row2['parts_id']."', from_parts_rack_location_id = '".$row2['parts_rack_location_id']."', to_parts_rack_location_id = '', qty = '".$row2['adj_qty']."', created_date = UTC_TIMESTAMP(), user_id = '".$_SESSION['user_id']."', notes = 'Adjustment. See adjustment log'";
	mysqli_query($conn, $sql_data_log);
}

echo "<script>";
echo "alert('Success!'); window.location.href=history.back()";
echo "</script>";
exit();
?>