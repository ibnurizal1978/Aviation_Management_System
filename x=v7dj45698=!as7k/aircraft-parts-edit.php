<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$aircraft_parts_id   = input_data(filter_var($_POST['aircraft_parts_id'],FILTER_SANITIZE_STRING));
$item_number      = input_data(filter_var($_POST['item_number'],FILTER_SANITIZE_STRING));
$position    = input_data(filter_var($_POST['position'],FILTER_SANITIZE_STRING));
$description        = input_data(filter_var($_POST['description'],FILTER_SANITIZE_STRING));
$part_number     = input_data(filter_var($_POST['part_number'],FILTER_SANITIZE_STRING));
$serial_number    = input_data(filter_var($_POST['serial_number'],FILTER_SANITIZE_STRING));
$installed_date    = input_data(filter_var($_POST['installed_date'],FILTER_SANITIZE_STRING));
$mth  = input_data(filter_var($_POST['mth'],FILTER_SANITIZE_STRING));
$hrs      = input_data(filter_var($_POST['hrs'],FILTER_SANITIZE_STRING));
$ldg    = input_data(filter_var($_POST['ldg'],FILTER_SANITIZE_STRING));
$installed_date      = input_data(filter_var($_POST['installed_date'],FILTER_SANITIZE_STRING));

//date
$installed_date_y   = substr($installed_date,6,4);
$installed_date_m   = substr($installed_date,3,2);
$installed_date_d   = substr($installed_date,0,2);
$installed_date_f   = $installed_date_y.'-'.$installed_date_m.'-'.$installed_date_d;


$sql2   = "UPDATE tbl_aircraft_parts SET item_number='".$item_number."',position='".$position."',description='".$description."',part_number='".$part_number."',serial_number='".$serial_number."',mth='".$mth."',hrs='".$hrs."',ldg='".$ldg."',installed_date='".$installed_date_f."' WHERE client_id = '".$_SESSION['client_id']."' AND aircraft_parts_id = '".$aircraft_parts_id."' LIMIT 1";
mysqli_query($conn,$sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AIRCRAFT-PARTS-EDIT','AIRCRAFT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'edit parts data for parts ID: $aircraft_parts_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>