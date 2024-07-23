<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
$db_server        = '127.0.0.1';
$db_user          = 'root';
$db_password      = 'Database@123';
$db_name          = 'db_aviation';
$conn 			  = new mysqli($db_server,$db_user,$db_password,$db_name) or die (mysqli_error());

$sql2 = "SELECT avtur_price,avtur_price_from,avtur_price_to FROM tbl_avtur_price";
$h2   = mysqli_query($conn,$sql2);
while($row2 = mysqli_fetch_assoc($h2)) {
  $sql 	= "SELECT avtur_afml_id,afml_fuel_date,iata_code,afml_amount FROM tbl_avtur_afml WHERE afml_price = '0.00' AND afml_amount<>0 AND (afml_fuel_date BETWEEN '".$row2['avtur_price_from']."' AND '".$row2['avtur_price_to']."') AND iata_code <>''";
  echo $sql.'<br/>';
  $h 		= mysqli_query($conn,$sql);
  $row 	= mysqli_fetch_assoc($h); 
  $harga = $row2['avtur_price']*$row['afml_amount'];
  echo 'tgl beli: '.$row['afml_fuel_date'].'<br/>';
  echo $row2['avtur_price'].'<br/>';
  echo $harga.'<br/><br/>';
  $sql_update = "UPDATE tbl_avtur_afml SET afml_price = '".$harga."' WHERE avtur_afml_id = '".$row['avtur_afml_id']."' LIMIT 1";
  mysqli_query($conn,$sql_update);
  echo $sql_update.'<br/>';
}
?>