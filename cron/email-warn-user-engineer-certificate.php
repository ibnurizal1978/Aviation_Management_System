<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
$db_server        = '127.0.0.1';
$db_user          = 'ams';
$db_password      = 'Database@123';
//$db_password      = 'D0d0lg4rut';
$db_name          = 'db_aviation';
$conn 			  = new mysqli($db_server,$db_user,$db_password,$db_name) or die (mysqli_error());

$sql 	= "SELECT full_name,user_email_address,user_id, CURDATE(), DATEDIFF(user_certificate_next, CURDATE()) AS selisih,certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2 FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) INNER JOIN tbl_user c USING (user_id) WHERE certificate_master_id IN (2,4,7,9,11,14) AND DATEDIFF(user_certificate_next, CURDATE()) < 30";
$h 		= mysqli_query($conn,$sql);
while($row 	= mysqli_fetch_assoc($h)) {
    $htmlContent2 = '
    <h2>Upcoming and Expired Certificate</h2>
    <table cellpadding="8" border="1" width="90%">
      <tr><th>Name</th><th>Certificate Type</th><th>Last Taken</th><th>Next Schedule</th><th>Treshold</th></tr>';
      $htmlContent3 = '';
        while ($row = mysqli_fetch_assoc($h)) {
          	$htmlContent3 .= '<tr><td>'.$row["full_name"].'</td><td>'.$row["certificate_master_name"].'</td><td>'.$row["user_certificate_date"].'</td><td>'.$row["user_certificate_next"].'</td><td>'.$row["selisih"].' day(s)</td></tr>';

		    //email ke personil masing-masing
			$to           = $row['user_email_address'];
			$subject      = "Hey, Your Certificate is about to expired!";
			$htmlContent  = $htmlContent2.$htmlContent3.'</table>';
			$headers      = "MIME-Version: 1.0" . "\r\n";
			$headers      .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers      .= 'From: AMS <no-reply@ams.satukode.com>' . "\r\n";
			mail($to,$subject,$htmlContent,$headers);          	
        } 
      $htmlContent4 = '</table>';

    echo $htmlContent2.$htmlContent3.$htmlContent4;

	//email ke manager
	$to           = 'andreas@smartaviation.co.id,ibnurizal@gmail.com';
	$subject      = "Upcoming and Expired Certificate";
	$htmlContent  = $htmlContent2.$htmlContent3.$htmlContent4;
	$headers      = "MIME-Version: 1.0" . "\r\n";
	$headers      .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers      .= 'From: AMS <no-reply@ams.satukode.com>' . "\r\n";
	//mail($to,$subject,$htmlContent,$headers);
}
?>