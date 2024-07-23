<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
include "../config.php";

$txt_username	=	input_data(filter_var($_POST['txt_username'],FILTER_SANITIZE_STRING));
$txt_password	= md5($_POST['txt_password']); 
//$txt_password	=	input_data(md5($_POST['txt_password']));

if($txt_username=='' || $txt_username == "Username") {
	header('location:'.$base_url.'index.php?r=80t1zjysirkvk769s8dvs');
exit();
}

//apakah client ID ini aktif atau non aktif?
$sql 	= "SELECT user_photo,client_active_status,user_id,username,full_name,client_id,user_active_status,user_timezone,department_id,user_manager_id FROM tbl_client a INNER JOIN tbl_user b USING (client_id) WHERE username = '".$txt_username."' AND substr(password,11,100) = '".$txt_password."' LIMIT 1";
$h 		= mysqli_query($conn,$sql);
$row 	= mysqli_fetch_assoc($h);

if($row['client_active_status']=='N') {
	header('location:'.$base_url.'index.php?r=8xt1zjysirkvk769s8dvs');
	exit();
}

if($row['username']<>$txt_username) {
	header('location:'.$base_url.'index.php?r=8vt1zjysirkvk769s8dvs');
	exit();
}

if($row['user_active_status']<>1) {
	header('location:'.$base_url.'index.php?r=8nt1zjysirkvk769s8dvs');
	exit();
}



$user_id 			  = $row['user_id'];
$user_photo      = $row['user_photo'];
$user_manager_id      = $row['user_manager_id'];
$username 			= $row['username'];
$full_name			= $row['full_name'];
$client_id 			= $row['client_id'];
$user_timezone		= $row['user_timezone'];
$department_id		= $row['department_id'];
$sql		= "update tbl_user SET user_last_login = UTC_TIMESTAMP() where user_id='".$user_id."' limit 1";
$h2 		= mysqli_query($conn,$sql);

//cara nampilin time berdasarkan timezone
//date_default_timezone_set($row['user_timezone']);
//echo 'date and time is ' . date('Y-m-d H:i:s');

$sql2		= "insert into tbl_user_log('".$user_id."','LOGIN',UTC_TIMESTAMP()";
$h2			= mysqli_query($conn,$sql2);

$_SESSION['user_id']			= $user_id;
$_SESSION['user_manager_id']      = $user_manager_id;
$_SESSION['user_photo']      = $user_photo;
$_SESSION['full_name']			= $full_name;
$_SESSION['username']			= $username;
$_SESSION['client_id']			= $client_id;
$_SESSION['department_id']		= $department_id;
$_SESSION['user_timezone']		= $user_timezone;

$sql3 	= "SELECT * FROM tbl_client WHERE client_id = '".$_SESSION['client_id']."' limit 1";
$h3 	= mysqli_query($conn,$sql3);
$row3 	= mysqli_fetch_assoc($h3);
$_SESSION['client_name']	= $row3['client_name'];
$_SESSION['client_address']			= $row3['client_address'];
$_SESSION['client_phone']			= $row3['client_phone'];
$_SESSION['client_email_address']	= $row3['client_email_address'];
$_SESSION['client_package']			= $row3['client_package'];

//timezone
$serverTimezoneOffset 	= (date("O") / 100 * 60 * 60);
$clientTimezoneOffset 	= $_POST["timezoneoffset"];
$serverTime 			= time();
$serverClientTimeDifference = $clientTimezoneOffset-$serverTimezoneOffset;
$clientTime 			= $serverTime+$serverClientTimeDifference;
$_SESSION['selisih'] 	= ($serverClientTimeDifference/(60*60));

//tampilkan INITIAL jam 

//tampilkan menu berdasarkan level
$sql_nav_header = "SELECT nav_header_id,nav_header_icon,nav_header_name FROM tbl_nav_user a INNER JOIN tbl_nav_menu b using (nav_menu_id) INNER JOIN tbl_nav_header USING (nav_header_id) WHERE user_id = '".$user_id."' GROUP BY nav_header_id ORDER by nav_menu_name";
$h_nav_header = mysqli_query($conn,$sql_nav_header);
echo $sql_nav_header;
while($row_menu_header = mysqli_fetch_assoc($h_nav_header)) {
	$_SESSION['nav_header'][]= array('header_id' => $row_menu_header['nav_header_id'],'header_icon' => $row_menu_header['nav_header_icon'],'header_name' => $row_menu_header['nav_header_name']);

	$sql_menu 	= "SELECT nav_header_id,nav_menu_name, nav_menu_url FROM tbl_nav_user a INNER JOIN tbl_nav_menu b using (nav_menu_id) WHERE user_id = '".$user_id."' AND nav_header_id = '".$row_menu_header['nav_header_id']."' ORDER by nav_menu_name";
	echo $sql_menu.'<br/>';
	$h_menu 	= mysqli_query($conn,$sql_menu);
	while($row_menu = mysqli_fetch_assoc($h_menu)) {
		$_SESSION['nav_items'][]= array('url' => $row_menu['nav_menu_url'], 'name' => $row_menu['nav_menu_name'],'nav_header_id' => $row_menu['nav_header_id']);
	}
}
if($_SESSION['department_id']==8) {
  header('location:'.$base_url.$seller_url.'report.php');
}else{
  header('location:'.$base_url.$seller_url.'main-content.php');
}
?>

<!-- Page JS Code -->
<script>
    jQuery(function () {
        // Init page helpers (Slick Slider plugin)
        Codebase.helpers('slick');
    });
</script>

<script type="text/javascript">
window.addEventListener('load', () => {
  if (!('serviceWorker' in navigator)) {
    // service workers not supported
    return
  }

  navigator.serviceWorker.register('../sw-ams.js').then(
    () => {
      // registered!
      //console.log('oke!', err)
    },
    err => {
      console.error('SW registration failed!', err)
    }
  )
})  
</script>