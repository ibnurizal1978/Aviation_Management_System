<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$project_ledger_id 	= $ntf[1];
$status 			= $ntf[3];
$engineer_user_id 	= $ntf[4];

$sql  = "UPDATE tbl_project_ledger SET project_ledger_paid_status = '".$status."',project_ledger_paid_status_date=UTC_TIMESTAMP() WHERE project_ledger_id = '".$project_ledger_id."' AND client_id = '".$_SESSION['client_id']."'";
//echo $sql;
//echo '<br/>';
mysqli_query($conn,$sql);

//add ke deposit
$sql2 = "SELECT project_master_id,project_ledger_amount,engineer_user_id FROM tbl_project_ledger WHERE project_ledger_id = '".$project_ledger_id."' LIMIT 1";
$h2 	= mysqli_query($conn,$sql2);
$row2 = mysqli_fetch_assoc($h2);

if($status==3) {
$sql3  = "UPDATE tbl_project_engineer_team SET deposit = deposit+'".$row2['project_ledger_amount']."' WHERE engineer_user_id = '".$engineer_user_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
//echo $sql3;
}
mysqli_query($conn,$sql3); 

header('location:petty-cash-detail.php?act=29dvi59&ntf=29dvi59-'.$row2['project_master_id'].'-'.$engineer_user_id.'-94dfvj!sdf-349ffuaw');
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>