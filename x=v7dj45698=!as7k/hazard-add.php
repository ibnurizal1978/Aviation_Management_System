<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$afml_page_no     = input_data(filter_var(@$_POST['afml_page_no'],FILTER_SANITIZE_STRING));
$hazard_reg_no     = input_data(filter_var($_POST['hazard_reg_no'],FILTER_SANITIZE_STRING));
$hazard_description     = input_data(filter_var($_POST['hazard_description'],FILTER_SANITIZE_STRING));
$hazard_location     = input_data(filter_var($_POST['hazard_location'],FILTER_SANITIZE_STRING));
$hazard_date    = input_data(filter_var($_POST['hazard_date'],FILTER_SANITIZE_STRING));
$hazard_probability    = input_data(filter_var($_POST['hazard_probability'],FILTER_SANITIZE_STRING));
$hazard_severity    = input_data(filter_var($_POST['hazard_severity'],FILTER_SANITIZE_STRING));
$hazard_recommendation           = input_data(filter_var($_POST['hazard_recommendation'],FILTER_SANITIZE_STRING));
$hazard_risk_level = $hazard_probability.$hazard_severity;


if($hazard_reg_no == "" || $hazard_description == "" || $hazard_location == "" || $hazard_date == "" || $hazard_recommendation == "") {
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
$hazard_date_y   = substr($hazard_date,6,4);
$hazard_date_m   = substr($hazard_date,3,2);
$hazard_date_d   = substr($hazard_date,0,2);
$hazard_date_f   = $hazard_date_y.'-'.$hazard_date_m.'-'.$hazard_date_d;


//apakah ada duplikat?
$sql  = "SELECT hazard_reg_no FROM tbl_hazard WHERE client_id = '".$_SESSION['client_id']."' AND hazard_reg_no = '".$hazard_reg_no."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate ref number'
  })
  </script>
<?php
  exit(); 
}

//cari atribut aircraft
$sql  = "INSERT INTO tbl_hazard (afml_page_no,hazard_reg_no,hazard_description,hazard_location,hazard_date,hazard_probability,hazard_severity,hazard_risk_level,hazard_recommendation,hazard_created_date,hazard_created_user_id,client_id) VALUES ('".@$afml_page_no."','".$hazard_reg_no."','".$hazard_description."','".$hazard_location."','".$hazard_date_f."','".$hazard_probability."','".$hazard_severity."','".$hazard_risk_level."','".$hazard_recommendation."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql);
//echo $sql;

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('HAZARD-ADD','HAZARD','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new hazard','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);

if(@$afml_page_no <> '') {
?>
<script type="text/javascript">
swal({title: "Data updated!",text: "",type: "success"}).then(function() {window.location = "afml.php";});
</script>
<?php }else{ ?>
<script type="text/javascript">
swal({title: "Data updated!",text: "",type: "success"}).then(function() {window.location = "hazard.php";});
</script>
<?php } ?>