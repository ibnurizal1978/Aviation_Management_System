<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$afml_deposit_date  = input_data(filter_var($_POST['afml_deposit_date'],FILTER_SANITIZE_STRING));
$deposit    		= input_data(filter_var($_POST['deposit'],FILTER_SANITIZE_STRING));
$deposit2   		= str_replace(',', '', $deposit);
$trx_id 			= $_SESSION['client_id'].date('dmyhis').round(microtime(true));
$afml_receipt_no 	= 'DEPO-'.date('dmyhis');
$trx_to     		= input_data(filter_var($_POST['trx_to'],FILTER_SANITIZE_STRING));

if($deposit=='') {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill form'
  })
  </script>
<?php
  exit();
}

//date
$afml_deposit_date_y   = substr($afml_deposit_date,6,4);
$afml_deposit_date_m   = substr($afml_deposit_date,3,2);
$afml_deposit_date_d   = substr($afml_deposit_date,0,2);
$afml_deposit_date_f   = $afml_deposit_date_y.'-'.$afml_deposit_date_m.'-'.$afml_deposit_date_d;


$sql_balance 	= "SELECT saldo_akhir FROM tbl_avtur_afml WHERE client_id = '".$_SESSION['client_id']."' ORDER BY avtur_afml_id DESC LIMIT 1";
$h_balance 		= mysqli_query($conn,$sql_balance);
$row_balance 	= mysqli_fetch_assoc($h_balance);
$last_balance 	= $row_balance['saldo_akhir']+$deposit2;

$sql2  = "INSERT INTO tbl_avtur_afml(afml_fuel_date,afml_notes,transaction_type,afml_receipt_no, afml_price,saldo_awal,saldo_akhir, transaction_belong_to,created_date,user_id,full_name,client_id) VALUES ('".$afml_deposit_date_f."','Deposit','K','".$afml_receipt_no."','".$deposit2."','".$row_balance['saldo_akhir']."','".$last_balance."','".$trx_to."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['full_name']."','".$_SESSION['client_id']."')";
//echo $sql2;
mysqli_query($conn,$sql2);
 
//insert ke table log user
$sql5   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('DEPOSIT-ADD','AVTUR','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'Add new deposit','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql5);

?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>