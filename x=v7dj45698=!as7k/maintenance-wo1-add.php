<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$wo_trx_id          = input_data(filter_var($_POST['wo_trx_id'],FILTER_SANITIZE_STRING));
$wo_subject         = input_data(filter_var($_POST['wo_subject'],FILTER_SANITIZE_STRING));
$wo_number          = input_data(filter_var($_POST['wo_number'],FILTER_SANITIZE_STRING));
$wo_reference       = input_data(filter_var($_POST['wo_reference'],FILTER_SANITIZE_STRING));
$wo_description     = input_data(filter_var($_POST['wo_description'],FILTER_SANITIZE_STRING));
$wo_additional_work = input_data(filter_var($_POST['wo_additional_work'],FILTER_SANITIZE_STRING));
$wo_engineer_in_charge = input_data(filter_var($_POST['wo_engineer_in_charge'],FILTER_SANITIZE_STRING));

if($wo_subject == "" || $wo_trx_id == "") {
  header('location:maintenance-wo1-new.php?q=wiofaakms&ntf='.$wo_trx_id);
  exit();
}

//apakah ada duplikat?
$sql  = "SELECT wo_trx_id FROM tbl_wo WHERE wo_trx_id = '".$wo_trx_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
  header('location:maintenance-wo1-new.php?q=834urnjks&ntf='.$wo_trx_id);
  exit(); 
}

$sql   = "INSERT INTO tbl_wo (wo_trx_id,wo_number,wo_subject,wo_reference,wo_description,wo_additional_work,wo_engineer_in_charge,created_date,user_id,client_id) VALUES ('".$wo_trx_id."','".$wo_number."','".$wo_subject."', '".$wo_reference."','".$wo_description."','".$wo_additional_work."', '".$wo_engineer_in_charge."', UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('WO-ADD','WO','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new WO','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);

header('location:maintenance-wo2-new.php?q=wroienjfsldm&ntf='.$wo_trx_id);
?>