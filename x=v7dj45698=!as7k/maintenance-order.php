<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$aircraft_parts_id     = input_data(filter_var($ntf[1],FILTER_SANITIZE_STRING));
$aircraft_master_id     = input_data(filter_var($ntf[2],FILTER_SANITIZE_STRING));


//cari detail parts
$sql_a  = "SELECT description FROM tbl_aircraft_parts WHERE aircraft_parts_id = '".$aircraft_parts_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h_a    = mysqli_query($conn,$sql_a);
$row_a  = mysqli_fetch_assoc($h_a);

$sql   = "INSERT INTO tbl_order (aircraft_parts_id,description,created_date,requestor_user_id,client_id) VALUES ('".$aircraft_parts_id."','".$row_a['description']."', UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('ORDER-ADD','ORDER','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'order new parts: $aircraft_parts_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
header('location:maintenance-detail.php?act=a29dvi59&ntf=29dvi59-'.$aircraft_master_id.'-94dfvj!sdf-349ffuaw');