<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$aircraft_master_id     = input_data(filter_var($_POST['aircraft_master_id'],FILTER_SANITIZE_STRING));
$aircraft_reg_code      = input_data(filter_var($_POST['aircraft_reg_code'],FILTER_SANITIZE_STRING));
$afml_page_no           = input_data(filter_var($_POST['afml_page_no'],FILTER_SANITIZE_STRING));
$afml_id                = input_data(filter_var($_POST['afml_id'],FILTER_SANITIZE_STRING));
$aircraft_parts_id       = input_data(filter_var($_POST['aircraft_parts_id'],FILTER_SANITIZE_STRING));
$old_part_number        = input_data(filter_var($_POST['old_part_number'],FILTER_SANITIZE_STRING));
$old_serial_number      = input_data(filter_var($_POST['old_serial_number'],FILTER_SANITIZE_STRING));
$new_part_number        = input_data(filter_var($_POST['new_part_number'],FILTER_SANITIZE_STRING));
$new_serial_number      = input_data(filter_var($_POST['new_serial_number'],FILTER_SANITIZE_STRING));
$position               = input_data(filter_var($_POST['position'],FILTER_SANITIZE_STRING));
$description            = input_data(filter_var($_POST['description'],FILTER_SANITIZE_STRING));
$reason                 = input_data(filter_var($_POST['reason'],FILTER_SANITIZE_STRING));

if($old_part_number == "" || $old_serial_number == "" || $new_part_number == "" || $new_serial_number == "") {
  header('location:afml-component-change.php?act=79dvi59g&ntf=29dvi59-'.$aircraft_reg_code.'-'.$afml_page_no.'-'.$afml_id.'-94dfvj!sdf-349ffuaw');
  exit(); 
}

//serial number old sama new sama? harusnya beda
if($old_serial_number == $new_serial_number) {
  header('location:afml-component-change.php?act=79dvi5s9g&ntf=v29dvi59-'.$aircraft_reg_code.'-'.$afml_page_no.'-'.$afml_id.'-94dfvj!sdf-349ffuaw');
  exit(); 
}

//apakah s/n baru udah ada di db?
$sql  = "SELECT serial_number FROM tbl_aircraft_parts WHERE client_id = '".$_SESSION['client_id']."'  AND serial_number = '".$new_serial_number."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
  header('location:afml-component-change.php?act=79djvi59g&ntf=vy29dvi59-'.$aircraft_reg_code.'-'.$afml_page_no.'-'.$afml_id.'-94dfvj!sdf-349ffuaw');
  exit(); 
}

//input ke tbl aircraft parts
$sql   = "UPDATE tbl_aircraft_parts SET part_number = '".$new_part_number."',serial_number = '".$new_serial_number."' WHERE aircraft_parts_id = '".$aircraft_parts_id."' LIMIT 1";

$sql2   = "INSERT INTO tbl_aircraft_parts_history (aircraft_parts_id,position,description,history_type,old_part_number,old_serial_number,new_part_number,new_serial_number,old_location,new_location,afml_page_no,reason,created_date,user_id,full_name,client_id) VALUES ('".$aircraft_parts_id."', '".$position."', '".$description."', 'REPLACEMENT', '".$old_part_number."', '".$old_serial_number."','".$new_part_number."','".$new_serial_number."','','".$aircraft_reg_code."', '".$afml_page_no."', '".$reason."', UTC_TIMESTAMP(), '".$_SESSION['user_id']."', '".$_SESSION['full_name']."', '".$_SESSION['client_id']."')";
//mysqli_query($conn,$sql);
mysqli_query($conn,$sql2);
echo $sql;
echo '<br/>';
echo $sql2;


//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AFML-COMPONENT-CHANGE','AFML','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'Change component for Parts ID:$aircraft_parts_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);

header('location:afml-component-change.php?act=29dvi59&ntf=vy29dvi59-'.$aircraft_reg_code.'-'.$afml_page_no.'-'.$afml_id.'-94dfvj!sdf-349ffuaw');
?>