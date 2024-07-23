<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$airnav_invoice_number   = input_data(filter_var(trim($_POST['airnav_invoice_number']),FILTER_SANITIZE_STRING));

if($_FILES["upload_file"]["name"] == "") {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please choose file'
  })
  </script>
<?php
  exit();
}

$info = new SplFileInfo($_FILES["upload_file"]["name"]);
$e = $info->getExtension();
//exit();

//file yang boleh diupload hanya XLS
if($e != "xls") {
?>
  <script type="text/javascript">
  Swal.fire({ type: 'error', text: 'Allowed file type: XLS' })
  </script>
<?php
exit();
$uploadOk = 0;
}

//delete yang data duplikat dgn yang sekarang
$sql = "delete from tbl_airnav_invoice WHERE airnav_invoice_number = '".$airnav_invoice_number."'";
mysqli_query($conn,$sql); 

include("../assets/plugins/PHPExcel/IOFactory.php");    
$object = PHPExcel_IOFactory::load($_FILES["upload_file"]["tmp_name"]);  
foreach($object->getWorksheetIterator() as $worksheet)  {  
  $highestRow = $worksheet->getHighestRow();  
  for($row=8; $row<=$highestRow; $row++)  {  
    $airnav_invoice_from    = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(1, $row)->getValue());  
    $airnav_invoice_to  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(2, $row)->getValue());
    $airnav_invoice_date  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(3, $row)->getValue());
    $airnav_reg_code  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(4, $row)->getValue());
    $airnav_time_in  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(8, $row)->getValue());
    $airnav_time_out  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(10, $row)->getValue());

    $airnav_invoice_date2 = substr($airnav_invoice_date,6,4).'-'.substr($airnav_invoice_date,3,2).'-'.substr($airnav_invoice_date,0,2);
    $airnav_reg_code2 = substr($airnav_reg_code,0,2).'-'.substr($airnav_reg_code,2,6);

    $sql   = "INSERT INTO tbl_airnav_invoice(airnav_invoice_from,airnav_invoice_to,airnav_invoice_date,airnav_reg_code,airnav_invoice_number,airnav_time_in, airnav_time_out, created_date,user_id,client_id) VALUES ('".$airnav_invoice_from."','".$airnav_invoice_to."','".$airnav_invoice_date2."','".$airnav_reg_code2."','".$airnav_invoice_number."','".$airnav_time_in."','".$airnav_time_out."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
    //echo '<br/>'.$sql.'<br/>';
    mysqli_query($conn,$sql); 
  }
}

$sql2 = "delete from tbl_airnav_invoice WHERE airnav_invoice_from = ''";
mysqli_query($conn,$sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AIRNAV-INVOICE-ADD','AVTUR','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new airnav invoice','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({title: "Data updated!",text: "",type: "success"}).then(function() {window.location = "airnav-new.php";});
</script>