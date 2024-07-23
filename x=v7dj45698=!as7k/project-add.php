<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$project_master_name  = input_data(filter_var($_POST['project_master_name'],FILTER_SANITIZE_STRING));
$project_master_amount       = input_data(filter_var($_POST['project_master_amount'],FILTER_SANITIZE_STRING));
$start_date    = input_data(filter_var($_POST['start_date'],FILTER_SANITIZE_STRING));
$end_date     = input_data(filter_var($_POST['end_date'],FILTER_SANITIZE_STRING));
$project_master_amount2    = str_replace(',', '', $project_master_amount);

if($project_master_name == "") {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill marked (*) forms'
  })
  </script>
<?php
  exit();
}

//date
$start_date_y   = substr($start_date,6,4);
$start_date_m   = substr($start_date,3,2);
$start_date_d   = substr($start_date,0,2);
$start_date_f   = $start_date_y.'-'.$start_date_m.'-'.$start_date_d;

$end_date_y   = substr($end_date,6,4);
$end_date_m   = substr($end_date,3,2);
$end_date_d   = substr($end_date,0,2);
$end_date_f   = $end_date_y.'-'.$end_date_m.'-'.$end_date_d;

//apakah ada duplikat?
$sql  = "SELECT project_master_name FROM tbl_project_master WHERE project_master_name = '".$project_master_name."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate project name'
  })
  </script>
<?php
  exit(); 
}

$sql2   = "INSERT INTO tbl_project_master (project_master_name,project_master_amount,start_date,end_date,created_date,user_id,client_id) VALUES ('".$project_master_name."','".$project_master_amount2."','".$start_date_f."','".$end_date_f."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql2);

//insert ke table log user
$sql5   = "INSERT INTO tbl_user_log (user_log_action,user_id,user_log_date) VALUES ('PROJECT-ADD','".$_SESSION['user_id']."',UTC_TIMESTAMP())";
mysqli_query($conn,$sql5);
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>