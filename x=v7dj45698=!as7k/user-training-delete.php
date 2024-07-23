<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$training_id   	= $ntf[1];

$sql  = "DELETE FROM tbl_user_cv_training WHERE training_id = '".$training_id."' AND user_id = '".$_SESSION['user_id']."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
mysqli_query($conn,$sql);
 
//insert ke table log user
$sql5   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('TRAINING-DELETE','USER-TRAINING','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'Delete training','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql5);
header('location:user-training.php');

?>
<script type="text/javascript">
//swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>