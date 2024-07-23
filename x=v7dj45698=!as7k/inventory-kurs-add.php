<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$parts_kurs_amount           = input_data(filter_var($_POST['parts_kurs_amount'],FILTER_SANITIZE_STRING));
$parts_kurs_amount2          = str_replace(',', '', $parts_kurs_amount);


if($parts_kurs_amount == '') {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill all forms'
  })
  </script>
<?php
  exit();
}

mysqli_query($conn, "UPDATE tbl_parts_kurs SET active_status = 0");

$sql   = "INSERT INTO tbl_parts_kurs (parts_kurs_amount,active_status, created_date,user_id) VALUES ('".$parts_kurs_amount2."',1, UTC_TIMESTAMP(),'".$_SESSION['user_id']."')";
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('PARTS-KURS','PARTS','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new kurs','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({title: "Success!",text: "",type: "success"}).then(function() {window.location = "inventory-kurs.php";});
</script>