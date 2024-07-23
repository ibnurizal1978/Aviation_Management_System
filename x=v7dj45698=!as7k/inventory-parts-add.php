<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$parts_name         = input_data(filter_var($_POST['parts_name'],FILTER_SANITIZE_STRING));
$parts_number       = $_POST['parts_number'];
$serial_number      = @$_POST['serial_number'];
$parts_stock        = input_data(filter_var($_POST['parts_stock'],FILTER_SANITIZE_STRING));
$parts_price        = input_data(filter_var($_POST['parts_price'],FILTER_SANITIZE_STRING));
$parts_rack_location_id     = input_data(filter_var($_POST['parts_rack_location_id'],FILTER_SANITIZE_STRING));
$parts_treshold     = input_data(filter_var($_POST['parts_treshold'],FILTER_SANITIZE_STRING));
$parts_price2       = str_replace(',', '', $parts_price);

if($parts_name == '' || $parts_number == '') {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill all forms'
  })
  </script>
<?php
  exit();
}

//apakah ada duplikat?

$sql  = "SELECT parts_number FROM tbl_parts WHERE parts_number = '".$parts_number."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate parts number'
  })
  </script>
<?php
  exit(); 
}

$sql   = "INSERT INTO tbl_parts (parts_name,parts_number,parts_treshold, serial_number,parts_stock,parts_price, parts_rack_location_id, created_date,user_id,client_id) VALUES ('".$parts_name."','".$parts_number."','".$parts_treshold."','".$serial_number."','".$parts_stock."','".$parts_price2."','".$parts_rack_location_id."', UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql);
$last_id = mysqli_insert_id($conn);

$sql1 = "INSERT INTO tbl_parts_location_stock SET parts_id = '".$last_id."', parts_rack_location_id = '".$parts_rack_location_id."', qty = '".$parts_stock."', created_date = UTC_TIMESTAMP(), user_id = '".$_SESSION['user_id']."'";
//echo $sql1.'<br/>';
mysqli_query($conn, $sql1);

$sql_data_log = "INSERT INTO tbl_parts_location_stock_log SET parts_id = '".$last_id."', from_parts_rack_location_id = '".$parts_rack_location_id."', to_parts_rack_location_id = '".$parts_rack_location_id."', qty = '".$parts_stock."', created_date = UTC_TIMESTAMP(), user_id = '".$_SESSION['user_id']."'";
mysqli_query($conn, $sql_data_log);


//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('PARTS-ADD','PARTS','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new parts Data','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({title: "Data updated!",text: "",type: "success"}).then(function() {window.location = "inventory-parts-transfer.php";});
</script>