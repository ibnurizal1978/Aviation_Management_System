<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";
if($ntf[1]=='act') {
  $table    = 'tbl_airnav_terminal';
  $column   = 'airnav_terminal_waiver_status';
  $id       = 'airnav_terminal_id';
  $info     = 'AIRNAV-TERMINAL-WAIVER';
  $module   = 'AIRNAV-TERMINAL';
  $url      = 'airnav-check-terminal.php';
}else{
  $table    = 'tbl_airnav_invoice';
  $column   = 'airnav_invoice_waiver_status'; 
  $id       = 'airnav_invoice_id';
  $info     = 'AIRNAV-INVOICE-WAIVER';
  $module   = 'AIRNAV-INVOICE';
  $url      = 'airnav-check-invoice.php';
}

$sql2 = "UPDATE $table SET $column = 1 WHERE $id = $ntf[0] LIMIT 1";
//echo $sql2;
mysqli_query($conn,$sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('".$info."','".$module."','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'WAIVER airnav terminal','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);

header('location: '.$url);
?>