<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$parts_id          	= input_data(filter_var($_POST['parts_id'],FILTER_SANITIZE_STRING));
$request_qty       	= input_data(filter_var($_POST['request_qty'],FILTER_SANITIZE_STRING));
$aircraft_reg_code  = input_data(filter_var($_POST['aircraft_reg_code'],FILTER_SANITIZE_STRING));

if($request_qty == '' || $aircraft_reg_code == '') {
  echo "<script>";
  echo "alert('Fill qty and aircraft reg. code'); window.location.href=history.back()";
  echo "</script>";
  exit();
}

$sql2 = "INSERT INTO tbl_parts_request SET parts_id = '".$parts_id."', request_qty = '".$request_qty."', request_user_id = '".$_SESSION['user_id']."', request_date = UTC_TIMESTAMP(), request_status = 'PENDING', aircraft_reg_code = '".$aircraft_reg_code."'";
//echo $sql2.'<br/>';
mysqli_query($conn, $sql2);

echo "<script>";
echo "alert('Success!'); window.location.href=history.back()";
echo "</script>";
exit();
?>