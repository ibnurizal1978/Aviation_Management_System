<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";


$afml_date     = input_data(filter_var($_POST['afml_date'],FILTER_SANITIZE_STRING));
$afml_page_no     = input_data(filter_var($_POST['afml_page_no'],FILTER_SANITIZE_STRING));
$aircraft_reg_code     = input_data(filter_var($_POST['aircraft_reg_code'],FILTER_SANITIZE_STRING));
$afml_pilot    = input_data(filter_var($_POST['afml_pilot'],FILTER_SANITIZE_STRING));
$afml_copilot    = input_data(filter_var($_POST['afml_copilot'],FILTER_SANITIZE_STRING));
$afml_engineer_on_board    = input_data(filter_var($_POST['afml_engineer_on_board'],FILTER_SANITIZE_STRING));
//$afml_time_preflight    = input_data(filter_var($_POST['afml_time_preflight'],FILTER_SANITIZE_STRING));
//$afml_time_daily           = input_data(filter_var($_POST['afml_time_daily'],FILTER_SANITIZE_STRING));
//$afml_station_preflight          = input_data(filter_var($_POST['afml_station_preflight'],FILTER_SANITIZE_STRING));
//$afml_station_daily      = input_data(filter_var($_POST['afml_station_daily'],FILTER_SANITIZE_STRING));
//$afml_lic_preflight      = input_data(filter_var($_POST['afml_lic_preflight'],FILTER_SANITIZE_STRING));
//$afml_lic_daily    = input_data(filter_var($_POST['afml_lic_daily'],FILTER_SANITIZE_STRING));
$afml_notes_pilot    = input_data(filter_var($_POST['afml_notes_pilot'],FILTER_SANITIZE_STRING));

//etcm
$etcm_time    = input_data(filter_var($_POST['etcm_time'],FILTER_SANITIZE_STRING));
$ectm_altitude    = input_data(filter_var($_POST['ectm_altitude'],FILTER_SANITIZE_STRING));
$ectm_ias    = input_data(filter_var($_POST['ectm_ias'],FILTER_SANITIZE_STRING));
$ectm_tq    = input_data(filter_var($_POST['ectm_tq'],FILTER_SANITIZE_STRING));
$ectm_itt    = input_data(filter_var($_POST['ectm_itt'],FILTER_SANITIZE_STRING));
$ectm_ng    = input_data(filter_var($_POST['ectm_ng'],FILTER_SANITIZE_STRING));
$ectm_np    = input_data(filter_var($_POST['ectm_np'],FILTER_SANITIZE_STRING));
$ectm_ff    = input_data(filter_var($_POST['ectm_ff'],FILTER_SANITIZE_STRING));
$ectm_oil_temp    = input_data(filter_var($_POST['ectm_oil_temp'],FILTER_SANITIZE_STRING));
$ectm_oil_press    = input_data(filter_var($_POST['ectm_oil_press'],FILTER_SANITIZE_STRING));
$ectm_oat    = input_data(filter_var($_POST['ectm_oat'],FILTER_SANITIZE_STRING));

if($afml_page_no == "" || $aircraft_reg_code == "" || $afml_pilot == "") {
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


/* diubah ke menit */
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



if(!empty($_FILES["upload_file"]))  {  
  $file_array = explode(".", $_FILES["upload_file"]["name"]);  
  if($file_array[1] == "xls")  {  
    include("../assets/js/PHPExcel/IOFactory.php");  
    $output = '';  
    $output .= "  
    <label class='text-success'>Data Inserted</label>  
    <table class='table table-bordered'>  
      <tr>  
        <th>Product Name</th>  
        <th>SKU</th>   
      </tr>";  
    $object = PHPExcel_IOFactory::load($_FILES["upload_file"]["tmp_name"]);  
    foreach($object->getWorksheetIterator() as $worksheet)  {  
      $highestRow = $worksheet->getHighestRow();  
      for($row=3; $row<=$highestRow; $row++)  {

        //raih data dari kolom excel
        $afml_route_from  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(0, $row)->getValue());  
        $afml_route_to    = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(1, $row)->getValue());  
        $afml_block_off   = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(2, $row)->getValue());  
        $afml_block_on    = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(3, $row)->getValue());  
        $afml_to          = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(4, $row)->getValue());
        $afml_ldg         = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(5, $row)->getValue()); 
        $afml_ldg_cycle   = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(6, $row)->getValue());        
        $afml_fuel_rem    = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(7, $row)->getValue()); 
        $afml_fuel_uplift = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(8, $row)->getValue());
        $afml_fuel_total  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(9, $row)->getValue()); 
        $afml_fuel_receipt_no   = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(10, $row)->getValue());
        $afml_added_oil   = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(11, $row)->getValue());                       
        $afml_added_hyd   = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(12, $row)->getValue()); 

        //perhitungan afml_block_hrs: block_on - block_off
        $afml_block_off = hoursToMinutes(date('H:i',(PHPExcel_Shared_Date::ExcelToPHP($afml_block_off))));
        $afml_block_on  = hoursToMinutes(date('H:i',(PHPExcel_Shared_Date::ExcelToPHP($afml_block_on))));
        $afml_block_hrs = $afml_block_on - $afml_block_off;
        if($afml_block_hrs<0) { $afml_block_hrs = $afml_block_hrs+1440; } 

        //perhitungan afml_flt_hrs: afml_ldg - afml_to
        $afml_to = hoursToMinutes(date('H:i',(PHPExcel_Shared_Date::ExcelToPHP($afml_to))));
        $afml_ldg  = hoursToMinutes(date('H:i',(PHPExcel_Shared_Date::ExcelToPHP($afml_ldg))));
        $afml_flt_hrs = $afml_ldg - $afml_to;
        if($afml_flt_hrs<0) { $afml_flt_hrs = $afml_flt_hrs+1440; }

        //generate unique code jadi nggak ada yang duplikat/double input
        $trx_id   = $afml_page_no.$afml_block_off;

        $sql   = "INSERT INTO tbl_afml_detail_new (afml_page_no,afml_date,aircraft_reg_code,afml_route_from,afml_route_to,afml_icao_from,afml_icao_to,afml_block_off,afml_block_on,afml_block_hrs,afml_to,afml_ldg,afml_flt_hrs,afml_ldg_cycle,created_date,user_id,client_id,trx_id,afml_fuel_rem,afml_fuel_uplift,afml_fuel_total) VALUES ('".$afml_page_no."','".$afml_date."','".$aircraft_reg_code."','".$afml_route_from."','".$afml_route_to."','','','".$afml_block_off."','".$afml_block_on."','".$afml_block_hrs."','".$afml_to."','".$afml_ldg."','".$afml_flt_hrs."','".$afml_ldg_cycle."', UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."','".$trx_id."','".$afml_fuel_rem."','".$afml_fuel_uplift."','".$afml_fuel_total."')";
        echo $sql.'<br/>';
        mysqli_query($conn, $sql);   
        }  
      }  
      $output .= '</table>';  
      //echo $output;
      echo '<div class="alert alert-success ks-solid text-center">Data uploaded successfully</div>';  
    }else{  
      echo '<div class="alert alert-success ks-solid text-center">Invalid File</div>';  
  }  
} 

//hapus data yang kosong
mysqli_query($conn, "DELETE FROM tbl_afml_detail_new WHERE afml_route_from = ''");

//get total hours dari jenis pesawat yang dipilih
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

//$sql   = "INSERT INTO tbl_afml_detail (afml_page_no,afml_date,aircraft_reg_code,afml_route_from,afml_route_to,afml_icao_from,afml_icao_to,afml_block_on,afml_block_off,afml_block_hrs,afml_to,afml_ldg,afml_flt_hrs,afml_ldg_cycle,created_date,user_id,client_id,trx_id,afml_fuel_rem,afml_fuel_uplift,afml_fuel_total) VALUES ('".$afml_page_no."','".$afml_date."','".$aircraft_reg_code."','".$afml_route_from."','".$afml_route_to."','".$row_from['icao_code']."','".$row_to['icao_code']."','".$afml_block_on."','".$afml_block_off."','".$afml_block_hrs."','".$afml_to."','".$afml_ldg."','".$afml_flt_hrs."','".$afml_ldg_cycle."', UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."','".$trx_id."','".$afml_fuel_rem."','".$afml_fuel_uplift."','".$afml_fuel_total."')";
//echo $sql;
//mysqli_query($conn,$sql);

/*--- dapatkan total BLOCK HRS, FLT HRS dan LDG untuk AFML number ini ---*/
$sqla = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(afml_block_hrs))) AS total_block_hrs, SEC_TO_TIME(SUM(TIME_TO_SEC(afml_flt_hrs))) AS total_flt_hrs, sum(afml_ldg_cycle) as total_ldg FROM tbl_afml_detail WHERE afml_page_no = '".$afml_page_no."'";
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


//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AFML-DETAIL-ADD','AFML','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new AFML detail for page no: $afml_page_no','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>