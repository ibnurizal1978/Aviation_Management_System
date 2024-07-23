<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$parts_id		=	input_data(filter_var($_POST['parts_id'],FILTER_SANITIZE_STRING));
$parts_stock	=	input_data(filter_var($_POST['parts_stock'],FILTER_SANITIZE_STRING));
$parts_type		=	input_data(filter_var($_POST['parts_type'],FILTER_SANITIZE_STRING));

if($parts_stock=="" || $parts_type == "") {
	header('location:'.$base_url.$seller_url.'inventory-parts-stock.php?ntf=r827ao-89t4hf34675dfoitrj!fn98s3');
exit();
}

//harusnya kalau dia kurangi stok nggak boleh lbh banyak dari existing stok
if($parts_type=='LESS') {
	$sql_cek 	= "SELECT parts_stock,parts_broken,parts_replacement FROM tbl_parts WHERE parts_id = '".$parts_id."' LIMIT 1";
	$h_cek 		= mysqli_query($conn,$sql_cek);
	$row_cek	= mysqli_fetch_assoc($h_cek);
	$totalnya 	= $row_cek['parts_stock']-($row_cek['parts_broken']+$row_cek['parts_replacement']);
	if($totalnya<$parts_stock) {
		header('location:'.$base_url.$seller_url.'inventory-parts-stock.php?ntf=k7ao3AAfowk8w3___ak7do2-89t4hf34675dfoitrj!fn98s3');
		exit();
	}
}

$sql 		= "INSERT INTO tbl_parts_stock (parts_id,parts_stock_transaction_type,parts_stock_amount,parts_stock_created_date,parts_stock_created_by_id,parts_stock_created_by_fullname,client_id) VALUES ('".$parts_id."','".$parts_type."','".$parts_stock."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['fullname']."','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql);

if($parts_type=='ADD') {
	$sql2 		= "UPDATE tbl_parts SET parts_stock = parts_stock+".$parts_stock.",parts_ready_to_use = parts_ready_to_use+".$parts_stock." WHERE parts_id = '".$parts_id."' LIMIT 1";
}else{
	$sql2 		= "UPDATE tbl_parts SET parts_stock = parts_stock-".$parts_stock.",parts_ready_to_use = parts_ready_to_use-".$parts_stock." WHERE parts_id = '".$parts_id."' LIMIT 1";
}
mysqli_query($conn,$sql2);

header('location:'.$base_url.$seller_url.'inventory-parts-stock.php?ntf=r1029wkw-89t4hf34675dfoitrj!fn98s3');
?>