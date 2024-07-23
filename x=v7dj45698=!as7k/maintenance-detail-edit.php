<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$aircraft_parts_id     = input_data(filter_var($_POST['aircraft_parts_id'],FILTER_SANITIZE_STRING));
$aircraft_master_id     = input_data(filter_var($_POST['aircraft_master_id'],FILTER_SANITIZE_STRING));
$ata_code     = input_data(filter_var($_POST['ata_code'],FILTER_SANITIZE_STRING));
$position    = input_data(filter_var($_POST['position'],FILTER_SANITIZE_STRING));
$description    = input_data(filter_var($_POST['description'],FILTER_SANITIZE_STRING));
$part_number    = input_data(filter_var($_POST['part_number'],FILTER_SANITIZE_STRING));
$serial_number           = input_data(filter_var($_POST['serial_number'],FILTER_SANITIZE_STRING));
$mth          = input_data(filter_var($_POST['mth'],FILTER_SANITIZE_STRING));
$ldg      = input_data(filter_var($_POST['ldg'],FILTER_SANITIZE_STRING));
$hrs      = input_data(filter_var($_POST['hrs'],FILTER_SANITIZE_STRING));
$installed_date    = input_data(filter_var($_POST['installed_date'],FILTER_SANITIZE_STRING));
//$next_ldg    = input_data(filter_var($_POST['next_ldg'],FILTER_SANITIZE_STRING));
//$next_hrs    = input_data(filter_var($_POST['next_hrs'],FILTER_SANITIZE_STRING));
//$next_install_date    = input_data(filter_var($_POST['next_install_date'],FILTER_SANITIZE_STRING));


if($description == "" || $serial_number == "" || $installed_date == "" ) {
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
$installed_date_y   = substr($installed_date,6,4);
$installed_date_m   = substr($installed_date,3,2);
$installed_date_d   = substr($installed_date,0,2);
$installed_date_f   = $installed_date_y.'-'.$installed_date_m.'-'.$installed_date_d;
/*
$next_install_date_y   = substr($next_install_date,6,4);
$next_install_date_m   = substr($next_install_date,3,2);
$next_install_date_d   = substr($next_install_date,0,2);
$next_install_date_f   = $next_install_date_y.'-'.$next_install_date_m.'-'.$next_install_date_d;
*/

//apakah ada duplikat?
/*
$sql  = "SELECT part_number FROM tbl_aircraft_parts WHERE client_id = '".$_SESSION['client_id']."' AND part_number = '".$part_number."' AND aircraft_parts_id <> '".$aircraft_parts_id."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
  echo $sql;
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate serial number'
  })
  </script>
<?php
  exit(); 
} , next_install_date = '".$next_install_date_f."', next_ldg = '".$next_ldg."', next_hrs = '".$next_hrs."'
*/

$sql   = "UPDATE tbl_aircraft_parts SET ata_code = '".$ata_code."', position = '".$position."', description = '".$description."', part_number = '".$part_number."', serial_number = '".$serial_number."', mth = '".$mth."', hrs = '".$hrs."', ldg = '".$ldg."', installed_date = '".$installed_date_f."'  WHERE aircraft_parts_id = '".$aircraft_parts_id."' LIMIT 1";
//echo $sql;
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('PARTS-EDIT','PARTS','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'edit parts with ID: $aircraft_parts_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({title: "Data updated!",text: "",type: "success"}).then(function() {window.location = "maintenance-detail.php??act=29dvi59&aircraft_master_id=<?php echo $aircraft_master_id ?>&ntf=29dvi59-<?php echo $aircraft_master_id ?>-94dfvj!sdf-349ffuaw";});
</script>