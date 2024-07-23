<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$aircraft_master_id      = input_data(filter_var($_POST['aircraft_master_id'],FILTER_SANITIZE_STRING));

$file_array = explode(".", $_FILES["upload_file"]["name"]);  

//file yang boleh diupload hanya PDF, WORD, XLS, JPG, PNG, GIF, PPT
if($file_array[1] != "xls") {
?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    text: 'Allowed file type: EXCEL (XLS)'
})
</script>
<?php
exit();
$uploadOk = 0;
//echo 'no';
}


//apakah ada duplikat untuk periode hingga?
$sql_delete  = "DELETE FROM tbl_aircraft_parts WHERE aircraft_master_id = '".$aircraft_master_id."' AND client_id = '".$_SESSION['client_id']."'";
mysqli_query($conn,$sql_delete);


if($file_array[1] == "xls")  {  
  include("../assets/plugins/PHPExcel/IOFactory.php");
  //include("../assets/plugins/PHPExcel/PHPExcel.php");    
  $object = PHPExcel_IOFactory::load($_FILES["upload_file"]["tmp_name"]);  
  foreach($object->getWorksheetIterator() as $worksheet)  {  
    $highestRow = $worksheet->getHighestRow();  
    for($row=2; $row<=$highestRow; $row++)  {  
      $item_number      = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(0, $row)->getValue());  
      $ata_code       = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(1, $row)->getValue());
      $position       = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(2, $row)->getValue());
      $description    = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(3, $row)->getValue());
      $part_number    = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(4, $row)->getValue());
      $serial_number  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(5, $row)->getValue());
      $mth            = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(6, $row)->getValue());
      $hrs            = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(7, $row)->getValue());
      $ldg            = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(8, $row)->getValue());
      $installed_date = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(9, $row)->getValue());
      $excel_date = 43010; //here is that value 41621 or 41631
      $unix_date = ($installed_date - 25569) * 86400;
      $installed_date = 25569 + ($unix_date / 86400);
      $unix_date = ($installed_date - 25569) * 86400;
      $installed_date2 = gmdate("Y-m-d", $unix_date);

      //echo $installed_date.' ... ';
      $sql   = "INSERT INTO tbl_aircraft_parts (item_number,ata_code,position,description,part_number,serial_number,mth,hrs,ldg,installed_date,aircraft_master_id,created_date,user_id,client_id) VALUES ('".$item_number."','".$ata_code."','".$position."','".$description."','".$part_number."','".$serial_number."','".$mth."','".$hrs."','".$ldg."','".$installed_date2."','".$aircraft_master_id."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
      // echo $sql.'<br/>';
      mysqli_query($conn,$sql) or die(mysqli_error()  );      
    }
  }
} 

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AIRCRAFT-PARTS-UPLOAD','AIRCRAFT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'upload component for aircraft master ID: $aircraft_master_id','".$_SESSION['client_id']."')";
//mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
Swal.fire(  '', 'Data Updated!', 'success')
</script>