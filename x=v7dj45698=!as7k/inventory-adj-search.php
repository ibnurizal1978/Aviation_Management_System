<?php
require_once '../config.php';
ini_set('display_errors',1);  error_reporting(E_ALL);
/*
$data = array();
$sql = "SELECT * FROM tbl_parts WHERE parts_name LIKE '%".$_GET['term']."%' ORDER BY parts_name ASC";
$h = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($h)) {
    $data[] = $row['parts_name'];
}
//return json data
echo json_encode($data);*/

$src = $_REQUEST["src"];
$parts_warehouse_id_value = $_REQUEST['parts_warehouse_id_value'];
echo 'ayam '.$parts_warehouse_id_value;
$sql = "SELECT parts_name FROM tbl_parts WHERE parts_name LIKE '%$src%'";
$h = mysqli_query($conn, $sql) or die(mysqli_error()); 
//query mencari hasil search
$hasil = mysqli_num_rows($h);
if ($hasil > 0) {
while ($row = mysqli_fetch_assoc($h)) {
	echo '<span class="pilihan" onclick="pilih_kota(\''.$row['parts_name'].'\');hideStuff(\'suggest\');">'.$row['parts_name'].'</span>';
}
}
?>