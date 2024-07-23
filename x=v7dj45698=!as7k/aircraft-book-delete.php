<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$aircraft_master_id    = $ntf[1];
$aircraft_book_id      = $ntf[2];

if($aircraft_book_id == "" || $aircraft_master_id == "") {
  header('location:aircraft-book.php?act=79dvi59g&ntf=29dvi59-'.$aircraft_master_id.'-'.$aircraft_book_id.'-94dfvj!sdf-349ffuaw');
  exit();
}


$sql2   = "DELETE FROM tbl_aircraft_book WHERE aircraft_book_id =  '".$aircraft_book_id."' AND client_id = '".$_SESSION['user_id']."' AND '".$_SESSION['client_id']."'";
mysqli_query($conn,$sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AIRCRAFT-ADD','AIRCRAFT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'deleting AFML book','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);

header('location:aircraft-book.php?act=a29dvi59&ntf=29dvi59-'.$aircraft_master_id.'-'.$aircraft_book_id.'-94dfvj!sdf-349ffuaw');