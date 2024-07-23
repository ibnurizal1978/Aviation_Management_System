<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$safety_finding_location      = input_data(filter_var($_POST['safety_finding_location'],FILTER_SANITIZE_STRING));
$safety_finding_area          = input_data(filter_var($_POST['safety_finding_area'],FILTER_SANITIZE_STRING));
$safety_finding_reference     = input_data(filter_var($_POST['safety_finding_reference'],FILTER_SANITIZE_STRING));
$safety_finding_type          = input_data(filter_var($_POST['safety_finding_type'],FILTER_SANITIZE_STRING));
$safety_finding_title         = input_data(filter_var($_POST['safety_finding_title'],FILTER_SANITIZE_STRING));
$safety_finding_description   = input_data(filter_var($_POST['safety_finding_description'],FILTER_SANITIZE_STRING));
$safety_finding_target_date   = input_data(filter_var($_POST['safety_finding_target_date'],FILTER_SANITIZE_STRING));
$safety_finding_target_user_id = input_data(filter_var($_POST['safety_finding_target_user_id'],FILTER_SANITIZE_STRING));
//$safety_finding_team_leader    = input_data(filter_var($_POST['safety_finding_team_leader'],FILTER_SANITIZE_STRING));


if($safety_finding_location == "" || $safety_finding_area == "" || $safety_finding_reference == "" || $safety_finding_type == "" || $safety_finding_title == "" || $safety_finding_description == "" || $safety_finding_target_date == "") {
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
$safety_finding_target_date_y   = substr($safety_finding_target_date,6,4);
$safety_finding_target_date_m   = substr($safety_finding_target_date,3,2);
$safety_finding_target_date_d   = substr($safety_finding_target_date,0,2);
$safety_finding_target_date_f   = $safety_finding_target_date_y.'-'.$safety_finding_target_date_m.'-'.$safety_finding_target_date_d;

//apakah ada duplikat antara judul dan target?
$sql_d  = "SELECT safety_finding_title FROM tbl_safety_finding WHERE safety_finding_title = '".$safety_finding_title."' AND safety_finding_target_user_id = '".$safety_finding_target_user_id."' LIMIT 1";
$h_d    = mysqli_query($conn,$sql_d);
if(mysqli_num_rows($h_d)>0) {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate content'
  })
</script>
<?php
  exit();
}

//cari department_name si tujuan
$sql_d  = "SELECT department_code,department_id,full_name FROM tbl_department a INNER JOIN tbl_user b USING (department_id) WHERE user_id = '".$safety_finding_target_user_id."' LIMIT 1";
$h_d    = mysqli_query($conn,$sql_d);
$row_d  = mysqli_fetch_assoc($h_d);

//cari last ID
$sql  = "SELECT safety_finding_id FROM tbl_safety_finding WHERE client_id = '".$_SESSION['client_id']."' ORDER BY safety_finding_id DESC LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
$safety_finding_no = $row['safety_finding_id']+1;
$finding_no = $row_d['department_code'].$safety_finding_no;

//cari atasan si pembuat form ini
$sql_a  = "SELECT user_id,full_name FROM tbl_user where client_id = '".$_SESSION['client_id']."' AND user_id = '".$_SESSION['user_manager_id']."' LIMIT 1";
$h_a    = mysqli_query($conn,$sql_a);
$row_a  = mysqli_fetch_assoc($h_a);


$sql   = "INSERT INTO tbl_safety_finding(safety_finding_location,safety_finding_no,safety_finding_area,safety_finding_type,safety_finding_reference,safety_finding_title,safety_finding_description,safety_finding_target_date,safety_finding_target_user_id,safety_finding_target_full_name,safety_finding_team_leader_user_id,safety_finding_team_leader_full_name,safety_finding_target_department_id,created_date,user_id,client_id) VALUES ('".$safety_finding_location."','".$finding_no."','".$safety_finding_area."','".$safety_finding_type."','".$safety_finding_reference."','".$safety_finding_title."','".$safety_finding_description."','".$safety_finding_target_date_f."','".$safety_finding_target_user_id."','".$row_d['full_name']."','".$row_a['user_id']."','".$row_a['full_name']."','".$row_d['department_id']."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('SAFETY-FINDING-ADD','SAFETY-FINDING','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new safety finding','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>