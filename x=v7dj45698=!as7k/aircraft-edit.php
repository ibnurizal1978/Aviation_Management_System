<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$aircraft_master_id   = input_data(filter_var($_POST['aircraft_master_id'],FILTER_SANITIZE_STRING));
$aircraft_reg_no      = input_data(filter_var($_POST['aircraft_reg_no'],FILTER_SANITIZE_STRING));
$aircraft_reg_code    = input_data(filter_var($_POST['aircraft_reg_code'],FILTER_SANITIZE_STRING));
$aircraft_type        = input_data(filter_var($_POST['aircraft_type'],FILTER_SANITIZE_STRING));
$aircraft_type_id     = input_data(filter_var($_POST['aircraft_type_id'],FILTER_SANITIZE_STRING));
$aircraft_serial_number    = input_data(filter_var($_POST['aircraft_serial_number'],FILTER_SANITIZE_STRING));
$engine_part_number    = input_data(filter_var($_POST['engine_part_number'],FILTER_SANITIZE_STRING));
$engine_serial_number  = input_data(filter_var($_POST['engine_serial_number'],FILTER_SANITIZE_STRING));
$prop_part_number      = input_data(filter_var($_POST['prop_part_number'],FILTER_SANITIZE_STRING));
$prop_serial_number    = input_data(filter_var($_POST['prop_serial_number'],FILTER_SANITIZE_STRING));
$manufacture_date      = input_data(filter_var($_POST['manufacture_date'],FILTER_SANITIZE_STRING));
$delivery_date          = input_data(filter_var($_POST['delivery_date'],FILTER_SANITIZE_STRING));

if($aircraft_reg_no == "" || $aircraft_reg_code == "" || $aircraft_type == "") {
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
$manufacture_date_y   = substr($manufacture_date,6,4);
$manufacture_date_m   = substr($manufacture_date,3,2);
$manufacture_date_d   = substr($manufacture_date,0,2);
$manufacture_date_f   = $manufacture_date_y.'-'.$manufacture_date_m.'-'.$manufacture_date_d;

//apakah ada duplikat?
$sql  = "SELECT aircraft_reg_no FROM tbl_aircraft_master WHERE aircraft_reg_no = '".$aircraft_reg_no."' AND aircraft_master_id <> '".$aircraft_master_id."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate registration number'
  })
  </script>
<?php
  exit(); 
}

//apakah ada duplikat?
$sql  = "SELECT aircraft_reg_code FROM tbl_aircraft_master WHERE aircraft_reg_code = '".$aircraft_reg_code."' AND aircraft_master_id <> '".$aircraft_master_id."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate aircraft code'
  })
  </script>
<?php
  exit(); 
}
/*
$temp = explode(".", $_FILES["upload_file"]["name"]);
$target_dir = "../uploads/aircraft-brochure/";
$newfilename = date('dmyhis').round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir.$newfilename;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

//file harus < 1MB
if ($_FILES["upload_file"]["size"] > 1000001) {
?>
  <script type="text/javascript">
  Swal.fire({ type: 'error', text: 'Maximum file size is 1MB' })
  </script>
<?php 
exit();
}

//file yang boleh diupload hanya PDF, WORD, XLS, JPG, PNG, GIF, PPT
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "doc" && $imageFileType != "xlsx" && $imageFileType != "docx" && $imageFileType != "ppt" && $imageFileType != "pptx" && $imageFileType != "gif" && $imageFileType != "jpeg") {
?>
  <script type="text/javascript">
  Swal.fire({ type: 'error', text: 'Allowed file type: JPG, GIF, PNG, PDF' })
  </script>
<?php
exit();
}

move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file);
*/
//hapus image sebelumnya
$sql_d    = "SELECT aircraft_master_brochure FROM tbl_aircraft_master WHERE aircraft_master_id = '".$aircraft_master_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h_d      = mysqli_query($conn,$sql_d);
$row_d    = mysqli_fetch_assoc($h_d);
@unlink($target_dir.$row_d['aircraft_master_brochure']);

$sql2   = "UPDATE tbl_aircraft_master SET aircraft_type_id='".$aircraft_type_id."',aircraft_reg_no='".$aircraft_reg_no."',aircraft_reg_code='".$aircraft_reg_code."',aircraft_type='".$aircraft_type."',aircraft_serial_number='".$aircraft_serial_number."',engine_part_number='".$engine_part_number."',engine_serial_number='".$engine_serial_number."',prop_part_number='".$prop_part_number."',prop_serial_number='".$prop_serial_number."',manufacture_date='".$manufacture_date_f."',delivery_date='".$delivery_date."' WHERE client_id = '".$_SESSION['client_id']."' AND aircraft_master_id = '".$aircraft_master_id."' LIMIT 1";
mysqli_query($conn,$sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AIRCRAFT-EDIT','AIRCRAFT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'edit data for airfract ID: $aircraft_master_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>