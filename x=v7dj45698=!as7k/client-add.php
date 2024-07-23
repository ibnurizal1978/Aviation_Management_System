<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$client_name        = input_data(filter_var($_POST['client_name'],FILTER_SANITIZE_STRING));
$client_code        = input_data(filter_var($_POST['client_code'],FILTER_SANITIZE_STRING));
$client_email_address = input_data(filter_var($_POST['client_email_address'],FILTER_SANITIZE_STRING));
$client_address     = input_data(filter_var($_POST['client_address'],FILTER_SANITIZE_STRING));
$client_phone       = input_data(filter_var($_POST['client_phone'],FILTER_SANITIZE_STRING));
$client_package     = input_data(filter_var($_POST['client_package'],FILTER_SANITIZE_STRING));

if($client_name == "" || $client_code == "" || $client_address == "" || $client_email_address == "") {
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
$sql  = "SELECT client_name FROM tbl_client WHERE client_name = '".$client_name."' LIMIT 1";
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
$sql  = "SELECT client_code FROM tbl_client WHERE client_code = '".$client_code."' LIMIT 1";
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

$sql2   = "INSERT INTO tbl_client (client_name,client_code,client_email_address,client_phone,client_address,client_package,client_active_status,client_created_date) VALUES ('".$client_name."','".$client_code."','".$client_email_address."','".$client_phone."','".$client_address."','".$client_package."','N',UTC_TIMESTAMP())";
mysqli_query($conn,$sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('CLIENT-ADD','CLIENT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new client: $client_name','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);

//email
$to           = 'ibnurizal@gmail.com';
$subject      = "Need Approval: New Client";
$htmlContent  = "Dear Director, There is a new client data inserted into our system. \r\nClient name: ".$client_name."\r\n. Please login to our system to approve or reject.";
$headers      = "MIME-Version: 1.0" . "\r\n";
$headers      .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers      .= 'From: AMS <no-reply@ams.satukode.com>' . "\r\n";
mail($to,$subject,$htmlContent,$headers);

?>
<script type="text/javascript">
Swal.fire(
  '',
  'New client created. Waiting for Director to approve',
  'success'
)
</script>