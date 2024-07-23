<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$safety_finding_id                          = input_data(filter_var($_POST['safety_finding_id'],FILTER_SANITIZE_STRING));
$safety_finding_status  = input_data(filter_var($_POST['safety_finding_status'],FILTER_SANITIZE_STRING));

if($safety_finding_status == "" ) {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please choose status'
  })
</script>
<?php
  exit();
}


#sudo ./certbot-auto --apache -d mnk.ordermatix.id

$sql_u = "UPDATE tbl_safety_finding SET safety_finding_status = 'CLOSED' WHERE safety_finding_id = '".$safety_finding_id."' LIMIT 1";
mysqli_query($conn,$sql_u);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('SAFETY-FINDING-UPDATE','SAFETY-FINDING','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'update safety finding','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>