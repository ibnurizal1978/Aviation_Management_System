<?php  
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$parts_photo_id  = input_data(filter_var($ntf[1],FILTER_SANITIZE_STRING));

$query = "DELETE FROM tbl_parts_photo WHERE parts_photo_id = '".$parts_photo_id."' AND client_id ='".$_SESSION['client_id']."' LIMIT 1";
mysqli_query($conn,$query);
header('location:inventory-parts.php?ntf=29dvi59-6eiysx3c2ak9di');
 ?>  