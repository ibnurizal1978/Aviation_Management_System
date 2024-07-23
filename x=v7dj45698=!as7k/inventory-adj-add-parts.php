<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$parts_id                = input_data(filter_var($_POST['parts_id'],FILTER_SANITIZE_STRING));
$parts_warehouse_id      = input_data(filter_var($_POST['parts_warehouse_id'],FILTER_SANITIZE_STRING));
$parts_rack_location_id  = input_data(filter_var($_POST['parts_rack_location_id'],FILTER_SANITIZE_STRING));
$qty                     = input_data(filter_var($_POST['qty'],FILTER_SANITIZE_STRING));
$qty2                 = input_data(filter_var($_POST['qty2'],FILTER_SANITIZE_STRING));

//echo 'qty :'.$qty;
//echo '<br/>new :'.$qty2;
$final = $qty+$qty2;
//echo '<br/>final :'.$final;
if($final < 0) {
  echo "<script>";
  echo "alert('New qty cannot minus'); window.location.href=history.back()";
  echo "</script>";
  exit();	
}

if($parts_id == '' || $parts_warehouse_id == '') {
  echo "<script>";
  echo "alert('Parts name and warehouse is empty'); window.location.href=history.back()";
  echo "</script>";
  exit();
}

$sql_adj 	= "SELECT parts_id FROM tbl_parts_adj_log WHERE parts_id = '".$parts_id."' AND adj_status = 'PENDING' LIMIT 1";
$h_adj 		= mysqli_query($conn, $sql_adj);
if(mysqli_num_rows($h_adj)>0) {
  echo "<script>";
  echo "alert('Parts already on list'); window.location.href=history.back()";
  echo "</script>";
  exit();
}


$sql1 	= "SELECT parts_name, parts_number, parts_price FROM tbl_parts WHERE parts_id = '".$parts_id."' LIMIT 1";
$h1 	= mysqli_query($conn, $sql1);
$row1 	= mysqli_fetch_assoc($h1);

$jumlah_adj = abs($qty2);
$sub_total = $row1['parts_price']*$jumlah_adj;

$sql = "INSERT INTO tbl_parts_adj_log SET parts_id = '".$parts_id."', parts_name = '".$row1['parts_name']."', parts_number = '".$row1['parts_number']."', parts_price = '".$row1['parts_price']."', sub_total = '".$sub_total."', before_qty = '".$qty."', adj_qty = '".$qty2."', new_qty = '".$final."', user_id = '".$_SESSION['user_id']."', full_name = '".$_SESSION['full_name']."',  parts_rack_location_id = '".$parts_rack_location_id."', parts_warehouse_id = '".$parts_warehouse_id."', created_date = UTC_TIMESTAMP(), adj_status = 'PENDING', client_id = '".$_SESSION['client_id']."'";
mysqli_query($conn, $sql);

echo "<script>";
echo "alert('Success!'); window.location.href=history.back()";
echo "</script>";
exit();
?>