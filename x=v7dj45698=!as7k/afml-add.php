<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$afml_date     = input_data(filter_var($_POST['afml_date'],FILTER_SANITIZE_STRING));
$afml_page_no     = input_data(filter_var($_POST['afml_page_no'],FILTER_SANITIZE_STRING));
$aircraft_reg_code     = input_data(filter_var($_POST['aircraft_reg_code'],FILTER_SANITIZE_STRING));
$afml_pilot    = input_data(filter_var($_POST['afml_pilot'],FILTER_SANITIZE_STRING));
$afml_copilot    = input_data(filter_var($_POST['afml_copilot'],FILTER_SANITIZE_STRING));
$afml_engineer_on_board    = input_data(filter_var($_POST['afml_engineer_on_board'],FILTER_SANITIZE_STRING));
//$afml_time_preflight    = input_data(filter_var($_POST['afml_time_preflight'],FILTER_SANITIZE_STRING));
//$afml_time_daily           = input_data(filter_var($_POST['afml_time_daily'],FILTER_SANITIZE_STRING));
//$afml_station_preflight          = input_data(filter_var($_POST['afml_station_preflight'],FILTER_SANITIZE_STRING));
//$afml_station_daily      = input_data(filter_var($_POST['afml_station_daily'],FILTER_SANITIZE_STRING));
//$afml_lic_preflight      = input_data(filter_var($_POST['afml_lic_preflight'],FILTER_SANITIZE_STRING));
//$afml_lic_daily    = input_data(filter_var($_POST['afml_lic_daily'],FILTER_SANITIZE_STRING));
$afml_notes_pilot    = input_data(filter_var($_POST['afml_notes_pilot'],FILTER_SANITIZE_STRING));

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

if($afml_page_no == "" || $aircraft_reg_code == "" || $afml_pilot == "") {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill marked (*) forms'
  })
  </script>
<?php
  exit();
}

//date
$afml_date_y   = substr($afml_date,6,4);
$afml_date_m   = substr($afml_date,3,2);
$afml_date_d   = substr($afml_date,0,2);
$afml_date_f   = $afml_date_y.'-'.$afml_date_m.'-'.$afml_date_d;


$sql  = "SELECT afml_page_no FROM tbl_afml WHERE client_id = '".$_SESSION['client_id']."' ORDER BY afml_id DESC LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
if($row['afml_page_no'] == $afml_page_no) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate page number. Maybe you just created it a minute ago?'
  })
  </script>
<?php
  exit(); 
}

//cari atribut aircraft
$sql_master  = "SELECT aircraft_serial_number,aircraft_ac_total_hrs,aircraft_ac_total_ldg,aircraft_eng_1_total_hrs,aircraft_eng_1_total_ldg,aircraft_eng_2_total_hrs,aircraft_eng_2_total_ldg,aircraft_prop_total_hrs FROM tbl_aircraft_master WHERE aircraft_reg_code = '".$aircraft_reg_code."' LIMIT 1";
$h_master    = mysqli_query($conn,$sql_master);
$row_master  = mysqli_fetch_assoc($h_master);

$aircraft_type = explode('-', $row_master['aircraft_serial_number']);

$aircraft_ac_total_hrs    = $row_master['aircraft_ac_total_hrs'];
$aircraft_ac_total_ldg    = $row_master['aircraft_ac_total_ldg'];
$aircraft_eng_1_total_hrs = $row_master['aircraft_eng_1_total_hrs'];
$aircraft_eng_1_total_ldg = $row_master['aircraft_eng_1_total_ldg'];
$aircraft_prop_total_hrs  = $row_master['aircraft_prop_total_hrs'];


//get pilot sign
$sql_b  = "SELECT user_signature FROM tbl_user WHERE user_id = '".$_SESSION['user_id']."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h_b    = mysqli_query($conn,$sql_b);
$row_b  = mysqli_fetch_assoc($h_b);
$afml_pilot_sign = $row_b['user_signature'];
if($afml_pilot_sign == '') {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Failed to add AFML, you don\'t have signature. Please upload signature in profile page'
  })
  </script>
<?php
exit();
}

//file harus < 1MB
if ($_FILES["upload_file"]["size"] > 1097152) {
?>
  <script type="text/javascript">
  Swal.fire({ type: 'error', text: 'File size must less than 1 MB' })
  </script>
<?php 
exit();
}

//file yang boleh diupload hanya PDF, WORD, XLS, JPG, PNG, GIF, PPT
if(!isset($_FILES["upload_file"])) {
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "pdf" && $imageFileType != "gif" && $imageFileType != "jpeg") {
  ?>
    <script type="text/javascript">
    Swal.fire({ type: 'error', text: 'You must upload AFML hard copy. Allowed file type: JPG, PNG, PDF' })
    </script>
  <?php
  exit();
  }

  $temp = explode(".", $_FILES["upload_file"]["name"]);
  $target_dir = "../uploads/afml-form/";
  @$newfilename = $afml_page_no.'-'.date('dmY').'.'.end($temp);
  $target_file = $target_dir.$newfilename;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  function compressImage($source_image, $compress_image) {
    $image_info = getimagesize($source_image);
    if ($image_info['mime'] == 'image/jpeg') {
      $source_image = imagecreatefromjpeg($source_image);
      imagejpeg($source_image, $compress_image, 50);
    } elseif ($image_info['mime'] == 'image/gif') {
      $source_image = imagecreatefromgif($source_image);
      imagegif($source_image, $compress_image, 50);
    } elseif ($image_info['mime'] == 'image/png') {
      $source_image = imagecreatefrompng($source_image);
      imagepng($source_image, $compress_image, 5);
    }
    return $compress_image;
  }

  move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file);
  $source_image = $target_file;
  $image_destination = $target_dir.$newfilename;
  $compress_images = compressImage($source_image, $image_destination);
}

  $sql   = "INSERT INTO tbl_afml (afml_page_no,afml_date,aircraft_reg_code,aircraft_serial_number,afml_type,afml_captain_user_id,afml_copilot_user_id,afml_engineer_on_board_user_id,afml_pilot_sign,brought_fwd_ac_hrs,brought_fwd_ac_ldg,brought_fwd_eng_1_hrs,brought_fwd_eng_1_ldg,brought_fwd_prop_hrs,ectm_time,ectm_altitude,ectm_ias,ectm_tq,ectm_itt,ectm_ng,ectm_np,ectm_ff,ectm_oil_temp,ectm_oil_press,ectm_oat,afml_file,created_date,user_id,client_id) VALUES ('".$afml_page_no."','".$afml_date_f."','".$aircraft_reg_code."','".$row_master['aircraft_serial_number']."','".$aircraft_type[0]."','".$afml_pilot."','".$afml_copilot."','".$afml_engineer_on_board."','".$afml_pilot_sign."','".$aircraft_ac_total_hrs."','".$aircraft_ac_total_ldg."','".$aircraft_eng_1_total_hrs."','".$aircraft_eng_1_total_ldg."','".$aircraft_prop_total_hrs."', '".$ectm_time."','".$ectm_altitude."','".$ectm_ias."','".$ectm_tq."','".$ectm_itt."','".$ectm_ng."','".$ectm_np."','".$ectm_ff."','".$ectm_oil_temp."','".$ectm_oil_press."','".$ectm_oat."','".@$newfilename."', UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
  mysqli_query($conn,$sql);
  $id = mysqli_insert_id($conn);

  $sql   = "INSERT INTO tbl_afml_notes (afml_page_no,afml_notes_pilot,pilot_user_id,pilot_created_date,client_id) VALUES ('".$afml_page_no."','".$afml_notes_pilot."','".$_SESSION['user_id']."', UTC_TIMESTAMP(),'".$_SESSION['user_id']."')";
  mysqli_query($conn,$sql);

  //insert ke table log user
  $sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AFML-ADD','AFML','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new AFML page no: $afml_page_no','".$_SESSION['client_id']."')";
  mysqli_query($conn,$sql_log);
  ?>
  <script type="text/javascript">
    swal({title: "Data updated!",text: "",type: "success"});
  //swal({title: "Data updated!",text: "",type: "success"}).then(function() {window.location = "afml.php";});
  </script>