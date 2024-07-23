<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$safety_finding_external_audited_date      = input_data(filter_var($_POST['safety_finding_external_audited_date'],FILTER_SANITIZE_STRING));
$safety_finding_external_location          = input_data(filter_var($_POST['safety_finding_external_location'],FILTER_SANITIZE_STRING));
$safety_finding_external_department_id     = input_data(filter_var($_POST['safety_finding_external_department_id'],FILTER_SANITIZE_STRING));
$safety_finding_external_target_date          = input_data(filter_var($_POST['safety_finding_external_target_date'],FILTER_SANITIZE_STRING));
$safety_finding_external_auditor_name         = input_data(filter_var($_POST['safety_finding_external_auditor_name'],FILTER_SANITIZE_STRING));

if($safety_finding_external_audited_date == "" || $safety_finding_external_department_id == "" || $safety_finding_external_target_date == "" || $safety_finding_external_location == "") {
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
$safety_finding_external_audited_date_y   = substr($safety_finding_external_audited_date,6,4);
$safety_finding_external_audited_date_m   = substr($safety_finding_external_audited_date,3,2);
$safety_finding_external_audited_date_d   = substr($safety_finding_external_audited_date,0,2);
$safety_finding_external_audited_date_f   = $safety_finding_external_audited_date_y.'-'.$safety_finding_external_audited_date_m.'-'.$safety_finding_external_audited_date_d;

$safety_finding_external_target_date_y   = substr($safety_finding_external_target_date,6,4);
$safety_finding_external_target_date_m   = substr($safety_finding_external_target_date,3,2);
$safety_finding_external_target_date_d   = substr($safety_finding_external_target_date,0,2);
$safety_finding_external_target_date_f   = $safety_finding_external_target_date_y.'-'.$safety_finding_external_target_date_m.'-'.$safety_finding_external_target_date_d;


$temp = explode(".", $_FILES["upload_file"]["name"]);
$target_dir = "../uploads/safety/";
$newfilename = date('dmyhis').round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir.$newfilename;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

//file harus < 3MB
if ($_FILES["upload_file"]["size"] > 3000001) {
?>
  <script type="text/javascript">
  Swal.fire({ type: 'error', text: 'Maximum file size is 3MB' })
  </script>
<?php 
exit();
}

//file yang boleh diupload hanya PDF, WORD, XLS, JPG, PNG, GIF, PPT
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "doc" && $imageFileType != "xlsx" && $imageFileType != "docx" && $imageFileType != "gif" && $imageFileType != "jpeg") {
?>
  <script type="text/javascript">
  Swal.fire({ type: 'error', text: 'Allowed file type: JPG, GIF, PNG, PDF, DOC, XLS' })
  </script>
<?php
exit();
}

move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file);

$sql   = "INSERT INTO tbl_safety_finding_external(safety_finding_external_audited_date,safety_finding_external_location,safety_finding_external_department_id,safety_finding_external_target_date,safety_finding_external_file,safety_finding_external_auditor_name,created_date,user_id,client_id) VALUES ('".$safety_finding_external_audited_date_f."','".$safety_finding_external_location."','".$safety_finding_external_department_id."','".$safety_finding_external_target_date_f."','".$newfilename."','".$safety_finding_external_auditor_name."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('SAFETY-FINDING-EXTERNAL-ADD','SAFETY-FINDING','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new safety external','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>