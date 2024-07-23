<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";


$afml_id     = input_data(filter_var($_POST['afml_id'],FILTER_SANITIZE_STRING));
$afml_date     = input_data(filter_var($_POST['afml_date'],FILTER_SANITIZE_STRING));
$afml_page_no     = input_data(filter_var($_POST['afml_page_no'],FILTER_SANITIZE_STRING));
$aircraft_reg_code     = input_data(filter_var($_POST['aircraft_reg_code'],FILTER_SANITIZE_STRING));
$afml_captain_user_id    = input_data(filter_var($_POST['afml_captain_user_id'],FILTER_SANITIZE_STRING));
$afml_copilot_user_id    = input_data(filter_var($_POST['afml_copilot_user_id'],FILTER_SANITIZE_STRING));
$afml_engineer_on_board_user_id    = input_data(filter_var($_POST['afml_engineer_on_board_user_id'],FILTER_SANITIZE_STRING));

//etcm
$ectm_time    = input_data(filter_var($_POST['ectm_time'],FILTER_SANITIZE_STRING));
$ectm_altitude    = input_data(filter_var($_POST['ectm_altitude'],FILTER_SANITIZE_STRING));
$ectm_ias    = input_data(filter_var($_POST['ectm_ias'],FILTER_SANITIZE_STRING));
$ectm_tq    = input_data(filter_var($_POST['ectm_tq'],FILTER_SANITIZE_STRING));
$ectm_itt    = input_data(filter_var($_POST['ectm_itt'],FILTER_SANITIZE_STRING));
$ectm_ng    = input_data(filter_var($_POST['ectm_ng'],FILTER_SANITIZE_STRING));
$ectm_np    = input_data(filter_var($_POST['ectm_np'],FILTER_SANITIZE_STRING));
$ectm_ff    = input_data(filter_var($_POST['ectm_ff'],FILTER_SANITIZE_STRING));
$ectm_oil_temp    = input_data(filter_var($_POST['ectm_oil_temp'],FILTER_SANITIZE_STRING));
$ectm_oil_press    = input_data(filter_var($_POST['ectm_oil_press'],FILTER_SANITIZE_STRING));
$ectm_oat    = input_data(filter_var($_POST['ectm_oat'],FILTER_SANITIZE_STRING));

if($afml_page_no == "" || $aircraft_reg_code == "") {
  header('location:afml-change.php?ntf=1-'.$afml_id.'-94dfvj!sdf-349ffuaw');
  exit(); 
}

//date
$afml_date_y   = substr($afml_date,6,4);
$afml_date_m   = substr($afml_date,3,2);
$afml_date_d   = substr($afml_date,0,2);
$afml_date_f   = $afml_date_y.'-'.$afml_date_m.'-'.$afml_date_d;


$sql  = "SELECT afml_page_no FROM tbl_afml WHERE client_id = '".$_SESSION['client_id']."' AND afml_id <> '".$afml_id."' AND afml_page_no = '".$afml_page_no."' ORDER BY afml_id DESC LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
if($row['afml_page_no'] == $afml_page_no) {
  header('location:afml-change.php?ntf=2-'.$afml_id.'-94dfvj!sdf-349ffuaw');
  exit(); 
}

//cari data lama AFML yang akan diubah
$sql1   = "SELECT * FROM tbl_afml WHERE afml_id = '".$afml_id."' LIMIT 1";
$h1     = mysqli_query($conn, $sql1);
$row1   = mysqli_fetch_assoc($h1);

//cari atribut aircraft
$sql_master  = "SELECT aircraft_serial_number,aircraft_ac_total_hrs,aircraft_ac_total_ldg,aircraft_eng_1_total_hrs,aircraft_eng_1_total_ldg,aircraft_eng_2_total_hrs,aircraft_eng_2_total_ldg,aircraft_prop_total_hrs FROM tbl_aircraft_master WHERE aircraft_reg_code = '".$aircraft_reg_code."' LIMIT 1";
$h_master    = mysqli_query($conn,$sql_master);
$row_master  = mysqli_fetch_assoc($h_master);
$aircraft_type = explode('-', $row_master['aircraft_serial_number']);

//add captain name
$sql_capt    = "SELECT  full_name FROM tbl_user WHERE user_id = '".$afml_captain_user_id."'";
$h_capt      = mysqli_query($conn,$sql_capt);
$row_capt    = mysqli_fetch_assoc($h_capt);

//add copilot name
$sql_copil   = "SELECT  full_name FROM tbl_user WHERE user_id = '".$afml_copilot_user_id."'";
$h_copil     = mysqli_query($conn,$sql_copil);
$row_copil   = mysqli_fetch_assoc($h_copil);

//add eob name
$sql_eob    = "SELECT  full_name FROM tbl_user WHERE user_id = '".$afml_engineer_on_board_user_id."'";
$h_eob      = mysqli_query($conn,$sql_eob);
$row_eob    = mysqli_fetch_assoc($h_eob);


$sql   = "UPDATE tbl_afml SET afml_page_no='".$afml_page_no."',afml_date='".$afml_date_f."',  afml_captain = '".$row_capt['full_name']."', afml_copilot = '".$row_copil['full_name']."', afml_engineer_on_board = '".$row_eob['full_name']."', afml_captain_user_id = '".$afml_captain_user_id."', afml_copilot_user_id = '".$afml_copilot_user_id."', afml_engineer_on_board_user_id = '".$afml_engineer_on_board_user_id."', ectm_time='".$ectm_time."',ectm_altitude='".$ectm_altitude."',ectm_ias='".$ectm_ias."',ectm_tq='".$ectm_tq."',ectm_itt='".$ectm_itt."',ectm_ng='".$ectm_ng."',ectm_np='".$ectm_np."',ectm_ff='".$ectm_ff."',ectm_oil_temp='".$ectm_oil_temp."',ectm_oil_press='".$ectm_oil_press."',ectm_oat='".$ectm_oat."' WHERE afml_id = '".$afml_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
mysqli_query($conn,$sql);


//insert ke table log AFML
              /*aircraft_reg_code_old, aircraft_reg_code_new,
              aircraft_serial_number_old, aircraft_serial_number_new,
              afml_type_old, afml_type_new,
              afml_captain_user_id_old, afml_captain_user_id_new,
              afml_copilot_user_id_old, afml_copilot_user_id_new,
              afml_engineer_on_board_user_id_old, afml_engineer_on_board_user_id_new,*/
$sql2     = "INSERT INTO tbl_afml_log
              (afml_log_date, user_id, client_id, afml_id,
              afml_page_no_old, afml_page_no_new,
              afml_date_old, afml_date_new,
              ectm_time_old, ectm_time_new,
              ectm_altitude_old, ectm_altitude_new,
              ectm_ias_old, ectm_ias_new,
              ectm_tq_old, ectm_tq_new,
              ectm_itt_old, ectm_itt_new,
              ectm_ng_old, ectm_ng_new,
              ectm_np_old, ectm_np_new,
              ectm_ff_old, ectm_ff_new,
              ectm_oil_temp_old, ectm_oil_temp_new,
              ectm_oil_press_old, ectm_oil_press_new,
              ectm_oat_old, ectm_oat_new)
              VALUES
              (UTC_TIMESTAMP(), '".$_SESSION['user_id']."', '".$_SESSION['client_id']."', '".$afml_id."',
              '".$row1['afml_page_no']."','".$afml_page_no."',
              '".$row1['afml_date']."','".$afml_date_f."',
              '".$row1['ectm_time']."','".$ectm_time."',
              '".$row1['ectm_altitude']."','".$ectm_altitude."',
              '".$row1['ectm_ias']."','".$ectm_ias."',
              '".$row1['ectm_tq']."','".$ectm_tq."',
              '".$row1['ectm_itt']."','".$ectm_itt."',
              '".$row1['ectm_ng']."','".$ectm_ng."',
              '".$row1['ectm_np']."','".$ectm_np."',
              '".$row1['ectm_ff']."','".$ectm_ff."',
              '".$row1['ectm_oil_temp']."','".$ectm_oil_temp."',
              '".$row1['ectm_oil_press']."','".$ectm_oil_press."',
              '".$row1['ectm_oat']."','".$ectm_oat."')";
$h2       = mysqli_query($conn, $sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AFML-EDIT','AFML','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'edit AFML data for page no: $afml_page_no','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
header('location:afml-change.php?ntf=999-'.$afml_id.'-94dfvj!sdf-349ffuaw');