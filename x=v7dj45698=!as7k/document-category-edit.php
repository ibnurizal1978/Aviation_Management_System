<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";
$document_category_id   = input_data(filter_var($_POST['document_category_id'],FILTER_SANITIZE_STRING));
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

//apakah ada duplikat?
$sql  = "SELECT document_category_name FROM tbl_document_category WHERE document_category_name = '".$document_category_name."' AND document_category_id <> '".$document_category_id."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate name'
  })
  </script>
<?php
  exit(); 
}

$sql        = "UPDATE tbl_document_category SET document_category_name = '".$document_category_name."' WHERE user_id = '".$_SESSION['user_id']."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1"; 
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log    = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('DOCUMENT-CATEGORY-EDIT','DOCUMENT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'edit document category','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>