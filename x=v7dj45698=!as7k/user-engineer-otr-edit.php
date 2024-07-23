<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$user_otr_id    		= input_data(filter_var($_POST['user_otr_id'],FILTER_SANITIZE_STRING));
$user_id    			= input_data(filter_var($_POST['user_id'],FILTER_SANITIZE_STRING));
$amel_no    			= input_data(filter_var($_POST['amel_no'],FILTER_SANITIZE_STRING));
$amel_validity_date    	= input_data(filter_var($_POST['amel_validity_date'],FILTER_SANITIZE_STRING));
$otr_no    				= input_data(filter_var($_POST['otr_no'],FILTER_SANITIZE_STRING));
$otr_validity_date    	= input_data(filter_var($_POST['otr_validity_date'],FILTER_SANITIZE_STRING));
$general_license    	= input_data(filter_var($_POST['general_license'],FILTER_SANITIZE_STRING));
$aircraft_type    		= input_data(filter_var($_POST['aircraft_type'],FILTER_SANITIZE_STRING));
$authorized_limitation  = input_data(filter_var($_POST['authorized_limitation'],FILTER_SANITIZE_STRING));
@$engine_run_up    		= input_data(filter_var($_POST['engine_run_up'],FILTER_SANITIZE_STRING));
@$weight_balance    		= input_data(filter_var($_POST['weight_balance'],FILTER_SANITIZE_STRING));
@$compass_swing    		= input_data(filter_var($_POST['compass_swing'],FILTER_SANITIZE_STRING));
@$boroscope    			= input_data(filter_var($_POST['boroscope'],FILTER_SANITIZE_STRING));
$remark    				= input_data(filter_var($_POST['remark'],FILTER_SANITIZE_STRING));


//date
$amel_validity_date_y   = substr($amel_validity_date,6,4);
$amel_validity_date_m   = substr($amel_validity_date,3,2);
$amel_validity_date_d   = substr($amel_validity_date,0,2);
$amel_validity_date_f   = $amel_validity_date_y.'-'.$amel_validity_date_m.'-'.$amel_validity_date_d;

$otr_validity_date_y   = substr($otr_validity_date,6,4);
$otr_validity_date_m   = substr($otr_validity_date,3,2);
$otr_validity_date_d   = substr($otr_validity_date,0,2);
$otr_validity_date_f   = $otr_validity_date_y.'-'.$otr_validity_date_m.'-'.$otr_validity_date_d;


//apakah data sudah ada sebelumnya?
$sql_next 	= "SELECT user_otr_id FROM tbl_user_otr where user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h_next 	= mysqli_query($conn,$sql_next);
if(mysqli_num_rows($h_next)>0) {

	$sql 	= "UPDATE tbl_user_otr SET amel_no = '".$amel_no."',amel_validity_date = '".$amel_validity_date_f."',
		otr_no = '".$otr_no."', 
		otr_validity_date = '".$otr_validity_date_f."',
		general_license = '".$general_license."',
		aircraft_type = '".$aircraft_type."',
		authorized_limitation = '".$authorized_limitation."',
		engine_run_up = '".$engine_run_up."',
		weight_balance = '".$weight_balance."',
		compass_swing = '".$compass_swing."',
		boroscope = '".$boroscope."',
		remark = '".$remark."',
		updated_date = UTC_TIMESTAMP(),
		updated_user_id = '".$_SESSION['user_id']."'
		WHERE user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";		
}else{
	$sql 	= "INSERT INTO tbl_user_otr(user_id,amel_no,amel_validity_date,otr_no,otr_validity_date,general_license,aircraft_type,authorized_limitation,engine_run_up,weight_balance,compass_swing,boroscope,remark,created_date,created_user_id,client_id) VALUES ('".$user_id."','".$amel_no."','".$amel_validity_date_f."','".$otr_no."','".$otr_validity_date_f."','".$general_license."','".$aircraft_type."','".$authorized_limitation."','".$engine_run_up."','".$weight_balance."','".$compass_swing."','".$boroscope."','".$remark."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
}
mysqli_query($conn,$sql);


//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('USER-ENGINEER-ADD-OTR','OTR','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new OTR data for user_id: $user_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"});
</script>