<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$user_id			=	input_data(filter_var($_POST['user_id'],FILTER_SANITIZE_STRING));
$txt_password		=	input_data(filter_var($_POST['txt_password'],FILTER_SANITIZE_STRING));
$txt_password2		=	input_data(filter_var($_POST['txt_password2'],FILTER_SANITIZE_STRING));
$int    = input_data(filter_var($_POST['int'],FILTER_SANITIZE_STRING));


if($user_id=="" || $txt_password == "" || $txt_password2 == "") {
	//header('location:'.$base_url.$seller_url.'user.php?act=29dvi59&ntf=r827aop-'.$user_id.'-89t4hf34675dfoitrj!fn98s3#p');
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

if(strlen($txt_password)<6) {
	//header('location:user.php?act=29dvi59&ntf=ado67od7p-'.$user_id.'-89t4hf34675dfoitrj!fn98s3#p');
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
//	header('location:user.php?act=29dvi59&ntf=qgzrts2dk733p-'.$user_id.'-89t4hf34675dfoitrj!fn98s3#p');
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
?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    text: 'Minimum length for password is 6 characters, consist of minimum one uppercase, one number and alphabet'
})
</script>
<?php	
 	//header('location:user.php?act=29dvi59&ntf=ado67od7p-'.$user_id.'-89t4hf34675dfoitrj!fn98s3#p');
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

$sql2 	= "UPDATE tbl_user SET password='".$pass."' WHERE user_id = '".$user_id."' LIMIT 1";
mysqli_query($conn,$sql2);

//insert ke table log user
$sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('USER-CHANGE-PASSWORD','USER','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'change password for user_id: $user_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>