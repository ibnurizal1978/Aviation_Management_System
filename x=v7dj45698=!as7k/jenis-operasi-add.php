<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$jenis_operasi_id         = input_data(filter_var($_POST['jenis_operasi_id'],FILTER_SANITIZE_STRING));
$master_iata_id           = input_data(filter_var($_POST['master_iata_id'],FILTER_SANITIZE_STRING));

if($jenis_operasi_id == '' ) {
    header('location:jenis-operasi.php?act=79dvi59g&ntf=101039rqh-94dfvj!sdf-349ffuaw');
  exit();
}

//apakah ada duplikat?
$sql  = "SELECT jenis_operasi_id FROM tbl_jenis_operasi_iata WHERE jenis_operasi_id = '".$jenis_operasi_id."' AND master_iata_id = '".$master_iata_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
echo $sql;
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
    header('location:jenis-operasi.php?act=aasfa&ntf=101039rqh-94dfvj!sdf-349ffuaw');
exit(); 
}

$sql   = "INSERT INTO tbl_jenis_operasi_iata (jenis_operasi_id,master_iata_id,client_id) VALUES ('".$jenis_operasi_id."','".$master_iata_id."','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('JENIS-OPERASI-ADD','JENIS-OPERASI','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new Jenis Operasi','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);

header('location:jenis-operasi.php?act=49856twnaq4&ntf=29dvi59-94dfvj!sdf-349ffuaw');
?>