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
					<h3>TRAINING AND RECURRENT  LIST  TECHNICAL  PERSONNEL</h3>
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
	      		$sql2 	= "SELECT certificate_master_name,user_certificate_file, date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 1 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 = mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2);
				?>		
	      	</td>	      	
	      	<td class="text-center">
	      		<?php //Human Factor Recurrent
	      		$sql2 	= "SELECT CURDATE(), DATEDIFF(user_certificate_next, CURDATE()) AS selisih,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date,date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 2 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($row2['selisih']<30) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	}
		      	mysqli_free_result($h2);
		      	?>
	      	</td>
	      	<td class="text-center">
	      		<?php //Aircraft Factor Initial
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 3 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2);
		      	?>	      		
	      	</td>
	      	<td class="text-center">
	      		<?php //Aircraft Factor Recurrent
	      		$sql2 	= "SELECT CURDATE(), DATEDIFF(user_certificate_next, CURDATE()) AS selisih,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date,date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 4 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($row2['selisih']<30) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	}
		      	mysqli_free_result($h2); 
		      	?>      		
	      	</td>
	      	<td class="text-center">
	      		<?php //Basic Ind
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 5 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2); 
				?>      		
	      	</td>
	      	<td class="text-center">
	      		<?php //SMS initial
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 6 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2); 
				?>      		
	      	</td>
	      	<td class="text-center">
	      		<?php //DG initial
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 8 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2); 
				?>	      		
	      	</td>
	      	<td class="text-center">
	      		<?php //Avsec initial
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 10 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2); 
				?>	      		
	      	</td>
	      	<td class="text-center">
	      		<?php //CASR
	      		$sql2 	= "SELECT CURDATE(), DATEDIFF(user_certificate_next, CURDATE()) AS selisih,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date,date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 26 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($row2['selisih']<30) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	}
		      	mysqli_free_result($h2); 
		      	?>      		
	      	</td>
	      	<td class="text-center">
	      		<?php //English
	      		$sql2 	= "SELECT CURDATE(), DATEDIFF(user_certificate_next, CURDATE()) AS selisih,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date,date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 27 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($row2['selisih']<30) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	}
		      	mysqli_free_result($h2); 
		      	?>      		
	      	</td>	      		      	
	      	<td class="text-center">
	      		<?php //Basic inspector
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 12 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
				mysqli_free_result($h2);
				?>	      		
	      	</td>
	      	<td class="text-center">
	      		<?php //RII initial
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 13 LIMIT 1";
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
