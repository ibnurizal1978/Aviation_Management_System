<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$client_id      = input_data(filter_var($_POST['client_id'],FILTER_SANITIZE_STRING));
$billing_payment_date    = input_data(filter_var($_POST['billing_payment_date'],FILTER_SANITIZE_STRING));
$billing_payment_source     = input_data(filter_var($_POST['billing_payment_source'],FILTER_SANITIZE_STRING));
$billing_payment_amount    = input_data(filter_var($_POST['billing_payment_amount'],FILTER_SANITIZE_STRING));
$billing_notes    = input_data(filter_var($_POST['billing_notes'],FILTER_SANITIZE_STRING));
$billing_payment_amount2    = str_replace(',', '', $billing_payment_amount);

if($billing_payment_date == "" || $billing_payment_amount == "") {
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
$billing_payment_date_y   = substr($billing_payment_date,6,4);
$billing_payment_date_m   = substr($billing_payment_date,3,2);
$billing_payment_date_d   = substr($billing_payment_date,0,2);
$billing_payment_date_f   = $billing_payment_date_y.'-'.$billing_payment_date_m.'-'.$billing_payment_date_d;

//apakah ada duplikat?
$sql  = "SELECT billing_payment_date FROM tbl_billing WHERE date(billing_payment_date) = '".$billing_payment_date_f."' AND client_id = '".$client_id."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate payment date for this client'
  })
  </script>
<?php
  exit(); 
}

$sql2   = "INSERT INTO tbl_billing (client_id,billing_payment_date,billing_payment_source,billing_payment_amount,user_id,created_date,billing_notes) VALUES ('".$client_id."','".$billing_payment_date_f."','".$billing_payment_source."','".$billing_payment_amount2."','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'".$billing_notes."')";
mysqli_query($conn,$sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('CLIENT-BILLING-ADD','CLIENT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new billing: $client_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>