<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$parts_warehouse_id     = input_data(filter_var($_POST['parts_warehouse_id'],FILTER_SANITIZE_STRING));
$parts_warehouse_name     = input_data(filter_var($_POST['parts_warehouse_name'],FILTER_SANITIZE_STRING));
$parts_warehouse_location = input_data(filter_var($_POST['parts_warehouse_location'],FILTER_SANITIZE_STRING));

if($parts_warehouse_name == "" || $parts_warehouse_location == "") {
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
$sql  = "SELECT parts_warehouse_name FROM tbl_parts_warehouse WHERE parts_warehouse_name = '".$parts_warehouse_name."' AND client_id = '".$_SESSION['client_id']."' AND parts_warehouse_id <> '".$parts_warehouse_id."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate data'
  })
  </script>
<?php
  exit(); 
}

$sql   = "UPDATE tbl_parts_warehouse SET parts_warehouse_name='".$parts_warehouse_name."',parts_warehouse_location='".$parts_warehouse_location."' WHERE parts_warehouse_id = '".$parts_warehouse_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('WAREHOUSE-EDIT','WAREHOUSE','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'edit Warehouse Data','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({title: "Data updated!",text: "",type: "success"}).then(function() {window.location = "inventory-warehouse.php";});
</script>