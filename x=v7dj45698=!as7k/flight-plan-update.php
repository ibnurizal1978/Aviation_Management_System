<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$flight_plan_id   = input_data(filter_var($_POST['flight_plan_id'],FILTER_SANITIZE_STRING));
$flight_no        = input_data(filter_var($_POST['flight_no'],FILTER_SANITIZE_STRING));
$win_dir          = input_data(filter_var($_POST['win_dir'],FILTER_SANITIZE_STRING));
$knots            = input_data(filter_var($_POST['knots'],FILTER_SANITIZE_STRING));
$atd              = input_data(filter_var($_POST['atd'],FILTER_SANITIZE_STRING));

$waypoints_from   = input_data(filter_var($_POST['waypoints_from'],FILTER_SANITIZE_STRING));
$alt              = input_data(filter_var($_POST['alt'],FILTER_SANITIZE_STRING));
$dist             = input_data(filter_var($_POST['dist'],FILTER_SANITIZE_STRING));
$tas              = input_data(filter_var($_POST['tas'],FILTER_SANITIZE_STRING));
$ata              = input_data(filter_var($_POST['ata'],FILTER_SANITIZE_STRING));
$act_fuel_rem     = input_data(filter_var($_POST['act_fuel_rem'],FILTER_SANITIZE_STRING));
$waypoints_to     = input_data(filter_var($_POST['waypoints_to'],FILTER_SANITIZE_STRING));
$trk              = input_data(filter_var($_POST['trk'],FILTER_SANITIZE_STRING));


if($flight_no == "" || $waypoints_from == "" || $waypoints_to == "") {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please choose Flight #, type waypoints from and to'
  })
  </script>
<?php
  exit();
}

$sql   = "INSERT INTO tbl_flight_plan_detail (flight_plan_id,flight_no,win_dir,knots,atd,waypoints_from,waypoints_to,alt,dist,tas,ata,act_fuel_rem,trk, created_date,user_id,client_id) VALUES ('".$flight_plan_id."','".$flight_no."','".$win_dir."','".$knots."','".$atd."','".$waypoints_from."','".$waypoints_to."','".$alt."','".$dist."','".$tas."','".$ata."','".$act_fuel_rem."','".$trk."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
echo $sql;
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('FLIGHT-PLAN-ADD-DETAIL','FLIGHT-PLAN','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add flight plan detail','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
//swal({title: "Data updated!",text: "",type: "success"}).then(function() {window.location = "flight-plan.php";});
</script>
