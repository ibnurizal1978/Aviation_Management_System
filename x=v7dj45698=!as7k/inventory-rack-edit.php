<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$parts_rack_location_id     = input_data(filter_var($_POST['parts_rack_location_id'],FILTER_SANITIZE_STRING));
$parts_rack_location_name     = input_data(filter_var($_POST['parts_rack_location_name'],FILTER_SANITIZE_STRING));
$parts_warehouse_id           = input_data(filter_var($_POST['parts_warehouse_id'],FILTER_SANITIZE_STRING));

if($parts_rack_location_name == "" || $parts_warehouse_id == "") {
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
$sql  = "SELECT parts_rack_location_name FROM tbl_parts_rack_location WHERE parts_rack_location_name = '".$parts_rack_location_name."' AND client_id = '".$_SESSION['client_id']."' AND parts_rack_location_id <> '".$parts_rack_location_id."' LIMIT 1";
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

$sql   = "UPDATE tbl_parts_rack_location SET parts_rack_location_name = '".$parts_rack_location_name."',parts_warehouse_id='".$parts_warehouse_id."' WHERE parts_rack_location_id = '".$parts_rack_location_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('RACK-LOCATION-UPDATE','RACK','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'update Rack Location Data','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({title: "Data updated!",text: "",type: "success"}).then(function() {window.location = "inventory-rack.php";});
</script>