<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
require_once "components.php";
$sql 	= "SELECT client_name FROM tbl_client WHERE client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h 		= mysqli_query($conn,$sql);
$row1  	= mysqli_fetch_assoc($h);
?>

  <style type="text/css">
  	td {
  		font-size:10pt;
  	}
  </style>

<div class="block table-responsive">
    <div class="block-header block-header-default">
    	<table class="table table-sm table-vcenter">
			<tr>
				<td>
					<h3>OTR DATA TECHNICAL  PERSONNEL</h3>
					<b><?php echo $row1['client_name'] ?></b>
				</td>
			</tr>
		</table>
	</div>

	<?php
	$sql 	= "SELECT  user_id,full_name,user_position
	FROM tbl_user WHERE client_id = '".$_SESSION['client_id']."' AND user_ID NOT IN ('35','15') AND department_id = 1 AND user_active_status = 1";
	$h 		= mysqli_query($conn,$sql);
	if(mysqli_num_rows($h)>0) {
	?>
	<div class="block-content">
        <table class="table table-sm table-vcenter table-bordered"> 
		    <thead> 
	          <tr style="background: #008D1C; color: #fff"> 
	            <th class="text-center" width="50%">Name</th>  
	            <th class="text-center">Position</th>
	            <th class="text-center">Amel No.</th>
	            <th class="text-center">Amel Validity</th>
	            <th class="text-center">OTR No.</th>
	            <th class="text-center">OTR Validity</th>
	            <th class="text-center">General License</th>
	            <th class="text-center">Aircraft Type</th>
	            <th class="text-center">Authorized Limitation</th>
	            <th class="text-center">Engine Run Up</th>
	            <th class="text-center">Weight Balance</th>
	            <th class="text-center">Compass Swing</th>
	            <th class="text-center">Boroscope</th>
	            <th class="text-center">Stamp No</th>
	            <th class="text-center">RII Stamp</th>
	            <th class="text-center">Inspector Stamp</th>
	            <th class="text-center">Remark</th>
	          </tr>  
		    </thead>  
		    <tbody>
		      <?php while ($row = mysqli_fetch_assoc($h)) { ?>
		      <tr>
		      	<td><a href="user-cv.php?q=174yurhwebfn&ntf=9eqoiwjdlk-<?php echo $row['user_id']; ?>-djnsncl"><?php echo $row['full_name']; ?></a></td>
		      	<td class="text-center"><?php echo $row['user_position']; ?></td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT amel_no,date_format(amel_validity_date, '%d/%m/%Y') as amel_validity_date FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					echo $row2['amel_no'];
					?>
				</td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT date_format(amel_validity_date, '%d/%m/%Y') as amel_validity_date FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					echo $row2['amel_validity_date'];
					?>		
		      	</td>	      	
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT otr_no FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					echo $row2['otr_no'];
					?>
		      	</td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT date_format(otr_validity_date, '%d/%m/%Y') as otr_validity_date FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					echo $row2['otr_validity_date'];
					?>	      		
		      	</td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT general_license FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					echo $row2['general_license'];
					?>      		
		      	</td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT aircraft_type FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					echo $row2['aircraft_type'];
					?>      		
		      	</td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT authorized_limitation FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					echo $row2['authorized_limitation'];
					?>      		
		      	</td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT engine_run_up FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					echo $row2['engine_run_up'];
					?>	      		
		      	</td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT weight_balance FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					echo $row2['weight_balance'];
					?>	      		
		      	</td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT compass_swing FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					echo $row2['compass_swing'];
					?>     		
		      	</td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT boroscope FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					echo $row2['boroscope'];
					?>	      		
		      	</td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT stamp_no FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					if($row2['stamp_no']<>'') {
						echo '<img width=70 src=../uploads/stamp/'.$row2['stamp_no'].'>';
					}
					?>      		
		      	</td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT rii_stamp FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					if($row2['rii_stamp']<>'') {
						echo '<img width=70 src=../uploads/stamp/'.$row2['rii_stamp'].'>';
					}
					?>	      		
		      	</td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT inspector_stamp FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					if($row2['inspector_stamp']<>'') {
						echo '<img width=70 src=../uploads/stamp/'.$row2['inspector_stamp'].'>';
					}
					?>	      		
		      	</td>
		      	<td>
		      		<?php 
		      		$sql2 	= "SELECT remark FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
					$h2 	= mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($h2);
					echo $row2['remark'];
					?>	      		
		      	</td>	      		      		      		      	
		      </tr>
		      <?php } ?>
		    </tbody>
		</table>
		<?php }else{ echo 'No data available'; } ?>
	</div>
</div>  
