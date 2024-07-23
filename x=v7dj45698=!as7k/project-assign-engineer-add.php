<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$project_master_id     = input_data(filter_var($_POST['project_master_id'],FILTER_SANITIZE_STRING));

$banyaknya = count(@$_POST['user_id']);
for ($i=0; $i<$banyaknya; $i++) {
  if(@$_POST['user_id'][$i]) {
  $sql_menu2  = "INSERT INTO tbl_project_engineer_team(project_master_id,engineer_user_id,created_date,user_id,client_id) VALUES ('".$project_master_id."','".@$_POST['user_id'][$i]."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
  mysqli_query($conn,$sql_menu2);
  //echo $sql_menu2.'<br/>';
  }
}

//insert ke table log user
$sql5   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('PROJECT-ADD-ENGINEER','PROJECT-ENGINEER','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'Add engineer team to project','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql5);

?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>