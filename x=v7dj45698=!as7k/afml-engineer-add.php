<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$afml_id          = input_data(filter_var($_POST['afml_id'],FILTER_SANITIZE_STRING));
$afml_detail_id     = input_data(filter_var($_POST['afml_detail_id'],FILTER_SANITIZE_STRING));
$afml_page_no     = input_data(filter_var($_POST['afml_page_no'],FILTER_SANITIZE_STRING));
$afml_fuel_rem     = input_data(filter_var($_POST['afml_fuel_rem'],FILTER_SANITIZE_STRING));
$afml_fuel_uplift   = input_data(filter_var($_POST['afml_fuel_uplift'],FILTER_SANITIZE_STRING));
$afml_fuel_total    = input_data(filter_var($_POST['afml_fuel_total'],FILTER_SANITIZE_STRING));
$afml_fuel_date    = input_data(filter_var($_POST['afml_fuel_date'],FILTER_SANITIZE_STRING));
$afml_added_oil    = input_data(filter_var($_POST['afml_added_oil'],FILTER_SANITIZE_STRING));
$afml_added_hyd    = input_data(filter_var($_POST['afml_added_hyd'],FILTER_SANITIZE_STRING));
$afml_receipt_no   = input_data(filter_var($_POST['afml_receipt_no'],FILTER_SANITIZE_STRING));
$afml_fuel_location   = input_data(filter_var($_POST['afml_fuel_location'],FILTER_SANITIZE_STRING));

if($afml_fuel_location == "") {
  header('location:afml-engineer.php?act=79dvi59g&ntf=29dvi59-'.$afml_id.'-94dfvj!sdf-349ffuaw');
  exit(); 
}

if($afml_receipt_no <> "" && $afml_fuel_date == "") {
  header('location:afml-engineer.php?act=70dvi59&ntf=29dvi59-'.$afml_id.'-94dfvj!sdf-349ffuaw');
  exit(); 
}

$afml_fuel_date_y   = substr($afml_fuel_date,6,4);
$afml_fuel_date_m   = substr($afml_fuel_date,3,2);
$afml_fuel_date_d   = substr($afml_fuel_date,0,2);
$afml_fuel_date_f   = $afml_fuel_date_y.'-'.$afml_fuel_date_m.'-'.$afml_fuel_date_d;

//apakah ada duplikat?
$sql  = "SELECT afml_receipt_no FROM tbl_afml_detail WHERE client_id = '".$_SESSION['client_id']."' AND afml_receipt_no<>'' AND afml_receipt_no = '".$afml_receipt_no."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
  header('location:afml-engineer.php?act=79dvi59&ntf=29dvi59-'.$afml_id.'-94dfvj!sdf-349ffuaw');
  exit(); 
}



if(@$_FILES['upload_file']['name'] != ''){

  $temp = explode(".", $_FILES["upload_file"]["name"]);
  $target_dir = "../uploads/afml-receipt/";
  @$newfilename = date('dmyhis').round(microtime(true)) . '.' . end($temp);
  $target_file = $target_dir.$newfilename;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  //file harus < 1MB
  if ($_FILES["upload_file"]["size"] > 1000001) {
  header('location:afml-engineer.php?act=09dvi59&ntf=29dvi59-'.$afml_id.'-94dfvj!sdf-349ffuaw');
  exit();
  }

  //file yang boleh diupload hanya PDF, WORD, XLS, JPG, PNG, GIF, PPT
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "pdf" && $imageFileType != "gif" && $imageFileType != "jpeg") {
  header('location:afml-engineer.php?act=09dvi59&ntf=29dvi59-'.$afml_id.'-94dfvj!sdf-349ffuaw');
  exit();
  }

  //cari aircraft reg code
  $sql_reg  = "SELECT aircraft_reg_code FROM tbl_afml WHERE afml_id = '".$afml_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
  $h_reg    = mysqli_query($conn,$sql_reg);
  $row_reg  = mysqli_fetch_assoc($h_reg);


  //cari belong to
  $sql_bt  = "SELECT aircraft_belong_to FROM tbl_aircraft_master WHERE aircraft_reg_code = '".$row_reg['aircraft_reg_code']."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
  $h_bt    = mysqli_query($conn,$sql_bt);
  $row_bt  = mysqli_fetch_assoc($h_bt);

  //cari harga avtur dari list master avtur sesuai tanggal isi
  $sql_check_avtur = "SELECT avtur_price,avtur_price_from,avtur_price_to FROM tbl_avtur_price WHERE '".$afml_fuel_date_f."' BETWEEN avtur_price_from AND avtur_price_to LIMIT 1";
  $h_check_avtur   = mysqli_query($conn,$sql_check_avtur);
  $row_check_avtur = mysqli_fetch_assoc($h_check_avtur);
  $harga = $row_check_avtur['avtur_price']*$afml_fuel_uplift;


  //cari perhitungan saldo akhir
  $sql_balance  = "SELECT saldo_akhir FROM tbl_avtur_afml WHERE client_id = '".$_SESSION['client_id']."' ORDER BY avtur_afml_id DESC LIMIT 1";
  $h_balance    = mysqli_query($conn,$sql_balance);
  $row_balance  = mysqli_fetch_assoc($h_balance);
  $last_balance   = $row_balance['saldo_akhir']-$harga;

  
move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file);
  $sql   = "UPDATE tbl_afml_detail SET afml_fuel_rem = '".$afml_fuel_rem."',afml_fuel_uplift = '".$afml_fuel_uplift."', afml_fuel_total= '".$afml_fuel_total."', afml_added_oil = '".$afml_added_oil."', afml_added_hyd = '".$afml_added_hyd."', afml_receipt_no = '".$afml_receipt_no."', afml_fuel_date = '".$afml_fuel_date_f."',  fuel_attachment = '".@$newfilename."', engineer_created_date = UTC_TIMESTAMP(), engineer_user_id = '".$_SESSION['user_id']."' WHERE afml_detail_id = '".$afml_detail_id."' LIMIT 1";

  $trx_id = $_SESSION['client_id'].date('dmyhis').round(microtime(true));
  $sql_avtur = "INSERT INTO tbl_avtur_afml (afml_id, afml_detail_id, afml_amount, afml_fuel_date, fuel_attachment, iata_code, aircraft_reg_code, afml_price, transaction_type, saldo_awal, saldo_akhir, trx_id, transaction_belong_to, created_date, afml_receipt_no, user_id, full_name, client_id) VALUES ('".$afml_id."','".$afml_detail_id."','".$afml_fuel_uplift."','".$afml_fuel_date_f."','".@$newfilename."','".$afml_fuel_location."','".$row_reg['aircraft_reg_code']."','".$harga."','D','".$row_balance['saldo_akhir']."','".$last_balance."','".$trx_id."','".$row_bt['aircraft_belong_to']."',UTC_TIMESTAMP(),'".$afml_receipt_no."','".$_SESSION['user_id']."','".$_SESSION['full_name']."','".$_SESSION['client_id']."')";
  mysqli_query($conn,$sql_avtur);
  //echo $sql_avtur;
  
}else{
  $sql   = "UPDATE tbl_afml_detail SET afml_fuel_rem = '".$afml_fuel_rem."',afml_fuel_uplift = '".$afml_fuel_uplift."', afml_fuel_total= '".$afml_fuel_total."', afml_added_oil = '".$afml_added_oil."', afml_added_hyd = '".$afml_added_hyd."', afml_receipt_no = '".$afml_receipt_no."', engineer_created_date = UTC_TIMESTAMP(), engineer_user_id = '".$_SESSION['user_id']."' WHERE afml_detail_id = '".$afml_detail_id."' LIMIT 1";
}
//mysqli_query($conn,$sql);


//jika receipt no diisi akan menghitung cost avtur
//$sql2   = "INSERT INTO tbl_afml_notes (afml_page_no,afml_notes_engineer,engineer_user_id,engineer_created_date,client_id) VALUES ('".$afml_page_no."','".$afml_notes_engineer."','".$_SESSION['user_id']."', UTC_TIMESTAMP(),'".$_SESSION['user_id']."')";
//mysqli_query($conn,$sql2);
//SELECT mth,installed_date, DATEDIFF(installed_date,'2019-07-03') as tanggal from tbl_aircraft_parts where DATEDIFF(installed_date,'2019-07-03') < 45 AND mth > 0 order by tanggal desc

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AFML-ENGINEER-UPDATE','AFML','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'Update AFML by engineer','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);

header('location:afml-engineer.php?act=a29dvi59&ntf=29dvi59-'.$afml_id.'-94dfvj!sdf-349ffuaw');
?>