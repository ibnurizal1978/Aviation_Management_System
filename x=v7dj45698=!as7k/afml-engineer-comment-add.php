<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$afml_id    = input_data(filter_var($_POST['afml_id'],FILTER_SANITIZE_STRING));
$afml_page_no     = input_data(filter_var($_POST['afml_page_no'],FILTER_SANITIZE_STRING));
$afml_notes_engineer     = input_data(filter_var($_POST['afml_notes_engineer'],FILTER_SANITIZE_STRING));
$component_change     = input_data(filter_var($_POST['component_change'],FILTER_SANITIZE_STRING));

if($afml_notes_engineer =='') {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill comment'
  })
</script>
<?php
  //header('location:afml-engineer.php?act=99dvi59&ntf=29dvi59-'.$afml_id.'-94dfvj!sdf-349ffuaw');
  exit(); 
}


if(@$_FILES['upload_file']['name'] != ''){
  $temp = explode(".", $_FILES["upload_file"]["name"]);
  $target_dir = "../uploads/afml-engineer-upload/";
  @$newfilename = date('dmyhis').round(microtime(true)) . '.' . end($temp);
  $target_file = $target_dir.$newfilename;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  //file harus < 1MB
  if ($_FILES["upload_file"]["size"] > 1000001) {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'File size not more than 1MB'
  })
</script>
<?php
  exit();
  }

  //file yang boleh diupload hanya PDF, WORD, XLS, JPG, PNG, GIF, PPT
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "pdf" && $imageFileType != "gif" && $imageFileType != "jpeg") {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill comment'
  })
</script>
<?php
  //header('location:afml-engineer.php?act=09dvi59&ntf=29dvi59-'.$afml_id.'-94dfvj!sdf-349ffuaw');
  exit();
  }

  move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file);
  $sql   = "UPDATE tbl_afml_notes SET afml_notes_engineer = '".$afml_notes_engineer."', afml_notes_engineer_files = '".@$newfilename."', component_change = '".$component_change."', engineer_created_date = UTC_TIMESTAMP(), engineer_user_id = '".$_SESSION['user_id']."' WHERE afml_page_no = '".$afml_page_no."' LIMIT 1";  
}else{
  $sql   = "UPDATE tbl_afml_notes SET afml_notes_engineer = '".$afml_notes_engineer."', component_change = '".$component_change."', engineer_created_date = UTC_TIMESTAMP(), engineer_user_id = '".$_SESSION['user_id']."' WHERE afml_page_no = '".$afml_page_no."' LIMIT 1";
}
//echo $sql;
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AFML-ENGINEER-UPDATE','AFML','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'Update AFML by engineer','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);

//header('location:afml-engineer.php?act=29dvi59&ntf=29dvi59-'.$afml_id.'-94dfvj!sdf-349ffuaw');
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>