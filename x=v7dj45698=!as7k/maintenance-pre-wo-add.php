<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$aircraft_parts_id           = input_data(filter_var($_POST['aircraft_parts_id'],FILTER_SANITIZE_STRING));
$aircraft_master_id           = input_data(filter_var($_POST['aircraft_master_id'],FILTER_SANITIZE_STRING));
$due_value2           = input_data(filter_var($_POST['due_value'],FILTER_SANITIZE_STRING));
$projection_type2           = input_data(filter_var($_POST['projection_type'],FILTER_SANITIZE_STRING));
$tanggal_detail           = input_data(filter_var($_POST['tanggal_detail'],FILTER_SANITIZE_STRING));
$ac_hrs_target           = input_data(filter_var($_POST['ac_hrs_target'],FILTER_SANITIZE_STRING));
$ac_ldg_target           = input_data(filter_var($_POST['ac_ldg_target'],FILTER_SANITIZE_STRING));
$eng_hrs_target           = input_data(filter_var($_POST['eng_hrs_target'],FILTER_SANITIZE_STRING));
$eng_ldg_target           = input_data(filter_var($_POST['eng_ldg_target'],FILTER_SANITIZE_STRING));
$prop_hrs_target           = input_data(filter_var($_POST['prop_hrs_target'],FILTER_SANITIZE_STRING));
$reg_code               = input_data(filter_var(substr($_POST['reg_code'],3,3),FILTER_SANITIZE_STRING));

//bikin angka romawi dari angka   bulan
function getRomanNumerals($decimalInteger) 
{
 $n = intval($decimalInteger);
 $res = '';

 $roman_numerals = array(
    'M'  => 1000,
    'CM' => 900,
    'D'  => 500,
    'CD' => 400,
    'C'  => 100,
    'XC' => 90,
    'L'  => 50,
    'XL' => 40,
    'X'  => 10,
    'IX' => 9,
    'V'  => 5,
    'IV' => 4,
    'I'  => 1);

 foreach ($roman_numerals as $roman => $numeral) 
 {
  $matches = intval($n / $numeral);
  $res .= str_repeat($roman, $matches);
  $n = $n % $numeral;
 }

 return $res;
}

//check apakah udah di centang?
$banyaknya = count(@$_POST['aircraft_parts_id']);
if($banyaknya == 0) {
   header('location:maintenance-detail.php?aircraft_parts_id='.$aircraft_parts_id.'&aircraft_master_id='.$aircraft_master_id.'&due_value2='.$due_value2.'&projection_type2='.$projection_type2.'&tanggal_detail='.$tanggal_detail.'&ac_hrs_target='.$ac_hrs_target.'&ac_ldg_target='.$ac_ldg_target.'&eng_hrs_target='.$eng_hrs_target.'&eng_ldg_target='.$eng_ldg_target.'&prop_hrs_target='.$prop_hrs_target.'&ntf=r827ao-89t4hf34675dfoitrj!fn98s3');
  exit();   
}

//input ke tbl maintenance wo
$wo_number  = 'WO-'.date('ymd').'-'.$reg_code.'-'.getRomanNumerals(date('m')).'-'.date('Y');
$wo_trx_id  = date('dmyhis');
$banyaknya  = count(@$_POST['aircraft_parts_id']);
for ($i=0; $i<$banyaknya; $i++) {
  if(@$_POST['aircraft_parts_id'][$i]) {
    $sql_menu2  = "INSERT INTO tbl_maintenance_wo (wo_trx_id, aircraft_master_id, aircraft_parts_id, wo_number, created_date,user_id,client_id) VALUES ('".$wo_trx_id."','".$aircraft_master_id."', '".@$_POST['aircraft_parts_id'][$i]."', '".$wo_number."', UTC_TIMESTAMP(), '".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
    mysqli_query($conn,$sql_menu2);
    //echo $sql_menu2.'<br/>';
  }
}


//insert ke table log user
$sql5   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('WO','WO','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'CREATE WO','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql5);

header('location:maintenance-wo1-new.php?q=wroienjfsldm&ntf='.$wo_trx_id);
?>