<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

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

include("../assets/plugins/PHPExcel/IOFactory.php");    
$object = PHPExcel_IOFactory::load($_FILES["upload_file"]["tmp_name"]);  
foreach($object->getWorksheetIterator() as $worksheet)  {  
  $highestRow = $worksheet->getHighestRow();  
  for($row=2; $row<=$highestRow; $row++)  {  
    $do_number    = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(9, $row)->getValue());  
    $avtur_pertamina_date  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(13, $row)->getValue());
    $avtur_qty  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(16, $row)->getValue());
    $avtur_amount  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(22, $row)->getValue());
    $aircraft_reg_code  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(24, $row)->getValue());
    $iata_code_from  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(26, $row)->getValue());
    $iata_code_to  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(27, $row)->getValue());

    $unix_date = ($avtur_pertamina_date - 25569) * 86400;
    $excel_date = 25569 + ($unix_date / 86400);
    $unix_date = ($excel_date - 25569) * 86400;
    $avtur_pertamina_date2 = gmdate("Y-m-d", $unix_date);

    $do_number2 = substr($do_number, 3);
    $avtur_qty2 = $avtur_qty*1000;
    $aircraft_reg_code2 = preg_replace('/\s+/','', $aircraft_reg_code);
    $aircraft_reg_code3 = str_replace('-','', $aircraft_reg_code2);
    $aircraft_reg_code4 = $aircraft_reg_code3[0].$aircraft_reg_code3[1].'-'.$aircraft_reg_code3[2].$aircraft_reg_code3[3].$aircraft_reg_code3[4];
    $sql   = "INSERT INTO tbl_avtur_pertamina(do_number,avtur_pertamina_date,avtur_qty,avtur_amount,aircraft_reg_code,iata_code_from,iata_code_to,created_date,user_id,client_id) VALUES ('".$do_number2."','".$avtur_pertamina_date2."','".$avtur_qty2."','".$avtur_amount."','".$aircraft_reg_code4."','".$iata_code_from."','".$iata_code_to."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
    //echo '<br/>'.$sql.'<br/>';
    mysqli_query($conn,$sql);      
  }
}
//exit();

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AVTUR-PERTAMINA-ADD','AVTUR','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new from pertamina','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({title: "Data updated!",text: "",type: "success"}).then(function() {window.location = "avtur-pertamina.php";});
</script>