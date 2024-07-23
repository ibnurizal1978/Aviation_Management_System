<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$filename = "Report_expenses_" . date('Ymd') . ".xls";

header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");


$project_master_id  = input_data(filter_var($_POST['project_master_id'],FILTER_SANITIZE_STRING));
$engineer_user_id   = input_data(filter_var($_POST['engineer_user_id'],FILTER_SANITIZE_STRING));
$date_from   		= input_data(filter_var($_POST['date_from'],FILTER_SANITIZE_STRING));
$date_to   			= input_data(filter_var($_POST['date_to'],FILTER_SANITIZE_STRING));

//date
$date_from_y   = substr($date_from,6,4);
$date_from_m   = substr($date_from,3,2);
$date_from_d   = substr($date_from,0,2);
$date_from_f   = $date_from_y.'-'.$date_from_m.'-'.$date_from_d;

$date_to_y   = substr($date_to,6,4);
$date_to_m   = substr($date_to,3,2);
$date_to_d   = substr($date_to,0,2);
$date_to_f   = $date_to_y.'-'.$date_to_m.'-'.$date_to_d;

$sql 	= "SELECT project_ledger_amount FROM tbl_project_ledger a INNER JOIN tbl_user b USING (user_id) WHERE project_master_id = '".$project_master_id."' AND project_ledger_type = 'DEPOSIT' ORDER BY project_ledger_id DESC LIMIT 1";
$h 		= mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($h);

$sql2 	= "SELECT full_name FROM tbl_user WHERE user_id = '".$engineer_user_id."' LIMIT 1";
$h2 	= mysqli_query($conn,$sql2);
$row2 	= mysqli_fetch_assoc($h2);
?>

  <style type="text/css">
  	body {
  		font-family: arial;
  		background: #fff;
  	}

	th {
		background: #189408;
		color: #fff;
		font-size: 18pt;
	}

  	td {
  		font-size:16pt;
  		height: 20px;
  		padding: 10px; 
  	}
  </style>
<div align="center">
	<table width="90%" align="center">
		<tr>
			<td width="100%">
				<h3>EXPENSES REPORT </h3>
				<b>Operation:</b>
				<br/>
				<b>Aircraft:</b>
				<br/>
				<b>Crew:</b> <?php echo $row2['full_name'] ?>
				<br/>
				<b>Period:</b> <?php echo $date_from.' to '.$date_to ?>
				<br/>
				<b>Beginning Deposit:</b> <?php echo 'IDR '.number_format($row['project_ledger_amount'],0,",",".") ?>
			</td>
		</tr>
	</table>
	<?php
	$sql 	= "SELECT project_ledger_notes,project_ledger_type,project_ledger_amount,project_ledger_last_balance,project_ledger_notes,date_format(project_ledger_date, '%d-%m-%Y') as project_ledger_date FROM tbl_project_ledger WHERE project_master_id = '".$project_master_id."' AND engineer_user_id = '".$engineer_user_id."' AND client_id = '".$_SESSION['client_id']."' AND date(project_ledger_date) BETWEEN '".$date_from_f."' AND '".$date_to_f."' ORDER BY project_ledger_id";
	$h 		= mysqli_query($conn,$sql);
	?>	
	<table border="1" width="90%">  
	    <thead> 
	      <tr style="background: #008D1C; color: #fff; height: 60px;"> 
	        <th class="text-center">Date</th>  
	        <th class="text-center">In (IDR)</th>
	        <th class="text-center">Out (IDR)</th>
	        <th class="text-center">Balance (IDR)</th>
	        <th class="text-center">Notes</th>
	      </tr> 
	    </thead>  
	    <tbody>
	      <?php
	      while ($row = mysqli_fetch_assoc($h)) {
	      ?>
	      <tr>
	      	<td><?php echo $row['project_ledger_date']; ?></td>
	      	<td align="right"><?php if($row['project_ledger_type']=='DEPOSIT') { echo number_format($row['project_ledger_amount'],0,",","."); }  ?></td>
	      	<td align="right"><?php if($row['project_ledger_type']=='INVOICE') { echo number_format($row['project_ledger_amount'],0,",","."); }  ?></td>
	      	<td align="right"><?php echo number_format($row['project_ledger_last_balance'],0,",","."); ?></td>
	      	<td><?php echo $row['project_ledger_notes'] ?></td>     	
	      </tr>
	      <?php } ?>
	      <tr>
	      	<td><b>TOTAL</b></td>
	      	<td align="right"><b>
	      		<?php
	      		$sql_total 	= "SELECT sum(project_ledger_amount) as total FROM tbl_project_ledger WHERE project_ledger_type = 'DEPOSIT' AND project_master_id = '".$project_master_id."' AND engineer_user_id = '".$engineer_user_id."' AND client_id = '".$_SESSION['client_id']."' AND date(project_ledger_date) BETWEEN '".$date_from_f."' AND '".$date_to_f."'";
	      		$h_total 	= mysqli_query($conn,$sql_total);
	      		$row_total 	= mysqli_fetch_assoc($h_total);
	      		echo number_format($row_total['total'],0,",",".");
	      		?></b>
	      	</td>
	      	<td align="right"><b>	
	      		<?php
	      		$sql_total 	= "SELECT sum(project_ledger_amount) as total FROM tbl_project_ledger WHERE project_ledger_type = 'INVOICE' AND project_master_id = '".$project_master_id."' AND engineer_user_id = '".$engineer_user_id."' AND client_id = '".$_SESSION['client_id']."' AND date(project_ledger_date) BETWEEN '".$date_from_f."' AND '".$date_to_f."'";
	      		$h_total 	= mysqli_query($conn,$sql_total);
	      		$row_total 	= mysqli_fetch_assoc($h_total);
	      		echo number_format($row_total['total'],0,",",".");
	      		?></b>	      		
	      	</td>
	      	<td>&nbsp;</td>
	      	<td>&nbsp;</td>
	      </tr>
	    </tbody>
	</table>
</div>