<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$aircraft_master_id            = input_data(filter_var($_POST['aircraft_master_id'],FILTER_SANITIZE_STRING));
$aircraft_document_master_id   = input_data(filter_var($_POST['aircraft_document_master_id'],FILTER_SANITIZE_STRING));
$start_date    = input_data(filter_var($_POST['start_date'],FILTER_SANITIZE_STRING));
$end_date    = input_data(filter_var($_POST['end_date'],FILTER_SANITIZE_STRING));

//date
$start_date_y   = substr($start_date,6,4);
$start_date_m   = substr($start_date,3,2);
$start_date_d   = substr($start_date,0,2);
$start_date_f   = $start_date_y.'-'.$start_date_m.'-'.$start_date_d;

$end_date_y   = substr($end_date,6,4);
$end_date_m   = substr($end_date,3,2);
$end_date_d   = substr($end_date,0,2);
$end_date_f   = $end_date_y.'-'.$end_date_m.'-'.$end_date_d;


$temp = explode(".", $_FILES["upload_file"]["name"]);
$target_dir = "../uploads/aircraft-certificate/";
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
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "doc" && $imageFileType != "xlsx" && $imageFileType != "docx" && $imageFileType != "ppt" && $imageFileType != "pptx" && $imageFileType != "gif" && $imageFileType != "jpeg") {
?>
	<script type="text/javascript">
	Swal.fire({ type: 'error', text: 'Allowed file type: JPG, GIF, PNG, PDF' })
	</script>
<?php
exit();
}

//cek apakah user id dan tipe sertifikat ini sudah ada di tbl_user_certificate?
$sql_anjing 	= "SELECT aircraft_document_master_id FROM tbl_aircraft_document WHERE aircraft_master_id = '".$aircraft_master_id."' AND aircraft_document_master_id = '".$aircraft_document_master_id."' LIMIT 1";
$h_anjing	= mysqli_query($conn,$sql_anjing);
if(mysqli_num_rows($h_anjing)>0) { ?>
	<script type="text/javascript">
	Swal.fire({ type: 'error', text: 'Duplicate data' })
	</script>
<?php 
exit();
}

//upload
move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file);

//insert data
$sql 	= "INSERT INTO tbl_aircraft_document(aircraft_master_id, aircraft_document_master_id,aircraft_document_file,start_date, end_date,created_date,user_id,client_id) VALUES ('".$aircraft_master_id."','".$aircraft_document_master_id."','".$newfilename."','".$start_date_f."','".$end_date_f."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";		
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AIRCRAFT-ADD-CERTIFICATE','AIRCRAFT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new certificate for airfract ID: $aircraft_master_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>