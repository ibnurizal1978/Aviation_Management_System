<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$avtur_price_from      = input_data(filter_var($_POST['avtur_price_from'],FILTER_SANITIZE_STRING));
$avtur_price_to    = input_data(filter_var($_POST['avtur_price_to'],FILTER_SANITIZE_STRING));

if($avtur_price_from == "" || $avtur_price_to == "") {
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

//date
$avtur_price_from_y   = substr($avtur_price_from,6,4);
$avtur_price_from_m   = substr($avtur_price_from,3,2);
$avtur_price_from_d   = substr($avtur_price_from,0,2);
$avtur_price_from_f   = $avtur_price_from_y.'-'.$avtur_price_from_m.'-'.$avtur_price_from_d;

$avtur_price_to_y   = substr($avtur_price_to,6,4);
$avtur_price_to_m   = substr($avtur_price_to,3,2);
$avtur_price_to_d   = substr($avtur_price_to,0,2);
$avtur_price_to_f   = $avtur_price_to_y.'-'.$avtur_price_to_m.'-'.$avtur_price_to_d;

$file_array = explode(".", $_FILES["upload_file"]["name"]);  

//file yang boleh diupload hanya PDF, WORD, XLS, JPG, PNG, GIF, PPT
if($file_array[1] != "xls" && $file_array[1] != "xlsx") {
?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    text: 'Allowed file type: EXCEL (XLS) '
})
</script>
<?php
exit();
$uploadOk = 0;
}

//apakah ada duplikat untuk periode mulai?
$sql  = "SELECT avtur_price_from FROM tbl_avtur_price WHERE avtur_price_from = '".$avtur_price_from_f."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate data'
  })
  </script>
<?php
  exit(); 
}

//apakah ada duplikat untuk periode hingga?
$sql  = "SELECT avtur_price_from FROM tbl_avtur_price WHERE avtur_price_to = '".$avtur_price_to_f."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate data'
  })
  </script>
<?php
  exit(); 
}


if($file_array[1] == "xls")  {  
  include("../assets/plugins/PHPExcel/IOFactory.php");
  //include("../assets/plugins/PHPExcel/PHPExcel.php");    
  $object = PHPExcel_IOFactory::load($_FILES["upload_file"]["tmp_name"]);  
  foreach($object->getWorksheetIterator() as $worksheet)  {  
    $highestRow = $worksheet->getHighestRow();  
    for($row=1; $row<=$highestRow; $row++)  {  
      $iata_code    = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(0, $row)->getValue());  
      $avtur_price  = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(1, $row)->getValue());
      $sql   = "INSERT INTO tbl_avtur_price (avtur_price_from,avtur_price_to,iata_code,avtur_price,created_date,user_id,client_id) VALUES ('".$avtur_price_from_f."','".$avtur_price_to_f."','".$iata_code."','".$avtur_price."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
      //echo $sql;
      mysqli_query($conn,$sql);      
    }
  }
} 

$sql_delete = "DELETE FROM tbl_avtur_price WHERE iata_code = ''";
mysqli_query($conn,$sql_delete);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AVTUR-PRICE-ADD','AVTUR','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new avtur for periode from: $avtur_price_from_y to $avtur_price_to_y','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>