<?php
$to           = 'ibnurizal@gmail.com';
$subject      = "Upcoming renewal certification";
$htmlContent  = $htmlContent2.$htmlContent3.$htmlContent4;
$headers      = "MIME-Version: 1.0" . "\r\n";
$headers      .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers      .= 'From: AMS <no-reply@ams.satukode.com>' . "\r\n";

if(mail($to,$subject,$htmlContent,$headers)):
    $a = 'Email has sent successfully.';
else:
    $a = 'Email sending fail.';
endif;

echo $a;
?>