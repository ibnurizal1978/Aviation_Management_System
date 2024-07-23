<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$afml_id    = input_data(filter_var($_POST['afml_id'],FILTER_SANITIZE_STRING));
$afml_time_preflight    = input_data(filter_var($_POST['afml_time_preflight'],FILTER_SANITIZE_STRING));
$afml_time_daily           = input_data(filter_var($_POST['afml_time_daily'],FILTER_SANITIZE_STRING));
$afml_station_preflight          = input_data(filter_var($_POST['afml_station_preflight'],FILTER_SANITIZE_STRING));
$afml_station_daily      = input_data(filter_var($_POST['afml_station_daily'],FILTER_SANITIZE_STRING));
$afml_lic_preflight      = input_data(filter_var($_POST['afml_lic_preflight'],FILTER_SANITIZE_STRING));
$afml_lic_daily    = input_data(filter_var($_POST['afml_lic_daily'],FILTER_SANITIZE_STRING));

if($afml_lic_daily == "" || $afml_lic_preflight == "") {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill forms'
  })
  </script>
<?php
  exit();
}

//get engineer signature
$sql_b  = "SELECT user_signature_rii_stamp,user_signature_rts_stamp,user_signature FROM tbl_user WHERE user_id = '".$_SESSION['user_id']."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h_b    = mysqli_query($conn,$sql_b);
$row_b  = mysqli_fetch_assoc($h_b);
$user_signature_rii_stamp = $row_b['user_signature_rii_stamp'];
$user_signature_rts_stamp = $row_b['user_signature_rts_stamp'];
$user_signature           = $row_b['user_signature'];
if($user_signature_rts_stamp == '') {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Failed to edit data, engineer does not have signature with stamp.'
  })
  </script>
<?php
exit();
}

//get engineer stamp
$sql_stamp  = "SELECT stamp_no,rii_stamp,inspector_stamp FROM tbl_user_otr WHERE user_id = '".$_SESSION['user_id']."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h_stamp    = mysqli_query($conn,$sql_stamp);
$row_stamp  = mysqli_fetch_assoc($h_stamp);
$stamp_no   = $row_stamp['stamp_no'];
$rii_stamp  = $row_stamp['rii_stamp'];
$inspector_stamp  = $row_stamp['inspector_stamp'];

$sql   = "UPDATE tbl_afml SET afml_time_preflight = '".$afml_time_preflight."',afml_time_daily = '".$afml_time_daily."',afml_station_preflight = '".$afml_station_preflight."',afml_station_daily = '".$afml_station_daily."',afml_lic_preflight = '".$afml_lic_preflight."',afml_lic_daily = '".$afml_lic_daily."',afml_stamp_preflight = '".$user_signature_rts_stamp."',afml_stamp_daily = '".$user_signature_rts_stamp."', user_signature_rts_stamp = '".$user_signature_rts_stamp."', user_signature_rii_stamp = '".$user_signature_rii_stamp."', afml_engineer_sign = '".$user_signature."',afml_engineer_preflight_user_id = '".$_SESSION['user_id']."' WHERE afml_id = '".$afml_id."' LIMIT 1";
//echo $sql;
mysqli_query($conn,$sql);


//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AFML-ADD-ENGINEER-PART','AFML','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add engineer part to AFML ','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>