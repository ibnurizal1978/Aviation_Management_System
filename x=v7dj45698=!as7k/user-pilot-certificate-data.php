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
					<h3>PILOT CERTIFICATION DATA</h3>
					<b><?php echo $row1['client_name'] ?></b>
				</td>
			</tr>
		</table>
	</div>
	<?php
	$sql 	= "SELECT  user_id,full_name,user_home_address,user_position,date_format(user_birth_date, '%d/%m/%Y') as user_birth_date,user_phone,user_email_address,lic_no
	FROM tbl_user WHERE client_id = '".$_SESSION['client_id']."' AND department_id = 4 AND user_active_status = 1";
	$h 		= mysqli_query($conn,$sql);
	if(mysqli_num_rows($h)>0) {
	?>
	<div class="block-content">
        <table class="table table-sm table-vcenter table-bordered table-striped">  
	    <thead> 
          <tr style="background: #70C265; color: #fff"> 
            <th rowspan="2" class="text-center" width="50%">Name</th>  
            <th rowspan="2" class="text-center">Position</th>
            <th rowspan="2" class="text-center">DoB</th>
            <th rowspan="2" class="text-center">Address</th>
            <th rowspan="2" class="text-center">LIC No.</th>
            <th rowspan="2" class="text-center">Phone</th>
            <th rowspan="2" class="text-center">Email</th>
            <th colspan="11" class="text-center">Certification</th>
          </tr>
          <tr style="background: #70C265; color: #fff">
            <th  width="10%" class="text-center">PPC</th>
            <th  width="10%" class="text-center">MEDEX</th>
            <th  width="10%" class="text-center">NEP</th>
            <th  width="10%" class="text-center">ALAR</th>
            <th  width="10%" class="text-center">WINDSHEAR</th>
            <th  width="10%" class="text-center">AVSEC</th>
            <th  width="10%" class="text-center">CRM</th>
            <th  width="10%" class="text-center">DG</th>
            <th  width="10%" class="text-center">CCP/FI/RI/GI</th>
            <th  width="10%" class="text-center">SC</th>
            <th  width="10%" class="text-center">PASSPORT</th>
          </tr>
		    <tr>  
		    </tr>  
	    </thead>  
	    <tbody>
	      <?php while ($row = mysqli_fetch_assoc($h)) { ?>
	      <tr style="background: #fff; color: #555555; border: 1px solid #000">
	      	<td><a href="user-cv.php?q=174yurhwebfn&ntf=9eqoiwjdlk-<?php echo $row['user_id']; ?>-djnsncl"><?php echo $row['full_name']; ?></a></td>
	      	<td class="text-center"><?php echo $row['user_position']; ?></td>
	      	<td class="text-center"><?php echo $row['user_birth_date']; ?></td>
	      	<td class="text-center"><?php echo $row['user_home_address']; ?></td>
	      	<td class="text-center"><?php echo $row['lic_no']; ?></td>
	      	<td class="text-center"><?php echo $row['user_phone']; ?></td>
	      	<td class="text-center"><?php echo $row['user_email_address']; ?></td>
			<td class="text-center">
	      		<?php //PPC
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 15 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);

				$date1 	= date_create(date('Y-m-d'));
				$date2 	= date_create($row2['user_certificate_next2']);
				$diff 	= date_diff($date1,$date2);
				$selisih = $diff->format("%a");
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($selisih<30) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	} ?>
	      	</td>
	      	<td class="text-center">
	      		<?php //MEDEX
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 16 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);

				$date1 	= date_create(date('Y-m-d'));
				$date2 	= date_create($row2['user_certificate_next2']);
				$diff 	= date_diff($date1,$date2);
				$selisih = $diff->format("%a");
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($selisih<30) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	} ?>	      		
	      	</td>
	      	<td class="text-center">
	      		<?php //NEP
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 17 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);

				$date1 	= date_create(date('Y-m-d'));
				$date2 	= date_create($row2['user_certificate_next2']);
				$diff 	= date_diff($date1,$date2);
				$selisih = $diff->format("%a");
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($selisih<30 || $date2<$date1) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	} ?>     		
	      	</td>
	      	<td class="text-center">
	      		<?php //ALAR
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 18 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);

				$date1 	= date_create(date('Y-m-d'));
				$date2 	= date_create($row2['user_certificate_next2']);
				$diff 	= date_diff($date1,$date2);
				$selisih = $diff->format("%a");
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($selisih<30 || $date2<$date1) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	} ?>      		
	      	</td>
	      	<td class="text-center">
	      		<?php //WINDSHEAR
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 19 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);

				$date1 	= date_create(date('Y-m-d'));
				$date2 	= date_create($row2['user_certificate_next2']);
				$diff 	= date_diff($date1,$date2);
				$selisih = $diff->format("%a");
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($selisih<30 || $date2<$date1) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	} ?>      		
	      	</td>
	      	<td class="text-center">
	      		<?php //AVSEC
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 20 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);

				$date1 	= date_create(date('Y-m-d'));
				$date2 	= date_create($row2['user_certificate_next2']);
				$diff 	= date_diff($date1,$date2);
				$selisih = $diff->format("%a");
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($selisih<30 || $date2<$date1) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	} ?>	      		
	      	</td>
	      	<td class="text-center">
	      		<?php //CRM
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 21 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);

				$date1 	= date_create(date('Y-m-d'));
				$date2 	= date_create($row2['user_certificate_next2']);
				$diff 	= date_diff($date1,$date2);
				$selisih = $diff->format("%a");
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($selisih<30 || $date2<$date1) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	} ?>     		
	      	</td>
	      	<td class="text-center">
	      		<?php //DG
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 22 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);

				$date1 	= date_create(date('Y-m-d'));
				$date2 	= date_create($row2['user_certificate_next2']);
				$diff 	= date_diff($date1,$date2);
				$selisih = $diff->format("%a");
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($selisih<30 || $date2<$date1) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	} ?>	      		
	      	</td>
	      	<td class="text-center">
	      		<?php //TRI
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 23 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);

				$date1 	= date_create(date('Y-m-d'));
				$date2 	= date_create($row2['user_certificate_next2']);
				$diff 	= date_diff($date1,$date2);
				$selisih = $diff->format("%a");
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($selisih<30 || $date2<$date1) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	} ?>      		
	      	</td>
	      	<td class="text-center">
	      		<?php //SC
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 24 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);

				$date1 	= date_create(date('Y-m-d'));
				$date2 	= date_create($row2['user_certificate_next2']);
				$diff 	= date_diff($date1,$date2);
				$selisih = $diff->format("%a");
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($selisih<30 || $date2<$date1) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	} ?>	      		
	      	</td>
	      	<td class="text-center">
	      		<?php //PASSPORT
	      		$sql2 	= "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 25 LIMIT 1";
				$h2 	= mysqli_query($conn,$sql2);
				$row2 	= mysqli_fetch_assoc($h2);

				$date1 	= date_create(date('Y-m-d'));
				$date2 	= date_create($row2['user_certificate_next2']);
				$diff 	= date_diff($date1,$date2);
				$selisih = $diff->format("%a");
				echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a><br/>';
		      	if($selisih<150 || $date2<$date1) {
		      		echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
		      	}else{
		      		echo '<p class="text-info">next: '.$row2['user_certificate_next'].'</p>';
		      	} ?>	      		
	      	</td>     		      		      		      	
	      </tr>
	      <?php } ?>
	    </tbody>
	</table>
	<?php }else{ echo 'No data available'; } ?> 
</div> 
