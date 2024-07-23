<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

/* coba diubah ke menit */
function hoursToMinutes($hours) 
{ 
    $minutes = 0; 
    if (strpos($hours, ':') !== false) 
    { 
        // Split hours and minutes. 
        list($hours, $minutes) = explode(':', $hours); 
    } 
    return $hours * 60 + $minutes; 
} 

// Transform minutes like "105" into hours like "1:45". 
function minutesToHours($minutes) 
{ 
    $hours = (int)($minutes / 60); 
    $minutes -= $hours * 60; 
    return sprintf("%02d:%02.0f", $hours, $minutes); 
}



$afml_page_no       = input_data(filter_var($_POST['afml_page_no'],FILTER_SANITIZE_STRING));
$afml_date       = input_data(filter_var($_POST['afml_date'],FILTER_SANITIZE_STRING));
$aircraft_reg_code  = input_data(filter_var($_POST['aircraft_reg_code'],FILTER_SANITIZE_STRING));
$afml_route_from    = input_data(filter_var($_POST['afml_route_from'],FILTER_SANITIZE_STRING));
$afml_route_to      = input_data(filter_var($_POST['afml_route_to'],FILTER_SANITIZE_STRING));
$afml_block_on      = input_data(filter_var($_POST['afml_block_on'],FILTER_SANITIZE_STRING));
$afml_block_off     = input_data(filter_var($_POST['afml_block_off'],FILTER_SANITIZE_STRING));
$afml_to            = input_data(filter_var($_POST['afml_to'],FILTER_SANITIZE_STRING));
$afml_ldg           = input_data(filter_var($_POST['afml_ldg'],FILTER_SANITIZE_STRING));
$afml_ldg_cycle     = input_data(filter_var($_POST['afml_ldg_cycle'],FILTER_SANITIZE_STRING));
$afml_fuel_rem     = input_data(filter_var($_POST['afml_fuel_rem'],FILTER_SANITIZE_STRING));
$afml_fuel_uplift     = input_data(filter_var($_POST['afml_fuel_uplift'],FILTER_SANITIZE_STRING));
$afml_fuel_total     = input_data(filter_var($_POST['afml_fuel_total'],FILTER_SANITIZE_STRING));

$brought_fwd_ac_hrs     = input_data(filter_var($_POST['brought_fwd_ac_hrs'],FILTER_SANITIZE_STRING));
$brought_fwd_ac_ldg     = input_data(filter_var($_POST['brought_fwd_ac_ldg'],FILTER_SANITIZE_STRING));
$brought_fwd_eng_1_hrs     = input_data(filter_var($_POST['brought_fwd_eng_1_hrs'],FILTER_SANITIZE_STRING));
$brought_fwd_eng_1_ldg     = input_data(filter_var($_POST['brought_fwd_eng_1_ldg'],FILTER_SANITIZE_STRING));
$brought_fwd_prop_hrs     = input_data(filter_var($_POST['brought_fwd_prop_hrs'],FILTER_SANITIZE_STRING));

$this_page_ac_hrs     = input_data(filter_var($_POST['this_page_ac_hrs'],FILTER_SANITIZE_STRING));
$this_page_ac_ldg     = input_data(filter_var($_POST['this_page_ac_ldg'],FILTER_SANITIZE_STRING));
$this_page_eng_1_hrs     = input_data(filter_var($_POST['this_page_eng_1_hrs'],FILTER_SANITIZE_STRING));
$this_page_eng_1_ldg     = input_data(filter_var($_POST['this_page_eng_1_ldg'],FILTER_SANITIZE_STRING));
$this_page_prop_hrs     = input_data(filter_var($_POST['this_page_prop_hrs'],FILTER_SANITIZE_STRING));

$total_ac_hrs     = input_data(filter_var($_POST['total_ac_hrs'],FILTER_SANITIZE_STRING));
$total_ac_ldg     = input_data(filter_var($_POST['total_ac_ldg'],FILTER_SANITIZE_STRING));
$total_eng_1_hrs     = input_data(filter_var($_POST['total_eng_1_hrs'],FILTER_SANITIZE_STRING));
$total_eng_1_ldg     = input_data(filter_var($_POST['total_eng_1_ldg'],FILTER_SANITIZE_STRING));
$total_prop_hrs     = input_data(filter_var($_POST['total_prop_hrs'],FILTER_SANITIZE_STRING));


if($afml_page_no == "" || $afml_route_from == '' || $afml_route_to == '' || $afml_block_on == '' || $afml_block_off == '' || $afml_to == '' || $afml_ldg == '') {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill all form'
  })
  </script>
<?php
  exit();
}

//perhitungan afml_block_hrs: block_on - block_off
$start = strtotime($afml_block_off);
$end = strtotime($afml_block_on);
if ($afml_block_off == '00:00') { 
  $afml_block_off = '24:00'; 
  $jumlah1 = $start - $end;
}else{
  $jumlah1 = $end - $start; 
}
$afml_block_hrs = date("H:i", $jumlah1);
//echo $afml_block_hrs;
//echo '<br/>';

//perhitungan afml_flt_hrs: afml_ldg - afml_to
if ($afml_ldg == '00:00') { $afml_ldg = '24:00'; }
$start = strtotime($afml_to);
$end = strtotime($afml_ldg);
$jumlah2 = $end - $start;
$afml_flt_hrs = date("H:i", $jumlah2);
//echo $afml_flt_hrs;
//echo '<br/>';


//cari icao code from 
$sql_from   = "SELECT icao_code from tbl_master_iata WHERE iata_code = '".$afml_route_from."' LIMIT 1";
$h_from     = mysqli_query($conn, $sql_from);
$row_from   = mysqli_fetch_assoc($h_from);

//cari icao code to 
$sql_to   = "SELECT icao_code from tbl_master_iata WHERE iata_code = '".$afml_route_to."' LIMIT 1";
$h_to     = mysqli_query($conn, $sql_to);
$row_to   = mysqli_fetch_assoc($h_to);

//generate unique code jadi nggak ada yang duplikat/double input
$trx_id   = date('dmYhis');

//get total hours dari jenis pesawat yang dipilih


//Total HRS
function AddTimeToStr($aElapsedTimes) {
  $totalHours = 0;
  $totalMinutes = 0;

  foreach($aElapsedTimes as $time) {
    $timeParts = explode(":", $time);
    $h = $timeParts[0];
    $m = $timeParts[1];
    $totalHours += $h;
    $totalMinutes += $m;
  }

  $additionalHours = floor($totalMinutes / 60);
  $minutes = $totalMinutes % 60;
  $hours = $totalHours + $additionalHours;

  $strMinutes = strval($minutes);
  if ($minutes < 10) {
      $strMinutes = "0" . $minutes;
  }

  $strHours = strval($hours);
  if ($hours < 10) {
      $strHours = "0" . $hours;
  }

  return($strHours . ":" . $strMinutes);
}

$sql   = "INSERT INTO tbl_afml_detail_old (afml_page_no,afml_date,aircraft_reg_code,afml_route_from,afml_route_to,afml_icao_from,afml_icao_to,afml_block_on,afml_block_off,afml_block_hrs,afml_to,afml_ldg,afml_flt_hrs,afml_ldg_cycle,created_date,user_id,client_id,trx_id,afml_fuel_rem,afml_fuel_uplift,afml_fuel_total) VALUES ('".$afml_page_no."','".$afml_date."','".$aircraft_reg_code."','".$afml_route_from."','".$afml_route_to."','".$row_from['icao_code']."','".$row_to['icao_code']."','".$afml_block_on."','".$afml_block_off."','".$afml_block_hrs."','".$afml_to."','".$afml_ldg."','".$afml_flt_hrs."','".$afml_ldg_cycle."', UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."','".$trx_id."','".$afml_fuel_rem."','".$afml_fuel_uplift."','".$afml_fuel_total."')";
//echo $sql;
mysqli_query($conn,$sql);

$sql   = "INSERT INTO tbl_afml_detail (afml_page_no,afml_date,aircraft_reg_code,afml_route_from,afml_route_to,afml_icao_from,afml_icao_to,afml_block_on,afml_block_off,afml_block_hrs,afml_to,afml_ldg,afml_flt_hrs,afml_ldg_cycle,created_date,user_id,client_id,trx_id,afml_fuel_rem,afml_fuel_uplift,afml_fuel_total) VALUES ('".$afml_page_no."','".$afml_date."','".$aircraft_reg_code."','".$afml_route_from."','".$afml_route_to."','".$row_from['icao_code']."','".$row_to['icao_code']."','".hoursToMinutes($afml_block_on)."','".hoursToMinutes($afml_block_off)."','".hoursToMinutes($afml_block_hrs)."','".hoursToMinutes($afml_to)."','".hoursToMinutes($afml_ldg)."','".hoursToMinutes($afml_flt_hrs)."','".$afml_ldg_cycle."', UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."','".$trx_id."','".$afml_fuel_rem."','".$afml_fuel_uplift."','".$afml_fuel_total."')";
//echo $sql;
mysqli_query($conn,$sql);



/*--- dapatkan total BLOCK HRS, FLT HRS dan LDG untuk AFML number ini ---*/
$sqla = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(afml_block_hrs))) AS total_block_hrs, SEC_TO_TIME(SUM(TIME_TO_SEC(afml_flt_hrs))) AS total_flt_hrs, sum(afml_ldg_cycle) as total_ldg FROM tbl_afml_detail_old WHERE afml_page_no = '".$afml_page_no."'";
$ha   = mysqli_query($conn,$sqla);
$rowa = mysqli_fetch_assoc($ha);
$total_flt_hrs = hoursToMinutes($rowa['total_flt_hrs']);
$total_ldg  = $rowa['total_ldg'];

/* hitung prev HRS dan LDG dari total HRS & LDG - current AFML */
$sql_master  = "SELECT aircraft_ac_total_hrs,aircraft_ac_total_ldg,aircraft_eng_1_total_hrs,aircraft_eng_1_total_ldg,aircraft_eng_2_total_hrs,aircraft_eng_2_total_ldg,aircraft_prop_total_hrs,aircraft_prop_total_ldg FROM tbl_aircraft_master WHERE aircraft_reg_code = '".$aircraft_reg_code."' LIMIT 1";
$h_master    = mysqli_query($conn,$sql_master);
$row_master  = mysqli_fetch_assoc($h_master);

/* convert dulu jam FLT dari AFML ke minutes */
$flt_to_minutes = hoursToMinutes($afml_flt_hrs);

/*jumlahkan ke data master pesawat */
$aircraft_ac_total_hrs    = $row_master['aircraft_ac_total_hrs']+$flt_to_minutes;
$aircraft_eng_1_total_hrs = $row_master['aircraft_eng_1_total_hrs']+$flt_to_minutes;
$aircraft_prop_total_hrs  = $row_master['aircraft_prop_total_hrs']+$flt_to_minutes;

$sql_update = "UPDATE tbl_aircraft_master SET 
aircraft_ac_total_hrs = '".$aircraft_ac_total_hrs."',
aircraft_ac_total_ldg = '".$row_master['aircraft_ac_total_ldg']."'+$afml_ldg_cycle,
aircraft_eng_1_total_hrs = '".$aircraft_eng_1_total_hrs."',
aircraft_eng_1_total_ldg = '".$row_master['aircraft_eng_1_total_ldg']."'+$afml_ldg_cycle,
aircraft_prop_total_hrs = '".$aircraft_prop_total_hrs."' 
WHERE aircraft_reg_code = '".$aircraft_reg_code."' LIMIT 1";
//echo $sql_update;
mysqli_query($conn,$sql_update);

//echo '<br/><br/>';

$sql_update_afml = "UPDATE tbl_afml SET 
this_page_ac_hrs = ".$this_page_ac_hrs."+$flt_to_minutes,
this_page_ac_ldg = '".$total_ldg."',
this_page_eng_1_hrs = ".$this_page_eng_1_hrs."+$flt_to_minutes,
this_page_eng_1_ldg = '".$total_ldg."',
this_page_prop_hrs = ".$this_page_prop_hrs."+$flt_to_minutes,
total_ac_hrs = ".$brought_fwd_ac_hrs."+$total_flt_hrs,
total_ac_ldg = ".$brought_fwd_ac_ldg."+$total_ldg,
total_eng_1_hrs = ".$brought_fwd_eng_1_hrs."+$total_flt_hrs,
total_eng_1_ldg = ".$brought_fwd_eng_1_ldg."+$total_ldg,
total_prop_hrs = ".$brought_fwd_prop_hrs."+$total_flt_hrs
WHERE afml_page_no = '".$afml_page_no."' LIMIT 1";
//echo $sql_update_afml;
mysqli_query($conn,$sql_update_afml);

//hitung total hrs pilot
$sql_ttl_hrs = "SELECT user_total_hrs FROM tbl_user WHERE user_id = '".$_SESSION['user_id']."' LIMIT 1";
$h_ttl_hrs   = mysqli_query($conn,$sql_ttl_hrs);
$row_ttl_hrs = mysqli_fetch_assoc($h_ttl_hrs);

$total_hrs  = AddTimeToStr(array($row_ttl_hrs['user_total_hrs'], $afml_flt_hrs));

$sql_update = "UPDATE tbl_user SET user_total_hrs = '".$total_hrs."' WHERE user_id = '".$_SESSION['user_id']."' LIMIT 1";
//echo $sql_update;
mysqli_query($conn,$sql_update);

/* cari ID pilot dan copilot */
$sql_afml = "SELECT afml_captain_user_id, afml_copilot_user_id FROM tbl_afml WHERE afml_page_no = '".$afml_page_no."' LIMIT 1";
$h_afml   = mysqli_query($conn, $sql_afml);
$row_afml = mysqli_fetch_assoc($h_afml);

/* insert ke tbl pilot hours buat jam terbang PILOT */
$sql_hrs1 = "INSERT INTO tbl_afml_pilot_hours (afml_page_no,user_id,created_date,client_id) VALUES ('".$afml_page_no."','".$row_afml['afml_captain_user_id']."', UTC_TIMESTAMP(), '".$_SESSION['client_id']."')";

//echo $sql_hrs1;
/* insert ke tbl pilot hours buat jam terbang COPILOT */
$sql_hrs2 = "INSERT INTO tbl_afml_pilot_hours (afml_page_no,user_id,created_date,client_id) VALUES ('".$afml_page_no."','".$row_afml['afml_copilot_user_id']."', UTC_TIMESTAMP(),'".$_SESSION['client_id']."')";

mysqli_query($conn,$sql_hrs1);
mysqli_query($conn,$sql_hrs2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AFML-DETAIL-ADD','AFML','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new AFML detail for page no: $afml_page_no','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>