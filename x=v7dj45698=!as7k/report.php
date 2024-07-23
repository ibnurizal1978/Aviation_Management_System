<?php 
require_once 'header.php';
//require_once 'components.php';
?>


<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">
        <div class="row invisible" data-toggle="appear">
            <div class="col-md-12">
            <div class="text-center" align="center">
                <a class="btn btn-success" href="report.php">Crew Certificate & Treshold</a> <a class="btn btn-success" href="report-pilot-hours.php">Pilot Hours Report</a> <a class="btn btn-success" href="report-eob-hours.php">EOB Hours Report</a> <a class="btn btn-success" href="report-aircraft-hours.php">Aircraft Hours Report</a>
            </div></div><p>&nbsp;</p>
            <!-- Row #3 -->
            <div class="col-md-12">
                <div class="block block-rounded block-bordered">
                    <div class="block-header block-header-default border-b">
                        <h3 class="block-title">Treshold Parts</h3>
                    </div>
                    <div class="block-content">
                        <?php
                        $sql    = "SELECT SELECT parts_id, parts_name, parts_number, parts_treshold, qty FROM tbl_parts a INNER JOIN tbl_parts_location_stock b USING (parts_id) FROM tbl_parts WHERE qty < parts_treshold ORDER BY parts_stock DESC";
                        $h      = mysqli_query($conn, $sql);
                        ?>                                    
                        <table class="table table-borderless table-striped">
                            <thead>
                                <tr>
                                    <th width="30%">Parts Name</th>
                                    <th>Parts No.</th>  
                                    <th>Current Stock</th>
                                    <th>Treshold</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($h)) { ?>
                                <tr>
                                    <td><?php echo $row["parts_name"]; ?></td>
                                    <td><?php echo $row["parts_number"]; ?></td>
                                    <td><?php echo $row["qty"]; ?></td>  
                                    <td>
                                    <?php 
                                    if($row['parts_stock']<$row['parts_treshold']) {
                                      echo "<font color=ff0000>".$row['parts_treshold']."</font>";
                                    }else{
                                      echo $row['parts_treshold'];
                                    } 
                                    ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row invisible" data-toggle="appear">
            <div class="col-md-12">
                <div class="block block-rounded block-bordered">
                    <div class="block-header block-header-default border-b bg-corporate">
                        <h3 class="block-title">Due List Crew License</h3>
                    </div>
                    <div class="block-content" data-toggle="slimscroll" data-height="350px">
                    <?php
                    $sql    = "SELECT  user_id,full_name FROM tbl_user WHERE client_id = '".$_SESSION['client_id']."' AND department_id = 4 AND user_active_status = 1 ORDER BY full_name";
                    $h      = mysqli_query($conn,$sql);
                    $tgl    = date('Y-m-d');
                    ?>
                        <table class="table table-sm table-vcenter table-bordered table-striped">  
                        <thead> 
                          <tr style="background: #efebee; border: 1px; font-size: 8pt">
                            <th  width="30%" class="text-center">Name</th>
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
                        </thead>  
                        <tbody>
                          <?php while ($row = mysqli_fetch_assoc($h)) { ?>
                          <tr style="background: #fff; color: #555555; border: 1px solid #000; font-size: 8pt">
                            <td><?php echo $row['full_name']; ?></td>
                            <td class="text-center">
                                <?php //PPC
                                $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 15 LIMIT 1";
                                $h2     = mysqli_query($conn,$sql2);
                                $row2   = mysqli_fetch_assoc($h2);

                                $date1  = date_create(date('Y-m-d'));
                                $date2  = date_create($row2['user_certificate_next2']);
                                $diff   = date_diff($date1,$date2);
                                $selisih = $diff->format("%a");
                                echo $row2['user_certificate_date'].'<br/>';
                                if($selisih<30 || $date2<$date1) {
                                    echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
                                }else{
                                    echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
                                } ?>
                            </td>
                            <td class="text-center">
                                <?php //MEDEX
                                $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 16 LIMIT 1";
                                $h2     = mysqli_query($conn,$sql2);
                                $row2   = mysqli_fetch_assoc($h2);

                                $date1  = date_create(date('Y-m-d'));
                                $date2  = date_create($row2['user_certificate_next2']);
                                $diff   = date_diff($date1,$date2);
                                $selisih = $diff->format("%a");
                                echo $row2['user_certificate_date'].'<br/>';
                                if($selisih<30 || $date2<$date1) {
                                    echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
                                }else{
                                    echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
                                } ?>                
                            </td>
                            <td class="text-center">
                                <?php //NEP
                                $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 17 LIMIT 1";
                                $h2     = mysqli_query($conn,$sql2);
                                $row2   = mysqli_fetch_assoc($h2);

                                $date1  = date_create(date('Y-m-d'));
                                $date2  = date_create($row2['user_certificate_next2']);
                                $diff   = date_diff($date1,$date2);
                                $selisih = $diff->format("%a");
                                echo $row2['user_certificate_date'].'<br/>';
                                if($selisih<30 || $date2<$date1) {
                                    echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
                                }else{
                                    echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
                                } ?>            
                            </td>
                            <td class="text-center">
                                <?php //ALAR
                                $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 18 LIMIT 1";
                                $h2     = mysqli_query($conn,$sql2);
                                $row2   = mysqli_fetch_assoc($h2);

                                $date1  = date_create(date('Y-m-d'));
                                $date2  = date_create($row2['user_certificate_next2']);
                                $diff   = date_diff($date1,$date2);
                                $selisih = $diff->format("%a");
                                echo $row2['user_certificate_date'].'<br/>';
                                if($selisih<30 || $date2<$date1) {
                                    echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
                                }else{
                                    echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
                                } ?>            
                            </td>
                            <td class="text-center">
                                <?php //WINDSHEAR
                                $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 19 LIMIT 1";
                                $h2     = mysqli_query($conn,$sql2);
                                $row2   = mysqli_fetch_assoc($h2);

                                $date1  = date_create(date('Y-m-d'));
                                $date2  = date_create($row2['user_certificate_next2']);
                                $diff   = date_diff($date1,$date2);
                                $selisih = $diff->format("%a");
                                echo $row2['user_certificate_date'].'<br/>';
                                if($selisih<30 || $date2<$date1) {
                                    echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
                                }else{
                                    echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
                                } ?>            
                            </td>
                            <td class="text-center">
                                <?php //AVSEC
                                $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 20 LIMIT 1";
                                $h2     = mysqli_query($conn,$sql2);
                                $row2   = mysqli_fetch_assoc($h2);

                                $date1  = date_create(date('Y-m-d'));
                                $date2  = date_create($row2['user_certificate_next2']);
                                $diff   = date_diff($date1,$date2);
                                $selisih = $diff->format("%a");
                                echo $row2['user_certificate_date'].'<br/>';
                                if($selisih<30 || $date2<$date1) {
                                    echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
                                }else{
                                    echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
                                } ?>                
                            </td>
                            <td class="text-center">
                                <?php //CRM
                                $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 21 LIMIT 1";
                                $h2     = mysqli_query($conn,$sql2);
                                $row2   = mysqli_fetch_assoc($h2);

                                $date1  = date_create(date('Y-m-d'));
                                $date2  = date_create($row2['user_certificate_next2']);
                                $diff   = date_diff($date1,$date2);
                                $selisih = $diff->format("%a");
                                echo $row2['user_certificate_date'].'<br/>';
                                if($selisih<30 || $date2<$date1) {
                                    echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
                                }else{
                                    echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
                                } ?>            
                            </td>
                            <td class="text-center">
                                <?php //DG
                                $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 22 LIMIT 1";
                                $h2     = mysqli_query($conn,$sql2);
                                $row2   = mysqli_fetch_assoc($h2);

                                $date1  = date_create(date('Y-m-d'));
                                $date2  = date_create($row2['user_certificate_next2']);
                                $diff   = date_diff($date1,$date2);
                                $selisih = $diff->format("%a");
                                echo $row2['user_certificate_date'].'<br/>';
                                if($selisih<30 || $date2<$date1) {
                                    echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
                                }else{
                                    echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
                                } ?>                
                            </td>
                            <td class="text-center">
                                <?php //TRI
                                $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 23 LIMIT 1";
                                $h2     = mysqli_query($conn,$sql2);
                                $row2   = mysqli_fetch_assoc($h2);

                                $date1  = date_create(date('Y-m-d'));
                                $date2  = date_create($row2['user_certificate_next2']);
                                $diff   = date_diff($date1,$date2);
                                $selisih = $diff->format("%a");
                                echo $row2['user_certificate_date'].'<br/>';
                                if($selisih<30 || $date2<$date1) {
                                    echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
                                }else{
                                    echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
                                } ?>            
                            </td>
                            <td class="text-center">
                                <?php //SC
                                $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 24 LIMIT 1";
                                $h2     = mysqli_query($conn,$sql2);
                                $row2   = mysqli_fetch_assoc($h2);

                                $date1  = date_create(date('Y-m-d'));
                                $date2  = date_create($row2['user_certificate_next2']);
                                $diff   = date_diff($date1,$date2);
                                $selisih = $diff->format("%a");
                                echo $row2['user_certificate_date'].'<br/>';
                                if($selisih<30 || $date2<$date1) {
                                    echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
                                }else{
                                    echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
                                } ?>                
                            </td>
                            <td class="text-center">
                                <?php //PASSPORT
                                $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2,user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 25 LIMIT 1";
                                $h2     = mysqli_query($conn,$sql2);
                                $row2   = mysqli_fetch_assoc($h2);

                                $date1  = date_create(date('Y-m-d'));
                                $date2  = date_create($row2['user_certificate_next2']);
                                $diff   = date_diff($date1,$date2);
                                $selisih = $diff->format("%a");
                                echo $row2['user_certificate_date'].'<br/>';
                                if($selisih<150 || $date2<$date1) {
                                    echo '<p class="text-danger">next: '.$row2['user_certificate_next'].'</p>';
                                }else{
                                    echo '<p class="text-success">next: '.$row2['user_certificate_next'].'</p>';
                                } ?>                
                            </td>                                           
                          </tr>
                          <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
 
        <div class="row invisible" data-toggle="appear">
            <div class="col-md-12">
                <div class="block block-rounded block-bordered">
                    <div class="block-header block-header-default border-b bg-corporate-light">
                        <h3 class="block-title">Due List Engineer License</h3>
                    </div>
                    <div class="block-content" data-toggle="slimscroll" data-height="350px" data-color="#9ccc65" >
                    <?php
                    $sql    = "SELECT  user_id,full_name,user_position FROM tbl_user WHERE client_id = '".$_SESSION['client_id']."' AND user_ID NOT IN ('35','15') AND department_id = 1 AND user_active_status = 1 ORDER BY full_name";
                    $h      = mysqli_query($conn,$sql);
                    ?>
                        <table class="table table-sm table-vcenter table-bordered table-striped">  
                            <thead> 
                              <tr style="background: #efebee; font-size: 8pt"> 
                                <th rowspan="2" class="text-center" width="50%">Name</th>
                                <th colspan="2" width="30%" class="text-center">Human Factor</th>
                                <th colspan="2" width="30%" class="text-center">Aircraft Type Rating</th>
                                <th colspan="6" width="30%" class="text-center">Mandatory Training</th>
                                <th rowspan="2"class="text-center">Basic Inspector</th>
                                <th rowspan="2"class="text-center">RII</th>
                              </tr>
                              <tr style="background: #efebee; font-size: 8pt">
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
                            </thead>  
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($h)) { ?>
                            <tr style="background: #fff; color: #555555; border: 1px solid #000; font-size: 8pt">
                                <td><?php echo $row['full_name']; ?></td>
                                <td class="text-center">
                                    <?php //Human Factor Initial
                                    $sql2   = "SELECT certificate_master_name,user_certificate_file, date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 1 LIMIT 1";
                                    $h2     = mysqli_query($conn,$sql2);
                                    $row2 = mysqli_fetch_assoc($h2);
                                    echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
                                    mysqli_free_result($h2);
                                    ?>      
                                </td>           
                                <td class="text-center">
                                    <?php //Human Factor Recurrent
                                    $sql2   = "SELECT CURDATE(), DATEDIFF(user_certificate_next, CURDATE()) AS selisih,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date,date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 2 LIMIT 1";
                                    $h2     = mysqli_query($conn,$sql2);
                                    $row2   = mysqli_fetch_assoc($h2);
                                    
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
                                    $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 3 LIMIT 1";
                                    $h2     = mysqli_query($conn,$sql2);
                                    $row2   = mysqli_fetch_assoc($h2);
                                    echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
                                    mysqli_free_result($h2);
                                    ?>              
                                </td>
                                <td class="text-center">
                                    <?php //Aircraft Factor Recurrent
                                    $sql2   = "SELECT CURDATE(), DATEDIFF(user_certificate_next, CURDATE()) AS selisih,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date,date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, user_certificate_file, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2 FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 4 LIMIT 1";
                                    $h2     = mysqli_query($conn,$sql2);
                                    $row2   = mysqli_fetch_assoc($h2);
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
                                    $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 5 LIMIT 1";
                                    $h2     = mysqli_query($conn,$sql2);
                                    $row2   = mysqli_fetch_assoc($h2);
                                    echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
                                    mysqli_free_result($h2); 
                                    ?>              
                                </td>
                                <td class="text-center">
                                    <?php //SMS initial
                                    $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 6 LIMIT 1";
                                    $h2     = mysqli_query($conn,$sql2);
                                    $row2   = mysqli_fetch_assoc($h2);
                                    echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
                                    mysqli_free_result($h2); 
                                    ?>              
                                </td>
                                <td class="text-center">
                                    <?php //DG initial
                                    $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 8 LIMIT 1";
                                    $h2     = mysqli_query($conn,$sql2);
                                    $row2   = mysqli_fetch_assoc($h2);
                                    echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
                                    mysqli_free_result($h2); 
                                    ?>              
                                </td>
                                <td class="text-center">
                                    <?php //Avsec initial
                                    $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 10 LIMIT 1";
                                    $h2     = mysqli_query($conn,$sql2);
                                    $row2   = mysqli_fetch_assoc($h2);
                                    echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
                                    mysqli_free_result($h2); 
                                    ?>              
                                </td>
                                <td class="text-center">
                                    <?php //CASR
                                    $sql2   = "SELECT CURDATE(), DATEDIFF(user_certificate_next, CURDATE()) AS selisih,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date,date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, user_certificate_file, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2 FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 26 LIMIT 1";
                                    $h2     = mysqli_query($conn,$sql2);
                                    $row2   = mysqli_fetch_assoc($h2);
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
                                    $sql2   = "SELECT CURDATE(), DATEDIFF(user_certificate_next, CURDATE()) AS selisih,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date,date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next, user_certificate_file, date_format(user_certificate_next, '%Y-%m-%d') as user_certificate_next2 FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 27 LIMIT 1";
                                    $h2     = mysqli_query($conn,$sql2);
                                    $row2   = mysqli_fetch_assoc($h2);
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
                                    $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 12 LIMIT 1";
                                    $h2     = mysqli_query($conn,$sql2);
                                    $row2   = mysqli_fetch_assoc($h2);
                                    echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
                                    mysqli_free_result($h2);
                                    ?>              
                                </td>
                                <td class="text-center">
                                    <?php //RII initial
                                    $sql2   = "SELECT certificate_master_name,date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, user_certificate_file FROM tbl_user_certificate a INNER JOIN tbl_certificate_master b USING (certificate_master_id) WHERE user_id = '".$row['user_id']."' AND certificate_master_id = 13 LIMIT 1";
                                    $h2     = mysqli_query($conn,$sql2);
                                    $row2   = mysqli_fetch_assoc($h2);
                                    echo '<a href='.$base_url.'uploads/certificate/'.$row2['user_certificate_file'].' target="_blank">'.$row2['user_certificate_date'].'</a>';
                                    mysqli_free_result($h2); 
                                    ?>              
                                </td>                                           
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
             
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
