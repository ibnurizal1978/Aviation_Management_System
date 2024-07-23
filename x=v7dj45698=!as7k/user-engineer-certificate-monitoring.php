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
  		font-size:8pt;
  	}
  </style>

<div class="block table-responsive">
    <div class="block-header block-header-default">
    	<table class="table table-sm table-vcenter">
			<tr>
				<td>
					<h3>Training Data Completeness</h3>
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
        <table class="table table-sm table-vcenter table-bordered table-striped">  
	    <thead> 
          <tr style="background: #70C265; color: #fff"> 
            <th rowspan="2" class="text-center" width="50%">Name</th>  
            <th rowspan="2"class="text-center">Position</th>
            <th colspan="1" class="text-center">Amel No.</th>
            <th colspan="2" width="30%" class="text-center">Human Factor</th>
            <th colspan="2" width="30%" class="text-center">Aircraft Type Rating</th>
            <th colspan="6" width="30%" class="text-center">Mandatory Training</th>
            <th rowspan="2"class="text-center">Basic Inspector</th>
            <th rowspan="2"class="text-center">RII</th>
            <th rowspan="2"class="text-center">Remark</th>
          </tr>
          <tr style="background: #70C265; color: #fff">
            <th  width="10%" class="text-center">Valid</th>
            <th  width="10%" class="text-center">Initial</th>
            <th  width="10%" class="text-center">Recurrent</th>
            <th  width="10%" class="text-center">Initial</th>
            <th  width="10%" class="text-center">Recurrent</th>
            <th  width="10%" class="text-center">Basic Ind</th>
            <th  width="10%" class="text-center">SMS</th>
            <th  width="10%" class="text-center">DG</th>
            <th  width="10%" class="text-center">AVSEC</th>
            <th  width="10%" class="text-center">CASR</th>
            <th  width="10%" class="text-center">English</th>
          </tr>
		    <tr>  
		    </tr>  
	    </thead>  
	    <tbody>
	      <?php while ($row = mysqli_fetch_assoc($h)) { ?>
	      <tr style="background: #fff; color: #555555; border: 1px solid #000">
	      	<td><a href="user-cv.php?q=174yurhwebfn&ntf=9eqoiwjdlk-<?php echo $row['user_id']; ?>-djnsncl"><?php echo $row['full_name']; ?></a></td>
	      	<td class="text-center"><?php echo $row['user_position']; ?></td>
	      	<td class="text-center">
	      		<?php 
	      		$sql2 	= "SELECT amel_no,date_format(amel_validity_date, '%d/%m/%Y') as amel_validity_date FROM tbl_user_otr WHERE user_id = '".$row['user_id']."' LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 = mysqli_fetch_assoc($h2);

	      		$sql3 	= "SELECT user_certificate_file FROM tbl_user_certificate WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 28 LIMIT 1";
				$h3 	= mysqli_query($conn,$sql3);
				$row3 	= mysqli_fetch_assoc($h3);

				echo '<a href='.$base_url.'uploads/certificate/'.$row3['user_certificate_file'].' target="_blank">'.$row2['amel_no'].'</a>';
	      		echo '<br/>('.$row2['amel_validity_date'].')'; ?></td>
	      	<td class="text-center">
	      		<?php //Human Factor Initial
	      		$sql2 	= "SELECT certificate_master_name,user_certificate_file, date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 1 AND user_certificate_date <> '0000-00-00' LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 = mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2);
				?>		
	      	</td>	      	
	      	<td class="text-center">
	      		<?php //Human Factor Recurrent
	      		$sql2 	= "SELECT CURDATE(), DATEDIFF(user_certificate_next, CURDATE()) AS selisih,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date,date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, user_certificate_file, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2 FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 2 AND user_certificate_date <> '0000-00-00' LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
                $date1  = date_create(date('Y-m-d'));
                $date2  = date_create($row2['user_certificate_next2']);

				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($row2['selisih']<30 || $date2<$date1) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
		      	}
		      	mysqli_free_result($h2);
		      	?>
	      	</td>
	      	<td class="text-center">
	      		<?php //Aircraft Factor Initial
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 3 AND user_certificate_date <> '0000-00-00' LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2);
		      	?>	      		
	      	</td>
	      	<td class="text-center">
	      		<?php //Aircraft Factor Recurrent
	      		$sql2 	= "SELECT CURDATE(), DATEDIFF(user_certificate_next, CURDATE()) AS selisih,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date,date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, user_certificate_file, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2 FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 4 AND user_certificate_date <> '0000-00-00' LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
                $date1  = date_create(date('Y-m-d'));
                $date2  = date_create($row2['user_certificate_next2']);

				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($row2['selisih']<30 || $date2<$date1) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
		      	}
		      	mysqli_free_result($h2); 
		      	?>      		
	      	</td>
	      	<td class="text-center">
	      		<?php //Basic Ind
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 5 AND user_certificate_date <> '0000-00-00' LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2); 
				?>      		
	      	</td>
	      	<td class="text-center">
	      		<?php //SMS initial
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 6 AND user_certificate_date <> '0000-00-00' LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2); 
				?>      		
	      	</td>
	      	<td class="text-center">
	      		<?php //DG initial
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 8 AND user_certificate_date <> '0000-00-00' LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2); 
				?>	      		
	      	</td>
	      	<td class="text-center">
	      		<?php //Avsec initial
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 10 AND user_certificate_date <> '0000-00-00' LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2); 
				?>	      		
	      	</td>
	      	<td class="text-center">
	      		<?php //CASR
	      		$sql2 	= "SELECT CURDATE(), DATEDIFF(user_certificate_next, CURDATE()) AS selisih,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date,date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, user_certificate_file, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2 FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 26 AND user_certificate_date <> '0000-00-00' LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
                $date1  = date_create(date('Y-m-d'));
                $date2  = date_create($row2['user_certificate_next2']);

				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($row2['selisih']<30 || $date2<$date1) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
		      	}
		      	mysqli_free_result($h2); 
		      	?>      		
	      	</td>
	      	<td class="text-center">
	      		<?php //English
	      		$sql2 	= "SELECT CURDATE(), DATEDIFF(user_certificate_next, CURDATE()) AS selisih,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date,date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, user_certificate_file,date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2 FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 27 AND user_certificate_date <> '0000-00-00' LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
                $date1  = date_create(date('Y-m-d'));
                $date2  = date_create($row2['user_certificate_next2']);

				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($row2['selisih']<30 || $date2<$date1) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
		      	}
		      	mysqli_free_result($h2); 
		      	?>      		
	      	</td>	      		      	
	      	<td class="text-center">
	      		<?php //Basic inspector
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 12 AND user_certificate_date <> '0000-00-00' LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2);
				?>	      		
	      	</td>
	      	<td class="text-center">
	      		<?php //RII initial
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2 FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 13 AND user_certificate_date <> '0000-00-00' LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2); 
				?>	      		
	      	</td>
	      	<td>&nbsp;</td>	      		      		      		      	
	      </tr>
	      <?php } ?>
	    </tbody>
	</table>
	<?php }else{ echo 'No data available'; } ?> 
</div>


<div class="block table-responsive">
    <div class="block-header block-header-default">
    	<table class="table table-sm table-vcenter">
			<tr>
				<td>
					<h3>OTR Data Completeness</h3>
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


<div class="block table-responsive">
    <div class="block-header block-header-default">
    	<table class="table table-sm table-vcenter">
			<tr>
				<td>
					<h3>CV Completeness</h3>
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
	            <th class="text-center">Work Experience</th>
	            <th class="text-center">Training & Educational</th>
	            <th class="text-center">Additional Info</th>
	          </tr>  
		    </thead>  
		    <tbody>
		      <?php while ($row = mysqli_fetch_assoc($h)) { ?>
		      <tr>
		      	<td><a href="user-cv.php?q=174yurhwebfn&ntf=9eqoiwjdlk-<?php echo $row['user_id']; ?>-djnsncl"><?php echo $row['full_name']; ?></a></td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT user_id FROM tbl_user_cv_work_experiences WHERE user_id = '".$row['user_id']."' LIMIT 1";
		      		$h2 		= mysqli_query($conn,$sql2);
		      		if(mysqli_num_rows($h2)>0) {
		      		?>
		      		<i class="fa fa-check text-success"></i> 
		      		<?php }else{ ?>
		      		<i class="fa fa-close text-danger"></i>
		      		<?php } ?>
		      	</td>
		      	<td class="text-center">
					<?php 
		      		$sql2 	= "SELECT user_id FROM tbl_user_cv_training WHERE user_id = '".$row['user_id']."' LIMIT 1";
		      		$h2 		= mysqli_query($conn,$sql2);
		      		if(mysqli_num_rows($h2)>0) {
		      		?>
		      		<i class="fa fa-check text-success"></i> 
		      		<?php }else{ ?>
		      		<i class="fa fa-close text-danger"></i>
		      		<?php } ?>
				</td>
		      	<td class="text-center">
		      		<?php 
		      		$sql2 	= "SELECT user_id FROM tbl_user_cv_additional WHERE user_id = '".$row['user_id']."' LIMIT 1";
		      		$h2 		= mysqli_query($conn,$sql2);
		      		if(mysqli_num_rows($h2)>0) {
		      		?>
		      		<i class="fa fa-check text-success"></i> 
		      		<?php }else{ ?>
		      		<i class="fa fa-close text-danger"></i>
		      		<?php } ?>		
		      	</td>
		      </tr>
		      <?php } ?>
		    </tbody>
		</table>
		<?php }else{ echo 'No data available'; } ?>
	</div>
</div>  
