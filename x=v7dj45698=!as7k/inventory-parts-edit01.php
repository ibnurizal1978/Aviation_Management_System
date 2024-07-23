<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$parts_id			=	input_data(filter_var($_POST['parts_id'],FILTER_SANITIZE_STRING));
$parts_name			=	input_data(filter_var($_POST['parts_name'],FILTER_SANITIZE_STRING));
$parts_number		=	$_POST['parts_number'];
$serial_number		=	@$_POST['serial_number'];
$parts_stock		=	input_data(filter_var($_POST['parts_stock'],FILTER_SANITIZE_STRING));
$parts_price		=	input_data(filter_var($_POST['parts_price'],FILTER_SANITIZE_STRING));
$parts_rack_location_id	=	input_data(filter_var($_POST['parts_rack_location_id'],FILTER_SANITIZE_STRING));
$parts_price2       = str_replace(',', '', $parts_price);
$parts_treshold     = input_data(filter_var($_POST['parts_treshold'],FILTER_SANITIZE_STRING));

if($parts_name=="" ) {
	header('location:inventory-parts-transfer.php?ntf=r827ao-89t4hf34675dfoitrj!fn98s3');
  exit();
}

//check duplikat data
$sql  = "SELECT parts_number FROM tbl_parts WHERE parts_number = '".$parts_number."' AND client_id = '".$_SESSION['client_id']."' AND parts_id <> '".$parts_id."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate parts number'
  })
  </script>
<?php
  exit(); 
}

$sql = "UPDATE tbl_parts SET parts_name = '".$parts_name."',parts_number = '".$parts_number."', parts_treshold = '".$parts_treshold."', serial_number = '".$serial_number."', parts_stock = '".$parts_stock."', parts_price = '".$parts_price2."', parts_rack_location_id = '".$parts_rack_location_id."' WHERE parts_id = '".$parts_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
mysqli_query($conn,$sql);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('PARTS-UPDATE','PARTS','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'update parts Data','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
header('location:inventory-parts-transfer.php?ntf=r1029wkw-89t4hf34675dfoitrj!fn98s3');
?>