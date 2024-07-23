<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$project_master_id   	= input_data(filter_var($_POST['project_master_id'],FILTER_SANITIZE_STRING));
$project_ledger_date 	= input_data(filter_var($_POST['project_ledger_date'],FILTER_SANITIZE_STRING));
$amount     		 	= input_data(filter_var($_POST['amount'],FILTER_SANITIZE_STRING));
$project_ledger_notes 	= input_data(filter_var($_POST['project_ledger_notes'],FILTER_SANITIZE_STRING));
$amount2    = str_replace(',', '', $amount);
$project_ledger_sn = $_SESSION['client_id'].date('dmyhis').round(microtime(true));

$project_ledger_date_y   = substr($project_ledger_date,6,4);
$project_ledger_date_m   = substr($project_ledger_date,3,2);
$project_ledger_date_d   = substr($project_ledger_date,0,2);
$project_ledger_date_f   = $project_ledger_date_y.'-'.$project_ledger_date_m.'-'.$project_ledger_date_d;

$temp 			= explode(".", $_FILES["upload_file"]["name"]);
$target_dir 	= "../uploads/petty-cash/";
$newfilename 	= date('dmyhis').round(microtime(true)) . '.' . end($temp);
$target_file 	= $target_dir.$newfilename;
$imageFileType 	= strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

//file harus < 1MB
if ($_FILES["upload_file"]["size"] > 1000001) {
?>
	<script type="text/javascript">
	Swal.fire({
	    type: 'error',
	    text: 'Maximum file size is 1MB'
	})
	</script>
<?php 
exit();
}

//file yang boleh diupload hanya PDF, WORD, XLS, JPG, PNG, GIF, PPT
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif" && $imageFileType != "jpeg" && $imageFileType != "pdf") {
?>
	<script type="text/javascript">
	Swal.fire({
	    type: 'error',
	    text: 'Allowed file type: JPG, GIF, PNG PDF'
	})
	</script>
<?php
exit();
}

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["upload_file"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file);

$sql  = "UPDATE tbl_project_engineer_team SET deposit = deposit-'".$amount2."' WHERE engineer_user_id = '".$_SESSION['user_id']."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
mysqli_query($conn,$sql);
$sql_balance 	= "SELECT project_ledger_last_balance FROM tbl_project_ledger WHERE engineer_user_id = '".$_SESSION['user_id']."' AND client_id = '".$_SESSION['client_id']."' AND project_master_id = '".$project_master_id."' ORDER BY created_date DESC LIMIT 1";
$h_balance 		= mysqli_query($conn,$sql_balance);
$row_balance 	= mysqli_fetch_assoc($h_balance);
$last_balance 	= $row_balance['project_ledger_last_balance']-$amount2;

$sql2  = "INSERT INTO tbl_project_ledger(project_master_id,project_ledger_date,project_ledger_sn,engineer_user_id,project_ledger_type,project_ledger_amount,project_ledger_last_balance,project_ledger_file,project_ledger_notes,created_date,user_id,client_id) VALUES ('".$project_master_id."','".$project_ledger_date_f."','".$project_ledger_sn."','".$_SESSION['user_id']."','INVOICE','".$amount2."','".$last_balance."','".$newfilename."','".$project_ledger_notes."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql2);
//echo $sql2.'<br/>';
 
//insert ke table log user
$sql5   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('PETTY-CASH-INVOICE','PETTY-CASH','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'Add invoice','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql5);

?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>