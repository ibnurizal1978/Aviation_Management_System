<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$aircraft_master_id           = input_data(filter_var($_POST['aircraft_master_id'],FILTER_SANITIZE_STRING));

$temp = explode(".", $_FILES["upload_file"]["name"]);
$target_dir = "../uploads/aircraft-brochure/";
$newfilename = date('dmyhis').round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir.$newfilename;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

//file harus < 1MB
if ($_FILES["upload_file"]["size"] > 1000000) {
?>
	<script type="text/javascript">
	Swal.fire({ type: 'error', text: 'Maximum file size is 1MB' })
	</script>
<?php 
exit();
}

//file yang boleh diupload hanya PDF, WORD, XLS, JPG, PNG, GIF, PPT
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "pdf" && $imageFileType != "gif" && $imageFileType != "jpeg") {
?>
	<script type="text/javascript">
	Swal.fire({ type: 'error', text: 'Allowed file type: JPG, GIF, PNG, PDF' })
	</script>
<?php
exit();
}

if(move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file)) {

	//insert data
	$sql 	= "INSERT INTO tbl_aircraft_photo(aircraft_master_id,aircraft_photo_name,created_date,user_id,client_id) VALUES ('".$aircraft_master_id."','".$newfilename."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";		
	mysqli_query($conn,$sql);

	//insert ke table log user
	$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AIRCRAFT-ADD-PHOTO','AIRCRAFT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new photo for airfract ID: $aircraft_master_id','".$_SESSION['client_id']."')";
	mysqli_query($conn,$sql_log);
	?>	
	<script type="text/javascript">
	swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
	</script>
<?php }else{ ?>	
	<script type="text/javascript">
	Swal.fire({ type: 'error', text: 'Maximum file size is 1MB' })
	</script>	
<?php
}