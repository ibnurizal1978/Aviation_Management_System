<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$project_master_id   = input_data(filter_var($_POST['project_master_id'],FILTER_SANITIZE_STRING));
$engineer_user_id   = input_data(filter_var($_POST['engineer_user_id'],FILTER_SANITIZE_STRING));
$deposit     		= input_data(filter_var($_POST['deposit'],FILTER_SANITIZE_STRING));
$deposit2    = str_replace(',', '', $deposit);
$project_ledger_sn = $_SESSION['client_id'].date('dmyhis').round(microtime(true));

if($engineer_user_id=='' || $deposit=='') {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill all form'
  })
  </script>
<?php
  exit();
}

$sql  = "UPDATE tbl_project_engineer_team SET deposit = deposit+'".$deposit2."' WHERE engineer_user_id = '".$engineer_user_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
mysqli_query($conn,$sql);

$sql_balance 	= "SELECT project_ledger_last_balance FROM tbl_project_ledger WHERE engineer_user_id = '".$engineer_user_id."' AND client_id = '".$_SESSION['client_id']."' AND project_master_id = '".$project_master_id."' LIMIT 1";
$h_balance 		= mysqli_query($conn,$sql_balance);
$row_balance 	= mysqli_fetch_assoc($h_balance);
$last_balance 	= $row_balance['project_ledger_last_balance']+$deposit2;

$sql2  = "INSERT INTO tbl_project_ledger(project_master_id,project_ledger_date,project_ledger_sn,engineer_user_id,project_ledger_type,project_ledger_amount,project_ledger_last_balance,created_date,user_id,client_id) VALUES ('".$project_master_id."',UTC_TIMESTAMP(),'".$project_ledger_sn."','".$engineer_user_id."','DEPOSIT','".$deposit2."','".$last_balance."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql2);
 
//insert ke table log user
$sql5   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('PETTY-CASH-ADD','PETTY-CASH','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'Add petty cash','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql5);

?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>