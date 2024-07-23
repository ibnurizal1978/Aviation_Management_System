<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$id     = input_data(filter_var($_GET['id'],FILTER_SANITIZE_STRING));

$sql 	= "DELETE FROM tbl_parts_adj_log WHERE id = '".$id."' LIMIT 1";
$h 		= mysqli_query($conn, $sql);

echo "<script>";
echo "alert('Success!'); window.location.href=history.back()";
echo "</script>";
exit();
?>