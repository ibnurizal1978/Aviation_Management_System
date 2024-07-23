<?php 
session_start();
require_once "../config.php";
require_once "../check-session.php";

$user_id			        =	input_data(filter_var($_POST['user_id'],FILTER_SANITIZE_STRING));
$username			        =	input_data(filter_var($_POST['username'],FILTER_SANITIZE_STRING));
$full_name			      =	input_data(filter_var($_POST['full_name'],FILTER_SANITIZE_STRING));
$user_active_status	  =	input_data(filter_var($_POST['user_active_status'],FILTER_SANITIZE_STRING));
$department_id        = input_data(filter_var($_POST['department_id'],FILTER_SANITIZE_STRING));
$user_position        = input_data(filter_var($_POST['user_position'],FILTER_SANITIZE_STRING));
$user_birth_date      = input_data(filter_var($_POST['user_birth_date'],FILTER_SANITIZE_STRING));
$user_home_address    = input_data(filter_var($_POST['user_home_address'],FILTER_SANITIZE_STRING));
$user_email_address   = input_data(filter_var($_POST['user_email_address'],FILTER_SANITIZE_STRING));
$user_phone           = input_data(filter_var($_POST['user_phone'],FILTER_SANITIZE_STRING));
$user_manager_id           = input_data(filter_var($_POST['user_manager_id'],FILTER_SANITIZE_STRING));
$user_marital_status           = input_data(filter_var($_POST['user_marital_status'],FILTER_SANITIZE_STRING));


if($full_name == "") {
  //header('location:'.$base_url.$seller_url.'user.php?act=29dvi59&ntf=r827ao-'.$user_id.'-89t4hf34675dfoitrj!fn98s3');
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

if (preg_match('/\s/',$username)) {
  //header('location:'.$base_url.$seller_url.'user.php?act=29dvi59&ntf=whsp4ee-'.$user_id.'-89t4hf34675dfoitrj!fn98s3');
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Username only allow character and number'
  })
  </script>
<?php 
	exit();
}

//apakah ada duplikat name utk client id ini?
$sql 	= "SELECT username FROM tbl_user WHERE username = '".$username."' AND user_id <> '".$user_id."' LIMIT 1";
$h 		= mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
	//header('location:user.php?act=29dvi59&ntf=dpk739a-'.$user_id.'-89t4hf34675dfoitrj!fn98s3');
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Duplicate username'
  })
  </script>
<?php
	exit();	
}

//convert tanggal lahir
//tgl delivery tidak boleh lbh kecil dari tgl skrg
$user_birth_date_y   = substr($user_birth_date,6,4);
$user_birth_date_m   = substr($user_birth_date,0,2);
$user_birth_date_d   = substr($user_birth_date,3,2);
$user_birth_date_f   = $user_birth_date_y.'-'.$user_birth_date_m.'-'.$user_birth_date_d;

$sql2 	= "UPDATE tbl_user SET full_name='".$full_name."', user_phone = '".$user_phone."', user_home_address = '".$user_home_address."', user_email_address = '".$user_email_address."',user_marital_status = '".$user_marital_status."' WHERE user_id = '".$_SESSION['user_id']."' LIMIT 1";
mysqli_query($conn,$sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('USER-EDIT','USER','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'edit data for username: $username','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
//header('location:user.php?ntf=r1029wkwedt-89t4hf34675dfoitrj!fn98s3');
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>