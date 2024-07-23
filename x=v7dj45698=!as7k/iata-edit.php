<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$master_iata_id     = input_data(filter_var($_POST['master_iata_id'],FILTER_SANITIZE_STRING));
$iata_location      = input_data(filter_var($_POST['iata_location'],FILTER_SANITIZE_STRING));
$iata_code          = input_data(filter_var(strtoupper($_POST['iata_code']),FILTER_SANITIZE_STRING));
$icao_code          = input_data(filter_var(strtoupper($_POST['icao_code']),FILTER_SANITIZE_STRING));
$iata_province      = input_data(filter_var($_POST['iata_province'],FILTER_SANITIZE_STRING));
$iata_airport_name  = input_data(filter_var($_POST['iata_airport_name'],FILTER_SANITIZE_STRING));

if($iata_location == "" || $iata_code == "" || $iata_province == "") {
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

//apakah ada duplikat IATA?
$sql  = "SELECT iata_code FROM tbl_master_iata WHERE iata_code = '".$iata_code."' AND master_iata_id <> '".$master_iata_id."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate IATA Code'
  })
  </script>
<?php
  exit(); 
}


$sql2   = "UPDATE tbl_master_iata SET iata_location = '".$iata_location."',iata_code = '".$iata_code."',icao_code = '".$icao_code."',iata_province = '".$iata_province."',iata_airport_name = '".$iata_airport_name."' WHERE master_iata_id = '".$master_iata_id."' LIMIT 1";
mysqli_query($conn,$sql2);
//echo $sql2;

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('IATA-EDIT','IATA','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'EDIT IATA CODE with ID: $master_iata_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
  swal({title: "Data updated!",text: "",type: "success"}).then(function() {window.location = "iata.php";});
</script>