<?php
require_once "../config.php";
ini_set('display_errors',1);  error_reporting(E_ALL);
$sql 	= "SELECT full_name,user_id, CURDATE(), DATEDIFF(user_certificate_next, CURDATE()) AS selisih,certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2 FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) INNER JOIN tbl_user c USING (user_id) WHERE certificate_master_id IN (2,4,7,9,11,14) AND DATEDIFF(user_certificate_next, CURDATE()) < 30";
$h 		= mysqli_query($conn,$sql);
while($row 	= mysqli_fetch_assoc($h)) {
        $htmlContent2 = '
        <h2>Upcoming and Expired Certificate</h2>
        <table cellpadding="8" border="1" width="90%">
          <tr><th>Name</th><th>Certificate Type</th><th>Last Taken</th><th>Next Schedule</th><th>Treshold</th></tr>';
          $htmlContent3 = '';
            while ($row = mysqli_fetch_assoc($h)) {
              $htmlContent3 .= '<tr><td>'.$row["full_name"].'</td><td>'.$row["certificate_master_name"].'</td><td>'.$row["user_certificate_date"].'</td><td>'.$row["user_certificate_next"].'</td><td>'.$row["selisih"].'</td></tr>';
            } 
          $htmlContent4 = '</table>';


        echo $htmlContent2.$htmlContent3.$htmlContent4;
        /*
		$content 	= 'Upcoming and expired certificate: '.$row['user_id'];
		//cek bosnya, kirim email
		$sql_cek 	= "SELECT user_email_address FROM tbl_user WHERE user_id = '".$row['user_id']."'";
		$h_cek 		= mysqli_query($conn,$sql_cek);
		while($row_cek 	= mysqli_fetch_assoc($h_cek)) {
			$to           = $row_cek['user_email_address'];
			$subject      = "Upcoming renewal certification";
			$htmlContent  = 'ada yang mau habis';
			$headers      = "MIME-Version: 1.0" . "\r\n";
			$headers      .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers      .= 'From: AMS <no-reply@ams.satukode.com>' . "\r\n";
			//if(mail($to,$subject,$htmlContent,$headers)) {
			//	echo $row_cek['user_email_address'].'<br/>terkirim';
			//}else{
			//	echo 'nggak';
			}
			echo 'a';
		} */ 
}
?>