<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
$db_server        = '127.0.0.1';
$db_user          = 'ams';
$db_password      = 'Database@123';
//$db_password      = 'D0d0lg4rut';
$db_name          = 'db_aviation';
$conn 			  = new mysqli($db_server,$db_user,$db_password,$db_name) or die (mysqli_error());

$sql 	= "SELECT *,CURDATE(), DATEDIFF(next_calibration_date, CURDATE()) AS selisih,date_format(next_calibration_date, '%d/%m/%Y') as next_calibration_date2,date_format(last_calibration_date, '%d/%m/%Y') as last_calibration_date,date_format(next_calibration_date, '%d/%m/%Y') as next_calibration_date FROM tbl_tools WHERE client_id = '".$_SESSION['client_id']."' AND date(last_calibration_date)<>'0000-00-00' AND DATEDIFF(next_calibration_date, CURDATE()) < 30";
$h 		= mysqli_query($conn,$sql);
while($row 	= mysqli_fetch_assoc($h)) {
    $htmlContent2 = '
    <h2>Upcoming and Expired Tools Calibration</h2>
    <table cellpadding="8" border="1" width="90%">
      <tr><th>Description</th><th>Part Number</th><th>Serial Number</th><th>Expired Date</th><th>Will Be Expired</th></tr>';
      $htmlContent3 = '';
        while ($row = mysqli_fetch_assoc($h)) {
          	$htmlContent3 .= '<tr><td>'.$row["tools_description"].'</td><td>'.$row["part_number"].'</td><td>'.$row["serial_number"].'</td><td>'.$row["next_calibration_date"].'</td><td>'.$row["selisih"].' day(s)</td></tr>';         	
        } 
      $htmlContent4 = '</table>';

    echo $htmlContent2.$htmlContent3.$htmlContent4;

	//email ke manager
	$to           = 'andreas@smartaviation.co.id,ibnurizal@gmail.com';
	$subject      = "Upcoming Tools Calibration";
	$htmlContent  = $htmlContent2.$htmlContent3.$htmlContent4;
	$headers      = "MIME-Version: 1.0" . "\r\n";
	$headers      .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers      .= 'From: AMS <no-reply@ams.satukode.com>' . "\r\n";
	//mail($to,$subject,$htmlContent,$headers);
}
?>