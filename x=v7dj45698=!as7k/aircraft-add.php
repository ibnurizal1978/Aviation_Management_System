<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$aircraft_reg_no      = input_data(filter_var($_POST['aircraft_reg_no'],FILTER_SANITIZE_STRING));
$aircraft_reg_code    = input_data(filter_var($_POST['aircraft_reg_code'],FILTER_SANITIZE_STRING));
$aircraft_type     = input_data(filter_var($_POST['aircraft_type'],FILTER_SANITIZE_STRING));
$aircraft_type_id    = input_data(filter_var($_POST['aircraft_type_id'],FILTER_SANITIZE_STRING));
$aircraft_serial_number    = input_data(filter_var($_POST['aircraft_serial_number'],FILTER_SANITIZE_STRING));
$engine_part_number    = input_data(filter_var($_POST['engine_part_number'],FILTER_SANITIZE_STRING));
$engine_serial_number    = input_data(filter_var($_POST['engine_serial_number'],FILTER_SANITIZE_STRING));
$prop_part_number    = input_data(filter_var($_POST['prop_part_number'],FILTER_SANITIZE_STRING));
$prop_serial_number    = input_data(filter_var($_POST['prop_serial_number'],FILTER_SANITIZE_STRING));
$manufacture_date    = input_data(filter_var($_POST['manufacture_date'],FILTER_SANITIZE_STRING));
$delivery_date    = input_data(filter_var($_POST['delivery_date'],FILTER_SANITIZE_STRING));

if($aircraft_reg_no == "" || $aircraft_reg_code == "" || $aircraft_type == "") {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill marked (*) forms'
  })
  </script>
<?php
  exit();
}

//date
$manufacture_date_y   = substr($manufacture_date,6,4);
$manufacture_date_m   = substr($manufacture_date,3,2);
$manufacture_date_d   = substr($manufacture_date,0,2);
$manufacture_date_f   = $manufacture_date_y.'-'.$manufacture_date_m.'-'.$manufacture_date_d;

//apakah ada duplikat?
$sql  = "SELECT aircraft_reg_no FROM tbl_aircraft_master WHERE aircraft_reg_no = '".$aircraft_reg_no."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate registration number'
  })
  </script>
<?php
  exit(); 
}

//apakah ada duplikat?
$sql  = "SELECT aircraft_reg_code FROM tbl_aircraft_master WHERE aircraft_reg_code = '".$aircraft_reg_code."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate aircraft code'
  })
  </script>
<?php
  exit(); 
}

$sql2   = "INSERT INTO tbl_aircraft_master (aircraft_type_id,aircraft_reg_no,aircraft_reg_code,aircraft_type,aircraft_serial_number,engine_part_number,engine_serial_number,prop_part_number,prop_serial_number,manufacture_date,delivery_date,created_date,client_id) VALUES ('".$aircraft_type_id."','".$aircraft_reg_no."','".$aircraft_reg_code."','".$aircraft_type."','".$aircraft_serial_number."','".$engine_part_number."','".$engine_serial_number."','".$prop_part_number."','".$prop_serial_number."','".$manufacture_date_f."','".$delivery_date."',UTC_TIMESTAMP(),'".$_SESSION['client_id']."')";
mysqli_query($conn,$sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AIRCRAFT-ADD','AIRCRAFT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new aircraft name: $aircraft_reg_no','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>