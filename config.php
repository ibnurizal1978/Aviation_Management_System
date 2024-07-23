<?php
$base_url 		= "https://ams.satukode.com/";
//$base_url 		= "http://localhost:8888/pantera.id/application/mnk.pantera.id/v2/";
$seller_url 	= 'x=v7dj45698=!as7k/';
$buyer_url 		= 'b9y6digk56js!3s=u9105/';
$url 			= explode("/",$_SERVER["REQUEST_URI"]); 

/*
panduan URL:

1. notification: ntf[0]
2. ID: ntf[1]
3. pengacak: ntf[3]
*/
$ntf = explode("-", @$_GET['ntf']);

//set timezone jadi default
date_default_timezone_set('UTC');

//renewal ssl
//sudo certbot --apache -d ams.satukode.com

//get filename
$url = $_SERVER['PHP_SELF'];
$filename = pathinfo(parse_url($url, PHP_URL_PATH));

//ini kalau mau ambil nama file aja
$file 	= $filename['filename'];

//ini kalau mau ambil extension. Kalau nggak mau extension, dicomment aja trs uncomment bawahnya
//$ext 	= '.'.$filename['extension'];
$ext 	= '';

function input_data($data){
$filter = stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)));
return $filter;
}

$db_server        = '127.0.0.1';
$db_user          = 'ams';
$db_password      = 'Database@123';
//$db_password      = 'D0d0lg4rut';
$db_name          = 'db_aviation';
$conn 			  = new mysqli($db_server,$db_user,$db_password,$db_name) or die (mysqli_error());
?>