<?php 
require_once 'header.php';
//require_once 'components.php';
function AddTimeToStr($aElapsedTimes) {
  $totalHours = 0;
  $totalMinutes = 0;

  foreach($aElapsedTimes as $time) {
    $timeParts = explode(":", $time);
    @$h = $timeParts[0];
    @$m = $timeParts[1];
    $totalHours += $h;
    $totalMinutes += $m;
  }

  $additionalHours = floor($totalMinutes / 60);
  $minutes = $totalMinutes % 60;
  $hours = $totalHours + $additionalHours;

  $strMinutes = strval($minutes);
  if ($minutes < 10) {
      $strMinutes = "0" . $minutes;
  }

  $strHours = strval($hours);
  if ($hours < 10) {
      $strHours = "0" . $hours;
  }

  return($strHours . ":" . $strMinutes);
}

?>

<!-- Side Overlay-->
<aside id="side-overlay">
    <!-- Side Overlay Scroll Container -->
    <div id="side-overlay-scroll">
        <!-- Side Header -->
        <div class="content-header content-header-fullrow">
            <div class="content-header-section align-parent">
                <button type="button" class="btn btn-circle btn-dual-secondary align-v-r" data-toggle="layout" data-action="side_overlay_close">
                    <i class="fa fa-times text-danger"></i>
                </button>

                <div class="content-header-item">
                    <a class="align-middle link-effect text-primary-dark font-w600" href="#">Filter</a>
                </div>
                <!-- END User Info -->
            </div>
        </div>
        <!-- END Side filter -->

        <!-- side kanan -->
        <div class="content-side">
            <!-- Search -->
            <div class="block pull-t pull-r-l">
                <div class="block-content block-content-full block-content-sm bg-body-light">
                    <form action="aircraft.php" method="post">
                        <input type="hidden" name="s" value="1091vdf8ame151">
                        <div class="input-group">
                            <input type="text" class="form-control" id="side-overlay-search" name="txt_search" placeholder="Search..">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-secondary px-10">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Search -->

            <!-- Profile -->
            <div class="block pull-r-l">
                <div class="block-header bg-body-light">
                    <h3 class="block-title">
                        <i class="fa fa-fw fa-pencil font-size-default mr-5"></i>Sort by
                    </h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                    </div>
                </div>
                <div class="block-content">
                    <form action="aircraft.php" method="post">
                        <div class="form-group mb-15">
                            <div class="form-material floating">
                                <select class="form-control" id="material-select2" name="s">
                                    <option></option><!-- Empty value for demostrating material select box -->
                                    <option value="1">By Name A-Z</option>
                                </select>
                                <label for="material-select2">Please Select</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-block btn-alt-primary">
                                    <i class="fa fa-refresh mr-5"></i> View
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END filter -->
        </div>
        <!-- END Side filter -->
    </div>
    <!-- END Side Overlay Scroll Container -->
</aside>
<!-- END Side Overlay -->

<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">
    <?php
    $sql    = "SELECT aircraft_master_id,aircraft_reg_code, aircraft_total_ldg,aircraft_total_hrs FROM tbl_aircraft_master WHERE aircraft_master_id = 3 AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
    $h      = mysqli_query($conn, $sql);
    $row    = mysqli_fetch_assoc($h);
    ?>   
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Aircraft Reg Code: <?php echo $row['aircraft_reg_code'] ?></h3>
                <div class="block-options">
                    <div class="block-options-item">
                        <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                            <i class="fa fa-filter"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="block-content">
                <!--due by month-->
                <table class="table table-sm table-vcenter">
                    <thead>
                        <tr>
                            <th>Reg. Code</th>
                            <th>Type</th>
                            <th>Unit Interval</th>
                            <th>Last</th>
                            <th>Next Due</th>
                            <th>Time remaining</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "SELECT item_number,description,part_number,serial_number,installed_date, DATEDIFF(next_due,CURDATE()) AS selisih, date_format(installed_date, '%d/%m/%Y') as installed_date,date_format(next_due, '%d/%m/%Y') as next_due,ldg,mth,hrs FROM tbl_aircraft_parts WHERE (DATEDIFF(next_due, CURDATE()) < 90 AND mth<>0) OR (ldg<>0 AND ldg-100 < '".$row['aircraft_total_ldg']."') AND aircraft_master_id = '".$row['aircraft_master_id']."' ORDER by selisih,next_due";
                        $h2      = mysqli_query($conn,$sql2);
                        while ($row2 = mysqli_fetch_assoc($h2)) {
                        ?>
                        <tr>
                            <td><?php echo $row2['item_number'] ?></td>
                            <td><?php echo '<b>'.$row2['description'].'</b><br/>P/N: '.$row2['part_number'].'<br/>S/N: '.$row2['serial_number'] ?></td>
                            <td><?php echo 'MOS: '.$row2['mth'].'<br/>HRS: '.$row2['hrs'].'<br/>LDG: '.$row2['ldg'] ?></td>
                            <td><?php echo $row2['installed_date'] ?></td>
                            <td><?php 
                                if($row2['next_due']=='00/00/0000') { echo ''; 
                                }else{
                                if($row2['selisih']<0) {
                                    echo '<font class=text-danger>'.$row2['next_due'].'</font>';
                                    }else{
                                echo '<font class=text-success>'.$row2['next_due'].'</font>';
                                    }
                                }                                
                                echo '<br/>';
                                if($row2['ldg']==0) {
                                    echo '';
                                }else{
                                if($row['aircraft_total_ldg']>$row2['ldg']) {
                                    echo '<font class=text-danger>'.$row2['ldg'].'</font>';
                                }else{
                                    echo '<font class=text-success>'.$row2['ldg'].'</font>';
                                }
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if($row2['next_due']=='00/00/0000') { 
                                    echo ''; 
                                    }else{
                                if($row2['selisih']<0) {
                                    echo '<font class=text-danger>'.$row2['selisih'].'d</font>';
                                    }else{
                                echo '<font class=text-success>'.$row2['selisih'].'d</font>';
                                    }
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit" href="aircraft-detail.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } mysqli_free_result($rs_result); ?>
                    </tbody>
                </table>
                <!--due by month-->
                <br/><br/>
                <!--due by ldg-->
                <table class="table table-sm table-vcenter">
                    <thead>
                        <tr>
                            <th>Reg. Code</th>
                            <th>Type</th>
                            <th>Unit Interval</th>
                            <th>Last</th>
                            <th>Next Due</th>
                            <th>Time remaining</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "SELECT item_number,description,part_number,serial_number,installed_date, DATEDIFF(next_due,CURDATE()) AS selisih, date_format(installed_date, '%d/%m/%Y') as installed_date,date_format(next_due, '%d/%m/%Y') as next_due,ldg,mth,hrs FROM tbl_aircraft_parts WHERE ldg-100 < '".$row['aircraft_total_ldg']."' AND ldg<>0 AND aircraft_master_id = '".$row['aircraft_master_id']."'";
                        $h2      = mysqli_query($conn,$sql2);
                        while ($row2 = mysqli_fetch_assoc($h2)) {
                        ?>
                        <tr>
                            <td><?php echo $row2['item_number'] ?></td>
                            <td><?php echo '<b>'.$row2['description'].'</b><br/>P/N: '.$row2['part_number'].'<br/>S/N: '.$row2['serial_number'] ?></td>
                            <td><?php echo 'MOS: '.$row2['mth'].'<br/>HRS: '.$row2['hrs'].'<br/>LDG: '.$row2['ldg'] ?></td>
                            <td><?php echo $row2['installed_date'] ?></td>
                            <td><?php if($row2['next_due']=='00/00/0000') { echo ''; }else{ echo $row2['next_due']; } ?></td>
                            <td>
                                <?php 
                                if($row2['next_due']=='00/00/0000') { 
                                    echo ''; 
                                    }else{
                                if($row2['selisih']<0) {
                                    echo '<font class=text-danger>'.$row2['selisih'].'d</font>';
                                    }else{
                                echo '<font class=text-success>'.$row2['selisih'].'d</font>';
                                    }
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit" href="aircraft-detail.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } mysqli_free_result($rs_result); ?>
                    </tbody>
                </table>
                <!--due by ldg--> 
                <br/><br/>
                <!--due by hrs-->
                <table class="table table-sm table-vcenter">
                    <thead>
                        <tr>
                            <th>Reg. Code</th>
                            <th>Type</th>
                            <th>Unit Interval</th>
                            <th>Last</th>
                            <th>Next Due</th>
                            <th>Time remaining</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "SELECT item_number,description,part_number,serial_number,installed_date, DATEDIFF(next_due,CURDATE()) AS selisih, date_format(installed_date, '%d/%m/%Y') as installed_date,date_format(next_due, '%d/%m/%Y') as next_due,ldg,mth,hrs FROM tbl_aircraft_parts WHERE hrs-200 < '".$row['aircraft_total_hrs']."' AND hrs<>0 AND aircraft_master_id = '".$row['aircraft_master_id']."'";
                        $h2      = mysqli_query($conn,$sql2);
                        while ($row2 = mysqli_fetch_assoc($h2)) {
                            //$time1 = $row['aircraft_total_hrs'];
                            //$time2 = $row2['hrs'];
                           // echo ($time1 - $time2);

                            //total_hrs buat tampil di bawah
                            $start = strtotime($row['aircraft_total_hrs']);
                            $end = strtotime($row2['hrs']);
                            $jumlah1 = $start - $end;
                            $total_hrs = date("H:i", $jumlah1);
                            echo $row['aircraft_total_hrs'].'-'.$start.'<br/>';
                            echo $end.'<br/>';
                            echo $total_hrs.'<br/><br/>';

                            $time1 = substr($row['aircraft_total_hrs'],0,6);
                            $time2 = $row2['hrs'];
                            $total_hrs2  = AddTimeToStr(array($time2, $time1));
                            echo 'TTL: '.$total_hrs2.'<br/>';
                        ?>
                        <tr>
                            <td><?php echo $row2['item_number'] ?></td>
                            <td><?php echo '<b>'.$row2['description'].'</b><br/>P/N: '.$row2['part_number'].'<br/>S/N: '.$row2['serial_number'] ?></td>
                            <td><?php echo 'MOS: '.$row2['mth'].'<br/>HRS: '.$row2['hrs'].'<br/>LDG: '.$row2['ldg'] ?></td>
                            <td><?php echo $row2['installed_date'] ?></td>
                            <td><?php if($row2['next_due']=='00/00/0000') { echo ''; }else{ echo $row2['next_due']; } ?></td>
                            <td>
                                <?php 
                                if($row2['next_due']=='00/00/0000') { 
                                    echo ''; 
                                    }else{
                                if($row2['selisih']<0) {
                                    echo '<font class=text-danger>'.$row2['selisih'].'d</font>';
                                    }else{
                                echo '<font class=text-success>'.$row2['selisih'].'d</font>';
                                    }
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit" href="aircraft-detail.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } mysqli_free_result($rs_result); ?>
                    </tbody>
                </table>
                <!--due by hrs--> 
            </div>
        </div>
        <!-- END Small Table -->
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
