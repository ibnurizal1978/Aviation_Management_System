<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$user_id           			= input_data(filter_var($_POST['user_id'],FILTER_SANITIZE_STRING));
$certificate_master_id    	= input_data(filter_var($_POST['certificate_master_id'],FILTER_SANITIZE_STRING));
$user_certificate_date    	= input_data(filter_var($_POST['user_certificate_date'],FILTER_SANITIZE_STRING));
$end_date    				= input_data(filter_var($_POST['end_date'],FILTER_SANITIZE_STRING));

if($user_certificate_date == "") {
?>
<script type="text/javascript">
Swal.fire({ type: 'error', text: 'Please fill date' })
</script>
<?php
exit();
}

//date
$user_certificate_date_y   = substr($user_certificate_date,6,4);
$user_certificate_date_m   = substr($user_certificate_date,3,2);
$user_certificate_date_d   = substr($user_certificate_date,0,2);
$user_certificate_date_f   = $user_certificate_date_y.'-'.$user_certificate_date_m.'-'.$user_certificate_date_d;

//end date
$end_date_y   = substr($end_date,6,4);
$end_date_m   = substr($end_date,3,2);
$end_date_d   = substr($end_date,0,2);
$end_date_f   = $end_date_y.'-'.$end_date_m.'-'.$end_date_d;


$temp = explode(".", $_FILES["upload_file"]["name"]);
$target_dir = "../uploads/certificate/";
$newfilename = date('dmyhis').round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir.$newfilename;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

//file harus < 3MB
if ($_FILES["upload_file"]["size"] > 5000001) {
?>
	<script type="text/javascript">
	Swal.fire({ type: 'error', text: 'Maximum file size is 3MB' })
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

//cek apakah user id dan tipe sertifikat ini sudah ada di tbl_user_certificate?
$sql_anjing 	= "SELECT user_id,certificate_master_id FROM tbl_user_certificate WHERE user_id = '".$user_id."' AND certificate_master_id = '".$certificate_master_id."' LIMIT 1";
$h_anjing	= mysqli_query($conn,$sql_anjing);
if(mysqli_num_rows($h_anjing)>0) { ?>
	<script type="text/javascript">
	Swal.fire({ type: 'error', text: 'Duplicate data' })
	</script>
<?php 
exit();
}

move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file);
$sql_next 	= "SELECT * FROM tbl_certificate_master where certificate_master_id = '".$certificate_master_id."' LIMIT 1";
$h_next 	= mysqli_query($conn,$sql_next);
$row_next	= mysqli_fetch_assoc($h_next);

//hapus image sebelumnya
$sql_d    = "SELECT user_certificate_file FROM tbl_user_certificate WHERE certificate_master_id = '".$certificate_master_id."' AND user_id = '".$user_id."' LIMIT 1";
$h_d      = mysqli_query($conn,$sql_d);
$row_d    = mysqli_fetch_assoc($h_d);
@unlink($target_dir.$row_d['user_certificate_file']);

//insert data
if($row_next['certificate_master_duration']==0) {
	$sql 	= "INSERT INTO tbl_user_certificate(user_id, certificate_master_id,user_certificate_date,user_certificate_next, user_certificate_file,client_id,created_date) VALUES ('".$user_id."','".$certificate_master_id."','".$user_certificate_date_f."','9999-12-31','".$newfilename."','".$_SESSION['client_id']."',UTC_TIMESTAMP())";
}else{
	//$sql 	= "INSERT INTO tbl_user_certificate(user_id, certificate_master_id,user_certificate_date,user_certificate_next, user_certificate_file,client_id,created_date) VALUES ('".$user_id."','".$certificate_master_id."','".$user_certificate_date_f."',DATE_ADD('".$user_certificate_date_f."', INTERVAL ".$row_next['certificate_master_duration']." MONTH),'".$newfilename."','".$_SESSION['client_id']."',UTC_TIMESTAMP())";
	$sql 	= "INSERT INTO tbl_user_certificate(user_id, certificate_master_id,user_certificate_date,user_certificate_next, user_certificate_file,client_id,created_date) VALUES ('".$user_id."','".$certificate_master_id."','".$user_certificate_date_f."', '".$end_date_f."','".$newfilename."','".$_SESSION['client_id']."',UTC_TIMESTAMP())";		
}
//echo $sql;
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('USER-ENGINEER-ADD-CERTIFICATE','CERTIFICATE','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new certificate for user_id: $user_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>