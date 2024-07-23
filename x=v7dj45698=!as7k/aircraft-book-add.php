<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$aircraft_master_id      = input_data(filter_var($_POST['aircraft_master_id'],FILTER_SANITIZE_STRING));
$aircraft_book_number_from    = input_data(filter_var($_POST['aircraft_book_number_from'],FILTER_SANITIZE_STRING));
$aircraft_book_number_to     = input_data(filter_var($_POST['aircraft_book_number_to'],FILTER_SANITIZE_STRING));

if($aircraft_book_number_from == "" || $aircraft_book_number_to == "") {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill forms'
  })
  </script>
<?php
  exit();
}

//apakah ada duplikat di number start?
$sql  = "SELECT * FROM tbl_aircraft_book WHERE aircraft_master_id = '".$aircraft_master_id."' AND ('".$aircraft_book_number_from."' BETWEEN aircraft_book_number_from AND aircraft_book_number_to)";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Beginning AFML number is already existed'
  })
  </script>
<?php
  exit(); 
}

//apakah ada duplikat di number end?
$sql  = "SELECT * FROM tbl_aircraft_book WHERE aircraft_master_id = '".$aircraft_master_id."' AND ('".$aircraft_book_number_to."' BETWEEN aircraft_book_number_from AND aircraft_book_number_to)";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Last AFML number is already existed'
  })
  </script>
<?php
  exit(); 
}

$sql2   = "INSERT INTO tbl_aircraft_book (aircraft_master_id,aircraft_book_number_from,aircraft_book_number_to,created_date,user_id,client_id) VALUES ('".$aircraft_master_id."','".$aircraft_book_number_from."','".$aircraft_book_number_to."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AIRCRAFT-ADD','AIRCRAFT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new aircraft book','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>