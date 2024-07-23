<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$parts_id                     = input_data(filter_var($_POST['parts_id'],FILTER_SANITIZE_STRING));
$qty                          = input_data(filter_var($_POST['qty'],FILTER_SANITIZE_STRING));
$from_parts_rack_location_id  = input_data(filter_var($_POST['from_parts_rack_location_id'],FILTER_SANITIZE_STRING));
$parts_rack_location_id       = input_data(filter_var($_POST['parts_rack_location_id'],FILTER_SANITIZE_STRING));


if($qty == '' || $parts_rack_location_id == '') {
  echo "<script>";
  echo "alert('Fill qty and destination'); window.location.href=history.back()";
  echo "</script>";
  exit();
}

$sql  = "SELECT qty FROM tbl_parts_location_stock WHERE parts_id = '".$parts_id."' AND parts_rack_location_id = '".$from_parts_rack_location_id."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
if($qty >  $row['qty']) {
  echo "<script>";
  echo "alert('Qty destination cannot be larger than curent'); window.location.href=history.back()";
  echo "</script>";
  exit();
}

//kurangi qty current
$qty_akhir = $row['qty'] - $qty;
$sql1 = "UPDATE tbl_parts_location_stock SET qty = '".$qty_akhir."' WHERE parts_id = '".$parts_id."' AND parts_rack_location_id = '".$from_parts_rack_location_id."'";
//echo $sql1.'<br/>';
mysqli_query($conn, $sql1);

$sql2 = "INSERT INTO tbl_parts_location_stock SET parts_id = '".$parts_id."', parts_rack_location_id = '".$parts_rack_location_id."', qty = '".$qty."', created_date = UTC_TIMESTAMP(), user_id = '".$_SESSION['user_id']."'";
//echo $sql1.'<br/>';
mysqli_query($conn, $sql2);

$sql_data_log = "INSERT INTO tbl_parts_location_stock_log SET parts_id = '".$parts_id."', from_parts_rack_location_id = '".$from_parts_rack_location_id."', to_parts_rack_location_id = '".$parts_rack_location_id."', qty = '".$qty."', created_date = UTC_TIMESTAMP(), user_id = '".$_SESSION['user_id']."'";
mysqli_query($conn, $sql_data_log);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('PARTS-TRANSFER','PARTS','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'Transfer from $from_parts_rack_location_id to $parts_rack_location_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
echo "<script>";
echo "alert('Success!'); window.location.href=history.back()";
echo "</script>";
exit();
?>