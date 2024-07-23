<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
$db_server        = '127.0.0.1';
$db_user          = 'root';
$db_password      = 'Database@123';
$db_name          = 'db_aviation';
$conn 			  = new mysqli($db_server,$db_user,$db_password,$db_name) or die (mysqli_error());

//add captain name
$sql1 	= "SELECT b.user_id,afml_captain_user_id, full_name FROM tbl_afml a INNER JOIN tbl_user b ON a.afml_captain_user_id = b.user_id WHERE afml_captain_user_id <> 0 AND afml_captain = '' ";
$h1		= mysqli_query($conn,$sql1);
while($row1 = mysqli_fetch_assoc($h1)) {
	$sqla = "UPDATE tbl_afml SET afml_captain = '".$row1['full_name']."' WHERE afml_captain_user_id = '".$row1['afml_captain_user_id']."'";
	//echo $sqla.'<br/>';
	mysqli_query($conn,$sqla) or die (mysqli_error());		
}

//add copilot name
$sql2 	= "SELECT b.user_id,afml_copilot_user_id, full_name FROM tbl_afml a INNER JOIN tbl_user b ON a.afml_copilot_user_id = b.user_id WHERE afml_copilot_user_id <> 0 AND afml_copilot = '' ";
$h2		= mysqli_query($conn,$sql2);
while($row2 = mysqli_fetch_assoc($h2)) {
	//echo $row['user_id'].' - '.$row['full_name'].' - '.$row['afml_copilot_user_id'].'<br/>';
	$sqlb = "UPDATE tbl_afml SET afml_copilot = '".$row2['full_name']."' WHERE afml_copilot_user_id = '".$row2['afml_copilot_user_id']."'";
	//echo '<br/>=============<br/>'.$sqlb.'<br/>';
	mysqli_query($conn,$sqlb) or die (mysqli_error());		
}

//add eob name
$sql3 	= "SELECT b.user_id,afml_engineer_on_board_user_id, full_name FROM tbl_afml a INNER JOIN tbl_user b ON a.afml_engineer_on_board_user_id = b.user_id WHERE afml_engineer_on_board_user_id <> 0 AND afml_engineer_on_board = '' ";
$h3		= mysqli_query($conn,$sql3);
while($row3 = mysqli_fetch_assoc($h3)) {
	$sqlc = "UPDATE tbl_afml SET afml_engineer_on_board = '".$row3['full_name']."' WHERE afml_engineer_on_board_user_id = '".$row3['afml_engineer_on_board_user_id']."'";
	echo $sqlc.'<br/>';
	mysqli_query($conn,$sqlc) or die (mysqli_error());		
}
