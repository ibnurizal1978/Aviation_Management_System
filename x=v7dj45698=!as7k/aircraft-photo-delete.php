<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$aircraft_photo_id    = input_data(filter_var($_POST['aircraft_photo_id'],FILTER_SANITIZE_STRING));
$aircraft_master_id    = input_data(filter_var($_POST['aircraft_master_id'],FILTER_SANITIZE_STRING));
$target_dir = "../uploads/aircraft-brochure/";

//hapus
$sql_d    = "SELECT aircraft_photo_id,aircraft_photo_name FROM tbl_aircraft_photo WHERE aircraft_photo_id = '".$aircraft_photo_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h_d      = mysqli_query($conn,$sql_d);
$row_d    = mysqli_fetch_assoc($h_d);
@unlink($target_dir.$row_d['aircraft_photo_name']);

$sql 	= "DELETE FROM tbl_aircraft_photo WHERE aircraft_photo_id = '".$aircraft_photo_id."' LIMIT 1";
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AIRCRAFT-DELETE-PHOTO','AIRCRAFT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'delete photo for airfract ID: $aircraft_master_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data deleted", type: "success"}).then(function(){ location.reload(); });
</script>