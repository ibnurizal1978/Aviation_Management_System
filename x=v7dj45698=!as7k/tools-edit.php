<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$tools_id                 = input_data(filter_var($_POST['tools_id'],FILTER_SANITIZE_STRING));
$tools_name               = input_data(filter_var($_POST['tools_name'],FILTER_SANITIZE_STRING));
$tools_description        = input_data(filter_var($_POST['tools_description'],FILTER_SANITIZE_STRING));
$size                     = input_data(filter_var($_POST['size'],FILTER_SANITIZE_STRING));
$part_number              = input_data(filter_var($_POST['part_number'],FILTER_SANITIZE_STRING));
$serial_number            = input_data(filter_var($_POST['serial_number'],FILTER_SANITIZE_STRING));
$manufacturer             = input_data(filter_var($_POST['manufacturer'],FILTER_SANITIZE_STRING));
$qty                      = input_data(filter_var($_POST['qty'],FILTER_SANITIZE_STRING));
$parts_location_id        = input_data(filter_var($_POST['parts_location_id'],FILTER_SANITIZE_STRING));
$tools_type               = input_data(filter_var($_POST['tools_type'],FILTER_SANITIZE_STRING));
$notes    = input_data(filter_var($_POST['notes'],FILTER_SANITIZE_STRING));
$last_calibration_date    = input_data(filter_var($_POST['last_calibration_date'],FILTER_SANITIZE_STRING));

if($tools_name=='') {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill Tools Name'
  })
  </script>
<?php
  exit();
}

//date
$last_calibration_date_y   = substr($last_calibration_date,6,4);
$last_calibration_date_m   = substr($last_calibration_date,3,2);
$last_calibration_date_d   = substr($last_calibration_date,0,2);
$last_calibration_date_f   = $last_calibration_date_y.'-'.$last_calibration_date_m.'-'.$last_calibration_date_d;


$sql2   = "UPDATE tbl_tools SET 
tools_name='".$tools_name."',
tools_description='".$tools_description."',
size='".$size."',
part_number='".$part_number."',
serial_number='".$serial_number."',
manufacturer='".$manufacturer."',
qty='".$qty."',
parts_location_id='".$parts_location_id."',
notes='".$notes."',
tools_type='".$tools_type."',
last_calibration_date='".$last_calibration_date_f."',
next_calibration_date=DATE_ADD('".$last_calibration_date_f."', INTERVAL 12 MONTH)
WHERE client_id = '".$_SESSION['client_id']."' 
AND tools_id = '".$tools_id."' LIMIT 1";
mysqli_query($conn,$sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('TOOLS-EDIT','TOOLS','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'edit data for tools ID: $tools_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>