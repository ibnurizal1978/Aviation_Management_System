<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";
?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    text: 'Maximum file size is 1MB'
})
</script>
<?php

$temp = explode(".", $_FILES["upload_file"]["name"]);
$target_dir = "../uploads/user/";
$newfilename = date('dmyhis').round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir.$newfilename;
//$target_file = $target_dir . basename($_FILES["upload_file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

//file harus < 1MB
if ($_FILES["upload_file"]["size"] > 1000001) {
?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    text: 'Maximum file size is 1MB'
})
</script>
<?php 
exit();
$uploadOk = 0;
}

//file yang boleh diupload hanya PDF, WORD, XLS, JPG, PNG, GIF, PPT
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif" && $imageFileType != "jpeg") {
?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    text: 'Allowed file type: JPG, GIF, PNG'
})
</script>
<?php
exit();
$uploadOk = 0;
}

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["upload_file"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}


//upload
if (move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file)) {

//hapus file utk user ini
$sql_d    = "SELECT user_photo FROM tbl_user WHERE user_id = '".$_SESSION['user_id']."' LIMIT 1";
$h_d      = mysqli_query($conn,$sql_d);
$row_d    = mysqli_fetch_assoc($h_d);
@unlink($target_dir.$row_d['user_photo']);

//update table user
$sql = "UPDATE tbl_user SET user_photo ='".$newfilename."' WHERE user_id = '".$_SESSION['user_id']."' LIMIT 1";
mysqli_query($conn,$sql);

//masukin ke log
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('USER-PHOTO','USER','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'upload photo','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);

?>
<script type="text/javascript">
swal({text: "Data updated", type: 
"success"}).then(function(){ 
   location.reload();
   }
);
</script>
<?php } else { ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    text: 'Something went wrong. Try again.'
})
</script>
<?php } ?>

