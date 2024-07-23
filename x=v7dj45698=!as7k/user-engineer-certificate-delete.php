<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$user_certificate_id    = $ntf[1];
$user_id    			= $ntf[2];

//hapus image sebelumnya
$target_dir = "../uploads/certificate/";
$sql_d    = "SELECT user_certificate_file FROM tbl_user_certificate WHERE user_certificate_id = '".$user_certificate_id."' AND user_id = '".$user_id."' LIMIT 1";
$h_d      = mysqli_query($conn,$sql_d);
$row_d    = mysqli_fetch_assoc($h_d);
@unlink($target_dir.$row_d['user_certificate_file']);

$sql 	= "DELETE FROM tbl_user_certificate WHERE user_certificate_id = '".$user_certificate_id."' AND user_id = '".$user_id."' LIMIT 1";
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('USER-ENGINEER-DELETE-CERTIFICATE','CERTIFICATE','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'Delete certificate for user_id: $user_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
header('location:user-engineer-certificate.php?act=29dvi59&ntf=29dvi59-'.$user_id.'-94dfvj!sdf-349ffuaw');
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>