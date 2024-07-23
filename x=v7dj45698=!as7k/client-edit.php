<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$client_id          = input_data(filter_var($_POST['client_id'],FILTER_SANITIZE_STRING));
$client_name        = input_data(filter_var($_POST['client_name'],FILTER_SANITIZE_STRING));
$client_code        = input_data(filter_var($_POST['client_code'],FILTER_SANITIZE_STRING));
$client_email_address = input_data(filter_var($_POST['client_email_address'],FILTER_SANITIZE_STRING));
$client_address     = input_data(filter_var($_POST['client_address'],FILTER_SANITIZE_STRING));
$client_phone       = input_data(filter_var($_POST['client_phone'],FILTER_SANITIZE_STRING));
$client_package     = input_data(filter_var($_POST['client_package'],FILTER_SANITIZE_STRING));
$client_active_status = input_data(filter_var($_POST['client_active_status'],FILTER_SANITIZE_STRING));

if($client_name == "" || $client_code == "") {
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

//apakah ada duplikat name utk client id ini?
$sql  = "SELECT client_name FROM tbl_client WHERE client_name = '".$client_name."' AND client_id <> '".$client_id."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate client name'
  })
  </script>
<?php
  exit(); 
}

//apakah ada duplikat name utk client id ini?
$sql  = "SELECT client_code FROM tbl_client WHERE client_code = '".$client_code."' AND client_id <> '".$client_id."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate client code'
  })
  </script>
<?php
  exit(); 
}

$sql2   = "UPDATE tbl_client SET client_name='".$client_name."',client_code='".$client_code."',client_email_address = '".$client_email_address."',client_phone = '".$client_phone."', client_address = '".$client_address."',client_package = '".$client_package."',client_active_status = '".$client_active_status."'  WHERE client_id = '".$client_id."' LIMIT 1";
mysqli_query($conn,$sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('CLIENT-EDIT','CLIENT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'edit client data for client_id: $client_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);

//header('location:user.php?ntf=r1029wkw-89t4hf34675dfoitrj!fn98s3');
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>