<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";
$document_category_name   = input_data(filter_var($_POST['document_category_name'],FILTER_SANITIZE_STRING));

if($document_category_name == "") {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill category name'
  })
  </script>
<?php
  exit();
}

$sql        = "INSERT INTO tbl_document_category (document_category_name,created_date,user_id,client_id) VALUES ('".$document_category_name."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')"; 
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log    = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('DOCUMENT-CATEGORY-ADD','DOCUMENT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'create new document category','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>