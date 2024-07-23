<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$aircraft_parts_id     = input_data(filter_var($_POST['aircraft_parts_id'],FILTER_SANITIZE_STRING));
$aircraft_master_id     = input_data(filter_var($_POST['aircraft_master_id'],FILTER_SANITIZE_STRING));
$aircraft_master_id2     = input_data(filter_var($_POST['aircraft_master_id2'],FILTER_SANITIZE_STRING));

if($aircraft_parts_id == "" || $aircraft_master_id == "" || $aircraft_master_id2 == "") {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill all forms'
  })
  </script>
<?php
  exit();
}

//cari PK aircraft old
$prev     = "SELECT aircraft_reg_code FROM tbl_aircraft_master WHERE aircraft_master_id = '".$aircraft_master_id."' LIMIT 1";
$h_prev   = mysqli_query($conn, $prev);
$row_prev = mysqli_fetch_assoc($h_prev);

//cari PK aircraft new
$aircraft_master_id2 = trim($aircraft_master_id2);
$new     = "SELECT aircraft_reg_code FROM tbl_aircraft_master WHERE aircraft_master_id = '".$aircraft_master_id2."' LIMIT 1";
$h_new   = mysqli_query($conn, $new);
$row_new = mysqli_fetch_assoc($h_new);
if(is_numeric($aircraft_master_id2)) {
  $new_location = $row_new['aircraft_reg_code'];
}else{
  $new_location = $aircraft_master_id2;
}

//cari description parts
$des     = "SELECT description FROM tbl_aircraft_parts WHERE aircraft_parts_id = '".$aircraft_parts_id."' LIMIT 1";
$h_des   = mysqli_query($conn, $des);
$row_des = mysqli_fetch_assoc($h_des);

//insert to parts history
$sql   = "INSERT INTO tbl_aircraft_parts_history (aircraft_parts_id,description,history_type,prev_location,new_location,created_date,user_id,full_name,client_id) VALUES ('".$aircraft_parts_id."', '".$row_des['description']."', 'MOVEMENT', '".$row_prev['aircraft_reg_code']."', '".$new_location."', UTC_TIMESTAMP(), '".$_SESSION['user_id']."', '".$_SESSION['full_name']."', '".$_SESSION['client_id']."')";
//echo $sql;
mysqli_query($conn,$sql);

//update tbl_parts
$sql_update = "UPDATE tbl_aircraft_parts SET aircraft_master_id = '".$aircraft_master_id2."' WHERE aircraft_parts_id = '".$aircraft_parts_id."'";
mysqli_query($conn, $sql_update);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('PARTS-MOVEMENT','PARTS','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'move parts with ID: $aircraft_parts_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({title: "Data updated!",text: "",type: "success"}).then(function() {window.location = "maintenance-detail.php??act=29dvi59&aircraft_master_id=<?php echo $aircraft_master_id ?>&ntf=29dvi59-<?php echo $aircraft_master_id ?>-94dfvj!sdf-349ffuaw";});
</script>