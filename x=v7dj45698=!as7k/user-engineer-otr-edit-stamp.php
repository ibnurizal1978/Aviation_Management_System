<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$stamp_type 	= input_data(filter_var($_POST['stamp_type'],FILTER_SANITIZE_STRING));
$user_id 		= input_data(filter_var($_POST['user_id'],FILTER_SANITIZE_STRING));
$temp 			= explode(".", $_FILES["upload_file"]["name"]);
$target_dir 	= "../uploads/stamp/";
$newfilename 	= date('dmyhis').round(microtime(true)) . '.' . end($temp);
$target_file 	= $target_dir.$newfilename;
$imageFileType 	= strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

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


//hapus image sebelumnya
if($stamp_type==1) {
	$sql_next 	= "SELECT stamp_no FROM tbl_user_otr where user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
	$h_next 	= mysqli_query($conn,$sql_next);
	$row_next	= mysqli_fetch_assoc($h_next);
	@unlink($target_dir.$row_next['stamp_no']);
	$sql 	= "UPDATE tbl_user_otr SET stamp_no = '".$newfilename."' WHERE user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";

}elseif($stamp_type==2) {
	$sql_next 	= "SELECT rii_stamp FROM tbl_user_otr where user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
	$h_next 	= mysqli_query($conn,$sql_next);
	$row_next	= mysqli_fetch_assoc($h_next);
	@unlink($target_dir.$row_next['rii_stamp']);
	$sql 	= "UPDATE tbl_user_otr SET  rii_stamp = '".$newfilename."' WHERE user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";

}else{
	$sql_next 	= "SELECT inspector_stamp FROM tbl_user_otr where user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
	$h_next 	= mysqli_query($conn,$sql_next);
	$row_next	= mysqli_fetch_assoc($h_next);
	@unlink($target_dir.$row_next['inspector_stamp']);
	$sql 	= "UPDATE tbl_user_otr SET inspector_stamp = '".$newfilename."' WHERE user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";		
}
mysqli_query($conn,$sql);

//upload
move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file);


//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('USER-ENGINEER-ADD-STAMP','CERTIFICATE','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add or edit new stamp for user_id: $user_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>