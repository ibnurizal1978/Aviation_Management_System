<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

/*
1. get total HRS, LDG di aircraft reg code ini
2. get selisih : 
   - if total salah > total benar, selisih = total salah - total benar
   - if total salah < total benar, selisih = total benar - total salah
3. update tbl_afml_detail
4. insert into tbl_afml_log
5. update tbl_aircraft_master where AC hrs = selisih_hrs, LDG = selisih_ldg, prop = selisih

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

$afml_id       = input_data(filter_var($_POST['afml_id'],FILTER_SANITIZE_STRING));
$aircraft_reg_code       = input_data(filter_var($_POST['aircraft_reg_code'],FILTER_SANITIZE_STRING));
$afml_detail_id       = input_data(filter_var($_POST['afml_detail_id'],FILTER_SANITIZE_STRING));
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
$afml_added_hyd     = input_data(filter_var($_POST['afml_added_hyd'],FILTER_SANITIZE_STRING));
$afml_added_oil     = input_data(filter_var($_POST['afml_added_oil'],FILTER_SANITIZE_STRING));

$brought_fwd_ac_hrs     = input_data(filter_var($_POST['brought_fwd_ac_hrs_old'],FILTER_SANITIZE_STRING));
$brought_fwd_ac_ldg     = input_data(filter_var($_POST['brought_fwd_ac_ldg_old'],FILTER_SANITIZE_STRING));
$brought_fwd_eng_1_hrs     = input_data(filter_var($_POST['brought_fwd_eng_1_hrs_old'],FILTER_SANITIZE_STRING));
$brought_fwd_eng_1_ldg     = input_data(filter_var($_POST['brought_fwd_eng_1_ldg_old'],FILTER_SANITIZE_STRING));
$brought_fwd_prop_hrs     = input_data(filter_var($_POST['brought_fwd_prop_hrs_old'],FILTER_SANITIZE_STRING));

$afml_receipt_no     = input_data(filter_var($_POST['afml_receipt_no'],FILTER_SANITIZE_STRING));
$afml_fuel_date     = input_data(filter_var($_POST['afml_fuel_date'],FILTER_SANITIZE_STRING));


if($afml_route_from == '' || $afml_route_to == '' || $afml_block_on == '' || $afml_block_off == '' || $afml_to == '' || $afml_ldg == '') {
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


$afml_fuel_date_y   = substr($afml_fuel_date,6,4);
$afml_fuel_date_m   = substr($afml_fuel_date,3,2);
$afml_fuel_date_d   = substr($afml_fuel_date,0,2);
$afml_fuel_date_f   = $afml_fuel_date_y.'-'.$afml_fuel_date_m.'-'.$afml_fuel_date_d;

//cari icao code from 
$sql_from   = "SELECT icao_code from tbl_master_iata WHERE iata_code = '".$afml_route_from."' LIMIT 1";
$h_from     = mysqli_query($conn, $sql_from);
$row_from   = mysqli_fetch_assoc($h_from);

//cari icao code to 
$sql_to   = "SELECT icao_code from tbl_master_iata WHERE iata_code = '".$afml_route_to."' LIMIT 1";
$h_to     = mysqli_query($conn, $sql_to);
$row_to   = mysqli_fetch_assoc($h_to);


//get data afml_detail yang current
$sql1   = "SELECT * FROM tbl_afml_detail WHERE afml_detail_id = '".$afml_detail_id."' LIMIT 1";
$h1     = mysqli_query($conn,$sql1);
$row1   = mysqli_fetch_assoc($h1);

//get total hrs dari pesawatnya
$sql_master  = "SELECT aircraft_ac_total_hrs,aircraft_ac_total_ldg,aircraft_eng_1_total_hrs,aircraft_eng_1_total_ldg,aircraft_eng_2_total_hrs,aircraft_eng_2_total_ldg,aircraft_prop_total_hrs,aircraft_prop_total_ldg FROM tbl_aircraft_master WHERE aircraft_reg_code = '".$row1['aircraft_reg_code']."' LIMIT 1";
$h_master    = mysqli_query($conn,$sql_master);
$row_master  = mysqli_fetch_assoc($h_master);

$afml_block_on_old      = $row1['afml_block_on'];
$afml_block_off_old     = $row1['afml_block_off'];
$afml_block_hrs_old     = $row1['afml_block_hrs'];
$afml_to_old            = $row1['afml_to'];
$afml_ldg_old           = $row1['afml_ldg'];
$afml_flt_hrs_old       = $row1['afml_flt_hrs'];

$afml_block_on2      = hoursToMinutes($afml_block_on);
$afml_block_off2     = hoursToMinutes($afml_block_off);
$afml_to2            = hoursToMinutes($afml_to);
$afml_ldg2           = hoursToMinutes($afml_ldg);
$afml_ldg2           = hoursToMinutes($afml_ldg);

/* hitung block hrs*/
$afml_block_hrs_new  = $afml_block_on2 - $afml_block_off2;
if($afml_block_hrs_new<0) { $afml_block_hrs_new = $afml_block_hrs_new+1440; }
$afml_block_hrs_new2 = minutesToHours($afml_block_hrs_new);

/* hitung flt hrs */
$afml_flt_hrs_new    = $afml_ldg2 - $afml_to2;
if($afml_flt_hrs_new<0) { $afml_flt_hrs_new = $afml_flt_hrs_new+1440; }
$afml_flt_hrs_new2   = minutesToHours($afml_flt_hrs_new);


/*
rumusnya:
BLOCK HRS = BLOCK ON - BLOCK OFF
FLT HRS = LDG - TO
*/
?><b>OLD DATA</b>
<table width="100%" border="1">
  <tr>
    <td>block on old</td>
    <td>block off old</td>
    <td>block hrs old</td>
    <td>afml to old</td>
    <td>afml ldg old</td>
    <td>afml flt hrs old</td>
  </tr>
  <tr>
    <td><?php echo minutesToHours($row1['afml_block_on']).' atau '.$afml_block_on_old ?></td>
    <td><?php echo minutesToHours($row1['afml_block_off']).' atau '.$afml_block_off_old ?></td>
    <td><?php echo minutesToHours($row1['afml_block_hrs']).' atau'.$afml_block_hrs_old ?></td>
    <td><?php echo minutesToHours($row1['afml_to']).' atau'.$afml_to_old ?></td>
    <td><?php echo minutesToHours($row1['afml_ldg']).' atau'.$afml_ldg_old ?></td>
    <td><?php echo minutesToHours($row1['afml_flt_hrs']).' atau '.$afml_flt_hrs_old ?></td>  
  </tr>
</table>
<br/>
<b>NEW DATA</b>
<table width="100%" border=1>
  <tr>
    <td>block on new</td>
    <td>block off new</td>
    <td>block hrs new</td>
    <td>afml to new</td>
    <td>afml ldg new</td>
    <td>afml flt hrs new</td>
  </tr>
  <tr>
    <td><?php echo $afml_block_on.' atau '.$afml_block_on2 ?></td>
    <td><?php echo $afml_block_off.' atau '.$afml_block_off2 ?></td>
    <td><?php echo $afml_block_hrs_new2.' atau '.$afml_block_hrs_new ?></td>
    <td><?php echo $afml_to.' atau '.$afml_to2 ?></td>
    <td><?php echo $afml_ldg.' atau '.$afml_ldg2 ?></td>
    <td><?php echo $afml_flt_hrs_new2.' atau '.$afml_flt_hrs_new ?></td>
  </tr>
</table>
<br/><br/>ARTINYA:<br/>
<?php
//apakah block hrs new > block hrs lama?
if($afml_block_hrs_new>$afml_block_hrs_old) {
  echo '- block hrs yang baru lbh besar';
}else{
  echo '- block hrs yang baru lbh kecil';
}
echo '<br/>';
//apakah block hrs new > block hrs lama?
if($afml_flt_hrs_new>$afml_flt_hrs_old) {
  echo '- flt hrs yang baru lbh besar';
  $selisih = $afml_flt_hrs_new-$afml_flt_hrs_old;
  $total_ac_hrs = $row_master['aircraft_ac_total_hrs'] + $selisih;
  $total_eng_1_hrs = $row_master['aircraft_eng_1_total_hrs'] + $selisih;
  $total_prop_hrs = $row_master['aircraft_prop_total_hrs'] + $selisih;
}else{
  echo '- flt hrs yang baru lbh kecil';
  $selisih = $afml_flt_hrs_old-$afml_flt_hrs_new;
  $total_ac_hrs = $row_master['aircraft_ac_total_hrs'] - $selisih;
  $total_eng_1_hrs = $row_master['aircraft_eng_1_total_hrs'] - $selisih;
  $total_prop_hrs = $row_master['aircraft_prop_total_hrs'] - $selisih;
}

echo '<br/>Selisih FLT HRS diatas (antara yg lama dan baru): '.minutesToHours($selisih).' atau '.$selisih;
echo '<br/>Total AC hrs sebelum diganti: '.minutesToHours($row_master['aircraft_ac_total_hrs']).' atau '.$row_master['aircraft_ac_total_hrs'];
echo '<br/>Total AC hrs setelah diganti: '.minutesToHours($total_ac_hrs).' atau '.$total_ac_hrs;
echo '<br/>Total prop hrs sebelum diganti: '.minutesToHours($row_master['aircraft_prop_total_hrs']).' atau '.$row_master['aircraft_prop_total_hrs'];


$sql   = "UPDATE tbl_afml_detail SET 
  afml_route_from='".$afml_route_from."',
  afml_route_to='".$afml_route_to."',
  afml_icao_from='".$row_from['icao_code']."',
  afml_icao_to='".$row_to['icao_code']."',
  afml_block_on='".$afml_block_on2."',
  afml_block_off ='".$afml_block_off2."',
  afml_block_hrs='".$afml_block_hrs_new."',
  afml_to='".$afml_to2."',
  afml_ldg='".$afml_ldg2."',
  afml_flt_hrs='".$afml_flt_hrs_new."',
  afml_ldg_cycle='".$afml_ldg_cycle."',
  afml_fuel_rem='".$afml_fuel_rem."',
  afml_fuel_uplift='".$afml_fuel_uplift."',
  afml_fuel_total='".$afml_fuel_total."'
  WHERE afml_detail_id = '".$afml_detail_id."'
  LIMIT 1";
mysqli_query($conn,$sql);


/*--- dapatkan total BLOCK HRS, FLT HRS dan LDG untuk AFML number ini ---*/
$sqla = "SELECT SUM(afml_block_hrs) AS total_block_hrs, SUM(afml_flt_hrs) AS total_flt_hrs, sum(afml_ldg_cycle) as total_ldg FROM tbl_afml_detail WHERE afml_detail_id = '".$afml_detail_id."'";
$ha   = mysqli_query($conn,$sqla);
$rowa = mysqli_fetch_assoc($ha);
$total_flt_hrs = $rowa['total_flt_hrs'];
$total_ldg  = $rowa['total_ldg'];


$sql_update = "UPDATE tbl_aircraft_master SET 
aircraft_ac_total_hrs = '".$total_ac_hrs."',
aircraft_ac_total_ldg = '".$row_master['aircraft_ac_total_ldg']."'+$afml_ldg_cycle,
aircraft_eng_1_total_hrs = '".$total_eng_1_hrs."',
aircraft_eng_1_total_ldg = '".$row_master['aircraft_eng_1_total_ldg']."'+$afml_ldg_cycle,
aircraft_prop_total_hrs = '".$total_prop_hrs."' 
WHERE aircraft_reg_code = '".$aircraft_reg_code."' LIMIT 1";
//echo $sql_update.'<br/>';
mysqli_query($conn,$sql_update);

$sql_update_afml = "UPDATE tbl_afml SET 
this_page_ac_hrs = ".$brought_fwd_ac_hrs."+$selisih,
this_page_ac_ldg = '".$brought_fwd_ac_ldg."',
this_page_eng_1_hrs = ".$brought_fwd_eng_1_hrs."+$selisih,
this_page_eng_1_ldg = '".$brought_fwd_eng_1_ldg."',
this_page_prop_hrs = ".$brought_fwd_prop_hrs."+$selisih
WHERE afml_id = '".$afml_id."' LIMIT 1";
//echo $sql_update_afml;
mysqli_query($conn,$sql_update_afml);

$sql_afml_log = "INSERT INTO tbl_afml_detail_log (
  afml_detail_id,afml_page_no,afml_date,aircraft_reg_code,
  afml_route_from_old,afml_route_to_old,
  afml_icao_from_old,afml_icao_to_old,
  afml_block_on_old,afml_block_off_old,afml_block_hrs_old,
  afml_to_old,afml_ldg_old,afml_flt_hrs_old,
  afml_ldg_cycle_old,afml_fuel_rem_old,
  afml_fuel_uplift_old,afml_fuel_total_old,afml_receipt_no_old,
  afml_added_oil_old,afml_added_hyd_old,
  afml_route_from_new,afml_route_to_new,
  afml_icao_from_new,afml_icao_to_new,
  afml_block_on_new,afml_block_off_new,afml_block_hrs_new,
  afml_to_new,afml_ldg_new,afml_flt_hrs_new,
  afml_ldg_cycle_new,afml_fuel_rem_new,
  afml_fuel_uplift_new,afml_fuel_total_new,
  afml_receipt_no_new,afml_added_oil_new,
  afml_added_hyd_new,afml_fuel_date_new,afml_fuel_date_old,
  created_date,user_id,client_id)
  VALUES
  ('".$row1['afml_detail_id']."','".$row1['afml_page_no']."','".$row1['afml_date']."',
  '".$row1['aircraft_reg_code']."',
  '".$row1['afml_route_from']."','".$row1['afml_route_to']."',
  '".$row1['afml_icao_from']."','".$row1['afml_icao_to']."',
  '".$row1['afml_block_on']."','".$row1['afml_block_off']."','".$row1['afml_block_hrs']."',
  '".$row1['afml_to']."','".$row1['afml_ldg']."','".$row1['afml_flt_hrs']."',
  '".$row1['afml_ldg_cycle']."','".$row1['afml_fuel_rem']."',
  '".$row1['afml_fuel_uplift']."','".$row1['afml_fuel_total']."','".$row1['afml_receipt_no']."',
  '".$row1['afml_added_oil']."','".$row1['afml_added_hyd']."',
  '".$afml_route_from."','".$afml_route_to."',
  '".$row_from['icao_code']."','".$row_to['icao_code']."',
  '".$afml_block_on2."','".$afml_block_off2."','".$afml_block_hrs_new."',
  '".$afml_to2."','".$afml_ldg2."','".$afml_flt_hrs_new."',
  '".$afml_ldg_cycle."','".$afml_fuel_rem."',
  '".$afml_fuel_uplift."','".$afml_fuel_total."',
  '".$afml_receipt_no."','".$afml_added_oil."',
  '".$afml_added_hyd."','".$afml_fuel_date_f."','".$row1['afml_fuel_date']."',
  UTC_TIMESTAMP(), '".$_SESSION['user_id']."', '".$_SESSION['client_id']."'
  )";  
  //echo $sql_afml_log;
  mysqli_query($conn, $sql_afml_log);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AFML-DETAIL-EDIT','AFML','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'Edit AFML detail for ID: $afml_detail_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
header('location:afml-change-hour.php?ntf=999-'.$afml_id.'-94dfvj!sdf-349ffuaw');