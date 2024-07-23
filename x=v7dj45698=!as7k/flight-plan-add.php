<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$flight_plan_date   = input_data(filter_var($_POST['flight_plan_date'],FILTER_SANITIZE_STRING));
$flight_plan_copilot= input_data(filter_var($_POST['flight_plan_copilot'],FILTER_SANITIZE_STRING));
$flight_plan_number = input_data(filter_var($_POST['flight_plan_number'],FILTER_SANITIZE_STRING));
$aircraft_reg_code  = input_data(filter_var($_POST['aircraft_reg_code'],FILTER_SANITIZE_STRING));
$flight_plan_type   = input_data(filter_var($_POST['flight_plan_type'],FILTER_SANITIZE_STRING));
$flight_plan_rules  = input_data(filter_var($_POST['flight_plan_rules'],FILTER_SANITIZE_STRING));

$taxi_flt_1         = input_data(filter_var($_POST['taxi_flt_1'],FILTER_SANITIZE_STRING));
$taxi_flt_2         = input_data(filter_var($_POST['taxi_flt_2'],FILTER_SANITIZE_STRING));
$taxi_flt_3         = input_data(filter_var($_POST['taxi_flt_3'],FILTER_SANITIZE_STRING));
$taxi_flt_4         = input_data(filter_var($_POST['taxi_flt_4'],FILTER_SANITIZE_STRING));

$holding_flt_1      = input_data(filter_var($_POST['holding_flt_1'],FILTER_SANITIZE_STRING));
$holding_flt_2      = input_data(filter_var($_POST['holding_flt_2'],FILTER_SANITIZE_STRING));
$holding_flt_3      = input_data(filter_var($_POST['holding_flt_3'],FILTER_SANITIZE_STRING));
$holding_flt_4      = input_data(filter_var($_POST['holding_flt_4'],FILTER_SANITIZE_STRING));

$contingency_flt_1  = input_data(filter_var($_POST['contingency_flt_1'],FILTER_SANITIZE_STRING));
$contingency_flt_2  = input_data(filter_var($_POST['contingency_flt_2'],FILTER_SANITIZE_STRING));
$contingency_flt_3  = input_data(filter_var($_POST['contingency_flt_3'],FILTER_SANITIZE_STRING));
$contingency_flt_4  = input_data(filter_var($_POST['contingency_flt_4'],FILTER_SANITIZE_STRING));

$fob_flt_1          = input_data(filter_var($_POST['fob_flt_1'],FILTER_SANITIZE_STRING));
$fob_flt_2          = input_data(filter_var($_POST['fob_flt_2'],FILTER_SANITIZE_STRING));
$fob_flt_3          = input_data(filter_var($_POST['fob_flt_3'],FILTER_SANITIZE_STRING));
$fob_flt_4          = input_data(filter_var($_POST['fob_flt_4'],FILTER_SANITIZE_STRING));

$etd_flt_1          = input_data(filter_var($_POST['etd_flt_1'],FILTER_SANITIZE_STRING));
$etd_flt_2          = input_data(filter_var($_POST['etd_flt_2'],FILTER_SANITIZE_STRING));
$etd_flt_3          = input_data(filter_var($_POST['etd_flt_3'],FILTER_SANITIZE_STRING));
$etd_flt_4          = input_data(filter_var($_POST['etd_flt_4'],FILTER_SANITIZE_STRING));


if($flight_plan_date == "" || $flight_plan_number == "" || $aircraft_reg_code == "" || $flight_plan_type == "" || $flight_plan_rules == "") {
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
$flight_plan_date_y   = substr($flight_plan_date,6,4);
$flight_plan_date_m   = substr($flight_plan_date,3,2);
$flight_plan_date_d   = substr($flight_plan_date,0,2);
$flight_plan_date_f   = $flight_plan_date_y.'-'.$flight_plan_date_m.'-'.$flight_plan_date_d;


//apakah ada duplikat?
$sql  = "SELECT flight_plan_number FROM tbl_flight_plan WHERE flight_plan_number = '".$flight_plan_number."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate flight number'
  })
  </script>
<?php
  exit(); 
}

$flight_plan_code = date('dmy').$_SESSION['user_id'].$_SESSION['client_id'];

//cari full name copilot
$sql_a  = "SELECT full_name FROM tbl_user WHERE user_id = '".$flight_plan_copilot."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h_a    = mysqli_query($conn,$sql_a);
$row_a  = mysqli_fetch_assoc($h_a);

$sql   = "INSERT INTO tbl_flight_plan (flight_plan_code,flight_plan_date,flight_plan_captain,flight_plan_copilot,flight_plan_number,aircraft_reg_code,flight_plan_type,flight_plan_rules,taxi_flt_1,taxi_flt_2,taxi_flt_3,taxi_flt_4,holding_flt_1,holding_flt_2,holding_flt_3,holding_flt_4,contingency_flt_1,contingency_flt_2,contingency_flt_3,contingency_flt_4,fob_flt_1,fob_flt_2,fob_flt_3,fob_flt_4,etd_flt_1,etd_flt_2,etd_flt_3,etd_flt_4,pilot_user_id,copilot_user_id, created_date,client_id) VALUES ('".$flight_plan_code."','".$flight_plan_date_f."','".$_SESSION['full_name']."','".$row_a['full_name']."','".$flight_plan_number."','".$aircraft_reg_code."','".$flight_plan_type."','".$flight_plan_rules."','".$taxi_flt_1."','".$taxi_flt_2."','".$taxi_flt_3."','".$taxi_flt_4."','".$holding_flt_1."','".$holding_flt_2."','".$holding_flt_3."','".$holding_flt_4."','".$contingency_flt_1."','".$contingency_flt_2."','".$contingency_flt_3."','".$contingency_flt_4."','".$fob_flt_1."','".$fob_flt_2."','".$fob_flt_3."','".$fob_flt_4."','".$etd_flt_1."','".$etd_flt_2."','".$etd_flt_3."','".$etd_flt_4."','".$_SESSION['user_id']."','".$flight_plan_copilot."',UTC_TIMESTAMP(),'".$_SESSION['client_id']."')";
//echo $sql;
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('FLIGHT-PLAN-ADD','FLIGHT-PLAN','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new flight plan','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({title: "Data updated!",text: "",type: "success"}).then(function() {window.location = "flight-plan.php";});
</script>
