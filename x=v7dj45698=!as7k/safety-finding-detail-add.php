<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$safety_finding_id                          = input_data(filter_var($_POST['safety_finding_id'],FILTER_SANITIZE_STRING));
$safety_finding_response_corrective_action  = input_data(filter_var($_POST['safety_finding_response_corrective_action'],FILTER_SANITIZE_STRING));

if($safety_finding_response_corrective_action == "" ) {
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


#sudo ./certbot-auto --apache -d mnk.ordermatix.id

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
$sql   = "INSERT INTO tbl_safety_finding_response(safety_finding_id,safety_finding_response_corrective_action,safety_finding_response_file,created_date,created_full_name,user_id,client_id) VALUES ('".$safety_finding_id."','".$safety_finding_response_corrective_action."','".$newfilename."',UTC_TIMESTAMP(),'".$_SESSION['full_name']."','".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql);

$sql_u = "UPDATE tbl_safety_finding SET reply_status = 1 WHERE safety_finding_id = '".$safety_finding_id."' LIMIT 1";
mysqli_query($conn,$sql_u);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('SAFETY-FINDING-RESPONSE','SAFETY-FINDING','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'response to safety finding','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>