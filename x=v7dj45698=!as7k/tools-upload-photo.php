<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$tools_id                 = input_data(filter_var($_POST['tools_id'],FILTER_SANITIZE_STRING));



/*--------------------- file photo ---------------------*/
$temp2 = explode(".", $_FILES["upload_photo"]["name"]);
$target_dir2 = "../uploads/tools-photo/";
$newfilename2 = date('dmyhis').round(microtime(true)) . '.' . end($temp2);
$target_file2 = $target_dir2.$newfilename2;
$imageFileType2 = strtolower(pathinfo($target_file2,PATHINFO_EXTENSION));

//file harus < 1MB
if(!isset($_FILES["upload_photo"])) {
  if ($_FILES["upload_photo"]["size"] > 1000001) {
  ?>
    <script type="text/javascript">
    Swal.fire({ type: 'error', text: 'Maximum file size is 1MB' })
    </script>
  <?php 
  exit();
  }

  //file yang boleh diupload hanya PDF, WORD, XLS, JPG, PNG, GIF, PPT
  if($imageFileType2 != "jpg" && $imageFileType2 != "png" && $imageFileType2 != "gif" && $imageFileType2 != "jpeg") {
  ?>
    <script type="text/javascript">
    Swal.fire({ type: 'error', text: 'Allowed file type: JPG, GIF, PNG' })
    </script>
  <?php
  exit();
  }
}

move_uploaded_file($_FILES["upload_photo"]["tmp_name"], $target_file2);

//hapus image sebelumnya
$sql_d2    = "SELECT tools_photo FROM tbl_tools WHERE tools_id = '".$tools_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h_d2      = mysqli_query($conn,$sql_d2);
$row_d2    = mysqli_fetch_assoc($h_d2);
@unlink($target_dir2.$row_d2['tools_photo']);
/*--------------------- end file photo ---------------------*/

$sql2   = "UPDATE tbl_tools SET tools_photo='".$newfilename2."' WHERE client_id = '".$_SESSION['client_id']."' AND tools_id = '".$tools_id."' LIMIT 1";
mysqli_query($conn,$sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('TOOLS-PHOTO','TOOLS','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'Add photo for tools ID: $tools_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>