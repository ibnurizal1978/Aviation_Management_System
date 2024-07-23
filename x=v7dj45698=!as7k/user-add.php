<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$username           = input_data(filter_var($_POST['username'],FILTER_SANITIZE_STRING));
$full_name          = input_data(filter_var($_POST['full_name'],FILTER_SANITIZE_STRING));
$txt_password       = input_data(filter_var($_POST['txt_password'],FILTER_SANITIZE_STRING));
$txt_password2      = input_data(filter_var($_POST['txt_password2'],FILTER_SANITIZE_STRING));
$department_id      = input_data(filter_var($_POST['department_id'],FILTER_SANITIZE_STRING));
$user_position      = input_data(filter_var($_POST['user_position'],FILTER_SANITIZE_STRING));
$user_birth_date    = input_data(filter_var($_POST['user_birth_date'],FILTER_SANITIZE_STRING));
$user_home_address  = input_data(filter_var($_POST['user_home_address'],FILTER_SANITIZE_STRING));
$user_email_address = input_data(filter_var($_POST['user_email_address'],FILTER_SANITIZE_STRING));
$user_phone         = input_data(filter_var($_POST['user_phone'],FILTER_SANITIZE_STRING));
$user_manager_id         = input_data(filter_var($_POST['user_manager_id'],FILTER_SANITIZE_STRING));

//date
$user_birth_date_y   = substr($user_birth_date,6,4);
$user_birth_date_m   = substr($user_birth_date,3,2);
$user_birth_date_d   = substr($user_birth_date,0,2);
$user_birth_date_f   = $user_birth_date_y.'-'.$user_birth_date_m.'-'.$user_birth_date_d;


if($username == "" || $full_name == "" || $txt_password == "" || $txt_password2 == "" || $department_id =="") {
  //header('location:'.$base_url.$seller_url.'user.php?ntf=r827ao-89t4hf34675dfoitrj!fn98s3');
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill all aforms'
  })
  </script>
<?php
  exit();
}

if (preg_match('/\s/',$username)) {
  //header('location:'.$base_url.$seller_url.'user.php?ntf=whsp4ee-89t4hf34675dfoitrj!fn98s3');
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
$sql  = "SELECT username FROM tbl_user WHERE username = '".$username."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
if(mysqli_num_rows($h)>0) {
  //header('location:user.php?ntf=dpk739a-89t4hf34675dfoitrj!fn98s3');
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


if(strlen($txt_password)<6) {
  //header('location:user.php?ntf=ado67od7-89t4hf34675dfoitrj!fn98s3');
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Minimum length for password is 6 characters, consist of minimum one uppercase, one number and alphabet'
  })
  </script>
<?php
  exit(); 
} 

if($txt_password <> $txt_password2) {
  //header('location:user.php?ntf=qgzrts2dk733-89t4hf34675dfoitrj!fn98s3');
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Both of passwords does not equal'
  })
  </script>
<?php
exit();
}

function valid_pass($txt_password) {
    if (!preg_match_all('$\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\s*$', $txt_password))
        return FALSE;
    return TRUE;
}
/*
    Explaining $\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$
    $ = beginning of string
    \S* = any set of characters
    (?=\S{8,}) = of at least length 8
    (?=\S*[a-z]) = containing at least one lowercase letter
    (?=\S*[A-Z]) = and at least one uppercase letter
    (?=\S*[\d]) = and at least one number
    (?=\S*[\W]) = and at least a special character (non-word characters)
    $ = end of the string

 */
 if(!valid_pass($txt_password)) { 
  //header('location:user.php?ntf=ado67od7-89t4hf34675dfoitrj!fn98s3');
?>
  <script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Minimum length for password is 6 characters, consist of minimum one uppercase, one number and alphabet'
  })
  </script>
<?php
  exit(); 
}

function generateRandomString($length = 10) {
    $characters = '=&!#$?/*0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$pass = generateRandomString().md5($txt_password2);

$sql2   = "INSERT INTO tbl_user (client_id,username,password,full_name,user_created_date,user_active_status,user_timezone,department_id,user_position,user_home_address,user_email_address,user_phone,user_birth_date,user_manager_id) VALUES ('".$_SESSION['client_id']."','".$username."','".$pass."','".$full_name."',UTC_TIMESTAMP(),1,'".$_SESSION['user_timezone']."','".$department_id."','".$user_position."','".$user_home_address."','".$user_email_address."','".$user_phone."','".$user_birth_date_f."','".$user_manager_id."')";
mysqli_query($conn,$sql2);
$last_id = mysqli_insert_id($conn);

$banyaknya = count(@$_POST['nav_menu_id']);
for ($i=0; $i<$banyaknya; $i++) {
  if(@$_POST['nav_menu_id'][$i]) {
    $sql_menu = "SELECT nav_menu_id from tbl_nav_menu WHERE nav_menu_id = '".@$_POST['menu_id'][$i]."'";
    $h_menu   = mysqli_query($conn,$sql_menu);
    $row_menu = mysqli_fetch_assoc($h_menu);
  $sql_menu2  = "INSERT INTO tbl_nav_user(nav_menu_id,user_id,client_id) VALUES ('".@$_POST['nav_menu_id'][$i]."','".$last_id."','".$_SESSION['client_id']."')";
  mysqli_query($conn,$sql_menu2);
  //echo $sql_menu2.'<br/>';
  }
}

if ($department_id==1) {
  $sql_cert = "SELECT certificate_master_id, certificate_master_name FROM tbl_certificate_master WHERE department_id = 1";
  $h_cert   = mysqli_query($conn,$sql_cert);
  while($row_cert = mysqli_fetch_assoc($h_cert)) {
    $sql4   = "INSERT INTO tbl_user_certificate (user_id,certificate_master_id,client_id) VALUES ('".$last_id."','".$row_cert['certificate_master_id']."','".$_SESSION['client_id']."')";
  mysqli_query($conn,$sql4);
  } 
}

if ($department_id==4) {
$sql4   = "INSERT INTO tbl_pilot_data (user_id,client_id) VALUES ('".$last_id."','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql4); 
}

//insert ke table log user
$sql5   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('USER-ADD','MASTER-USER','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'username: $username','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql5);


//header('location:user.php?ntf=r1029wkw-89t4hf34675dfoitrj!fn98s3');
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>