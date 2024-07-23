<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$year   		= input_data(filter_var($_POST['year'],FILTER_SANITIZE_STRING));
$title   		= input_data(filter_var($_POST['title'],FILTER_SANITIZE_STRING));
$description    = input_data(filter_var($_POST['description'],FILTER_SANITIZE_STRING));

if($title=='' || $description=='') {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill Title and Detail'
  })
  </script>
<?php
  exit();
}

$sql  = "INSERT INTO tbl_user_cv_additional (user_id,year,title,description,created_date,client_id) VALUES ('".$_SESSION['user_id']."','".$year."','".$title."','".$description."',UTC_TIMESTAMP(),'".$_SESSION['client_id']."')";
mysqli_query($conn,$sql);
 
//insert ke table log user
$sql5   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('WORK-ADDITIONAL-INFO-ADD','WORK-EXPERIENCES','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'Add additional info','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql5);

?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>