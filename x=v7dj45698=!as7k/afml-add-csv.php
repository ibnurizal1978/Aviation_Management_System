<?php
define("FOLDER_FILE", dirname( __FILE__ ));
//chmod(FOLDER_FILE, 0777);
	
$tgl  	= date("Y-m-d");
$date 	= date("Y-m-d H:i:s");
$file 	= 'log/afml-add-'.$tgl.'.csv';
$FX 	= file_exists($file);

if(!$FX)
{ // Jika file belum ada, maka untuk membuat pertama kali para baris awal diberi Label
	//chmod($file, 0777); 
	$cF = fopen($file, 'w');

	$list1 = array (array("AFML DATE", "PAGE NO","REG NO", "PILOT_ID","COPILOT_ID","EOB"));
	
	foreach ($list1 as $fields1)
	{
		fputcsv($cF, $fields1);
	}
	fclose($cF);
	

}	

$a = "`".$afml_date; //di excel ga akan ngaco
$b = $afml_page_no;
$c = $aircraft_reg_code;
$d = $afml_pilot;
$e = $afml_copilot;
$f = $afml_engineer_on_board;

$list = array (array($a,$b,$c,$d,$e,$f));

chmod($file, 0775); 
$ft = fopen($file, 'a');
 
foreach ($list as $fields) 
{
	fputcsv($ft, $fields);
}
	
fclose($ft);	
?>