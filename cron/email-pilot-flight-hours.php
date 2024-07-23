<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
$db_server        = '127.0.0.1';
$db_user          = 'ams';
$db_password      = 'Database@123';
$db_name          = 'db_aviation';
$conn 			  = new mysqli($db_server,$db_user,$db_password,$db_name) or die (mysqli_error());


function minutesToHours($minutes) 
{ 
    $hours = (int)($minutes / 60); 
    $minutes -= $hours * 60; 
    return sprintf("%02d:%02.0f", $hours, $minutes); 
}

//get adata user. test: capt kun dan sammy
$sql    = "SELECT user_id, user_email_address, full_name, lic_no FROM tbl_user WHERE user_id IN ('51','30')";
$h      = mysqli_query($conn,$sql);
while($row    = mysqli_fetch_assoc($h)) {


	$sql_tot1   = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id = '".$row['user_id']."' OR b.afml_copilot_user_id = '".$row['user_id']."') AND YEAR(a.afml_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(a.afml_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
	$h_tot1     = mysqli_query($conn, $sql_tot1);
	$row_tot1   = mysqli_fetch_assoc($h_tot1);
	$total_block_hrs    = $row_tot1['total_block_hrs'];
	$total_flt_hrs      = $row_tot1['total_flt_hrs'];


	$sql2    = "SELECT afml_id,afml_page_no,b.aircraft_reg_code, afml_block_hrs, afml_flt_hrs, date_format(a.afml_date, '%d/%m/%Y') as afml_date FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id = '".$row['user_id']."' OR b.afml_copilot_user_id = '".$row['user_id']."')  AND YEAR(a.afml_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(a.afml_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";                          
	$h2      = mysqli_query($conn, $sql2);

	$htmlContent2 = '
	Hello <b>'.$row['full_name'].'</b>,<br/>
	Total Flt Hrs: '.minutesToHours($total_flt_hrs).'<br/>
	Total Block Hrs: '.minutesToHours($total_block_hrs).'<br/>
	<table cellpadding="1" border="1" width="100%">
		<tr><th>ID</th><th>AFML Page No</th><th>Date</th><th>Reg Code</th><th>Flt Hrs</th><th>Block Hrs</th></tr>';
$htmlContent3 = '';
	while($row2 = mysqli_fetch_assoc($h2)) {
		
	  	$htmlContent3 .= '<tr><td>'.$row['user_id'].'</td><td>'.$row2["afml_page_no"].'</td><td>'.$row2["afml_date"].'</td><td>'.$row2["aircraft_reg_code"].'</td><td>'.minutesToHours($row2["afml_flt_hrs"]).'</td><td>'.minutesToHours($row2["afml_block_hrs"]).'</td></tr>';    	
	}
	$htmlContent4 = '</table>';


	//email ke personil masing-masing
	$to           = $row['user_email_address'];    
	//$to           = 'ibnurizal@gmail.com,Fransiskus.bong@gmail.com';
	$subject      = "Your previous flight Hours";
	$htmlContent  = $htmlContent2.$htmlContent3.'</table>';
	$headers      = "MIME-Version: 1.0" . "\r\n";
	$headers      .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers      .= 'From: AMS <no-reply@ams.satukode.com>' . "\r\n";
	if(mail($to,$subject,$htmlContent,$headers)) {
		echo 'ok';
	}else{
		echo 'no';
	}
echo $htmlContent2.$htmlContent3.$htmlContent4;

} 


?>