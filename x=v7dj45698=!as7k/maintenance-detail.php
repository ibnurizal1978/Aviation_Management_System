<?php 
require_once 'header.php';
ini_set('display_errors',1);  error_reporting(E_ALL);
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

$aircraft_master_id     = input_data(filter_var($_REQUEST['aircraft_master_id'],FILTER_SANITIZE_STRING));
$due_value              = input_data(filter_var($_REQUEST['due_value2'],FILTER_SANITIZE_STRING));
$projection_type        = input_data(filter_var($_REQUEST['projection_type2'],FILTER_SANITIZE_STRING));
$tanggal_detail         = input_data(filter_var($_REQUEST['tanggal_detail'],FILTER_SANITIZE_STRING));
$ac_hrs_target          = input_data(filter_var($_REQUEST['ac_hrs_target'],FILTER_SANITIZE_STRING));
$ac_ldg_target          = input_data(filter_var($_REQUEST['ac_ldg_target'],FILTER_SANITIZE_STRING));
$eng_hrs_target         = input_data(filter_var($_REQUEST['eng_hrs_target'],FILTER_SANITIZE_STRING));
$eng_ldg_target         = input_data(filter_var($_REQUEST['eng_ldg_target'],FILTER_SANITIZE_STRING));
$prop_hrs_target        = input_data(filter_var($_REQUEST['prop_hrs_target'],FILTER_SANITIZE_STRING));
$ac_hrs_target = str_replace('.', ':', $ac_hrs_target);
$eng_hrs_target = str_replace('.', ':', $eng_hrs_target);
$prop_hrs_target = str_replace('.', ':', $prop_hrs_target);

function minutesToHours($minutes) 
{ 
    $hours = (int)($minutes / 60); 
    $minutes -= $hours * 60; 
    return sprintf("%02d:%02.0f", $hours, $minutes); 
}

?>

<script type="text/javascript">
(function (global) {

    if(typeof (global) === "undefined")
    {
        throw new Error("window is undefined");
    }

    var _hash = "!";
    var noBackPlease = function () {
        global.location.href += "#";

        // making sure we have the fruit available for juice....
        // 50 milliseconds for just once do not cost much (^__^)
        global.setTimeout(function () {
            global.location.href += "!";
        }, 50);
    };
    
    // Earlier we had setInerval here....
    global.onhashchange = function () {
        if (global.location.hash !== _hash) {
            global.location.hash = _hash;
        }
    };

    global.onload = function () {
        
        noBackPlease();

        // disables backspace on page except on input fields and textarea..
        document.body.onkeydown = function (e) {
            var elm = e.target.nodeName.toLowerCase();
            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                e.preventDefault();
            }
            // stopping event bubbling up the DOM tree..
            e.stopPropagation();
        };
        
    };

})(window);    
</script>

<!-- buat select all checkbox -->
<script type="text/javascript">
    function selectAll() {
        var items = document.getElementsByName('aircraft_parts_id[]');
        for (var i = 0; i < items.length; i++) {
            if (items[i].type == 'checkbox')
                items[i].checked = true;
        }
    }

    function UnSelectAll() {
        var items = document.getElementsByName('aircraft_parts_id[]');
        for (var i = 0; i < items.length; i++) {
            if (items[i].type == 'checkbox')
                items[i].checked = false;
        }
    } 
</script>

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
                    <form action="maintenance-detail.php" method="post">
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
                    <form action="maintenance-detail.php" method="post">
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

      <?php if(@$ntf[0]=='r827ao') { ?>
        <div class="alert alert-danger alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <p class="mb-0">Please checklist parts you want to do maintenance</p>
        </div>
      <?php } ?>

    <?php
    $sql    = "SELECT *,aircraft_ac_total_hrs as aircraft_total_hrs, aircraft_eng_1_total_hrs, aircraft_prop_total_hrs, aircraft_ac_total_ldg as aircraft_total_ldg FROM tbl_aircraft_master WHERE aircraft_master_id = '".$aircraft_master_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
    $h      = mysqli_query($conn, $sql);
    $row    = mysqli_fetch_assoc($h);

    $sql1   = "SELECT date_format(afml_date, '%d/%m/%Y') as afml_date FROM tbl_afml WHERE aircraft_reg_code = '".$row['aircraft_reg_code']."' ORDER BY afml_date DESC LIMIT 1";
    $h1     = mysqli_query($conn, $sql1);
    $row1   = mysqli_fetch_assoc($h1);
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

                <table width="100%" class="table table-sm table-bordered">
                    <tr>
                        <td align="center" colspan="3"><h3>DUE LIST</h3></td>
                    </tr>
                    <tr>
                        <td><b><?php echo $row['aircraft_reg_code'] ?></b></td>
                        <td colspan="2" align="right">Printed Date: <?php echo date('d/m/Y') ?><br/>A/C Last Record at: <?php echo $row1['afml_date'] ?></td>
                    </tr>
                    <tr>
                        <td><b>A/C Type:</b> <?php echo $row['aircraft_serial_number'] ?></td>
                        <td><b>Eng Type:</b> <?php echo $row['engine_part_number'] ?></td>
                        <td><b>Prop Type:</b> <?php echo $row['prop_part_number'] ?></td>
                    </tr>
                    <tr>
                        <td><b>S/N:</b> <?php echo $row['aircraft_reg_code'] ?></td>
                        <td><b>S/N:</b> <?php echo $row['engine_serial_number'] ?></td>
                        <td><b>S/N:</b> <?php echo $row['prop_serial_number'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Hours:</b> <?php echo minutesToHours($row['aircraft_total_hrs']) ?></td>
                        <td><b>Hours:</b> <?php echo minutesToHours($row['aircraft_eng_1_total_hrs']) ?></td>
                        <td><b>Hours:</b> <?php echo minutesToHours($row['aircraft_prop_total_hrs']) ?></td>
                    </tr>
                    <tr>
                        <td><b>LDG:</b> <?php echo $row['aircraft_total_ldg'] ?></td>
                        <td><b>Cycle:</b> <?php echo $row['aircraft_eng_1_total_ldg'] ?></td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
                <br/><br/>
                <form action="maintenance-pre-wo-add.php" method="POST">

                    master ID <input type="text" name="aircraft_master_id" value="<?php echo $aircraft_master_id ?>"><br/>
                    due value <input type="text" name="due_value" value="<?php echo $due_value ?>"><br/>
                    proj type <input type="text" name="projection_type" value="<?php echo $projection_type ?>"><br/>
                    date <input type="text" name="tanggal_detail" value="<?php echo $tanggal_detail ?>"><br/>
                    ac hrs target <input type="text" name="ac_hrs_target" value="<?php echo $ac_hrs_target ?>"><br/>
                    ac ldg target <input type="text" name="ac_ldg_target" value="<?php echo $ac_ldg_target ?>"><br/>
                    eng hrs target <input type="text" name="eng_hrs_target" value="<?php echo $eng_hrs_target ?>"><br/>
                    eng ldg target <input type="text" name="eng_ldg_target" value="<?php echo $eng_ldg_target ?>"><br/>
                    prop hrs target <input type="text" name="prop_hrs_target" value="<?php echo $prop_hrs_target ?>"><br/>
                    reg code <input type="text" name="reg_code" value="<?php echo $row['aircraft_reg_code'] ?>"><br/>

                    <a href="#" onclick="javascript:window.print();" class="btn btn-success mr-5 mb-5" /><i class="si si-printer"></i> Print</a> <a href="maintenance.php" class="btn btn-warning mr-5 mb-5">Reset</a><!-- <a href="maintenance-wo1-new.php" class="btn btn-info mr-5 mb-5">Create WO</a>-->
                    <!--due by month-->
                    <table class="table table-sm table-vcenter">
                        <thead>
                            <tr>
                                <th>Reg. Code<br/>(<a href="javascript:void(0);" onclick='selectAll()'>select all</a>)</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Unit Interval</th>
                                <th>C/W</th>
                                <th>Next Due</th>
                                <th>Time remaining</th>
                                <!--<th class="text-center">Actions</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql2   = "SELECT aircraft_parts_id, item_number,description,part_number,serial_number,installed_date, DATEDIFF(next_install_date,CURDATE()) AS selisih, date_format(installed_date, '%d/%m/%Y') as installed_date,date_format(next_install_date, '%d/%m/%Y') as next_install_date,ldg,mth,hrs,last_ldg,date_format(last_hrs, '%H:%i') as last_hrs,next_ldg,next_hrs FROM tbl_aircraft_parts WHERE (next_install_date BETWEEN '".$row['aircraft_master_last_inspection']."' AND '".$tanggal_detail."') OR (next_ldg BETWEEN '".$ac_ldg_target."' AND '".$row['aircraft_total_ldg']."') OR (next_hrs BETWEEN '".$ac_hrs_target."' AND '".$row['aircraft_total_hrs']."') AND aircraft_master_id = '".$aircraft_master_id."'";
                            $h2      = mysqli_query($conn,$sql2);
                            while ($row2 = mysqli_fetch_assoc($h2)) {
                            ?>
                            <tr>
                                <td><input type="checkbox" name="aircraft_parts_id[]" value="<?php echo $row2['aircraft_parts_id'] ?>"></td>
                                <td><?php echo $row2['item_number'] ?></td>
                                <td><?php echo '<b>'.$row2['description'].'</b><br/>P/N: '.$row2['part_number'].'<br/>S/N: '.$row2['serial_number'] ?></td>
                                <td>
                                    <?php 
                                    echo 'MOS: '.$row2['mth'].'<br/>';
                                    echo 'HRS: '.$row2['hrs'].'<br/>';
                                    echo 'LDG: '.$row2['ldg'] ?>
                                </td>
                                <td><!-- last -->
                                    <?php 
                                    if($row2['installed_date']=='00/00/0000') { echo '<br/>'; }else{ echo $row2['installed_date'].'<br/>'; }
                                    if($row2['last_hrs']=='00:00') { echo '<br/>'; }else{ echo $row2['last_hrs'].'<br/>'; }
                                    if($row2['last_ldg']==0) { echo '<br/>'; }else{ echo $row2['last_ldg']; }
                                    ?>
                                </td>
                                <td><!-- next due -->
                                    <?php 
                                    if($row2['next_install_date']=='00/00/0000') { echo '<br/>'; }else{ echo $row2['next_install_date'].'<br/>'; }
                                    if($row2['last_hrs']=='00:00') { echo '<br/>'; }else{ echo $row2['hrs']+$row2['last_hrs'].'<br/>'; }
                                    if($row2['last_ldg']==0) { echo '<br/>'; }else{ echo $row2['next_ldg']; }
                                    ?>    
                                </td>
                                <td><!--time remaining-->
                                    <?php 
                                    if($row2['next_install_date']=='00/00/0000') {
                                        echo '<br/>'; 
                                    }else{
                                        if($row2['selisih']<0) {
                                            echo '<font class=text-danger>'.$row2['selisih'].'d (OVD)</font><br/>';
                                        }else{
                                            echo '<font class=text-success>'.$row2['selisih'].'d</font><br/>';
                                        }
                                    }

                                    if($row2['last_hrs']=='00:00') { 
                                        echo '<br/>'; 
                                    }else{
                                        $total_jam = $row['aircraft_total_hrs']-$row2['last_hrs'];
                                        if($total_jam<0) { 
                                            echo '<font class=text-danger>'.minutesToHours($total_jam).'</font><br/>';
                                        }else{
                                            echo '<font class=text-success>'.minutesToHours($total_jam).'</font><br/>';
                                        } 
                                    }


                                    if($row2['last_ldg']==0) { 
                                        echo '<br/>'; 
                                    }else{ 
                                        $total_ldg = $row2['next_ldg']-$row['aircraft_total_ldg'];
                                        if($total_ldg<0) {
                                            echo '<font class=text-danger>'.$total_ldg.'</font><br/>';
                                        }else{
                                            echo '<font class=text-success>'.$total_ldg.'</font><br/>';
                                        } 
                                    }
                                    ?>
                                </td>
                                <!--<td class="text-center">
                                    <div class="btn-group">
                                        <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Move" href="maintenance-detail-move.php?act=29dvi59&ntf=29dvi59-<?php echo $row2["aircraft_parts_id"]?>-94dfvj!sdf-349ffuaw">
                                            <i class="si si-shuffle"></i>
                                        </a>                                        
                                        <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit" href="maintenance-detail2.php?act=29dvi59&ntf=29dvi59-<?php echo $row2["aircraft_parts_id"]?>-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </div>
                                </td>-->
                            </tr>
                            <?php } mysqli_free_result($h2); ?>
                        </tbody>
                        <input type="submit" value="CREATE WO" class="btn btn-info mr-5 mb-5">
                    </table>
                    <!--due by hrs--> 
                    <a href="#" onclick="javascript:window.print();" class="btn btn-success mr-5 mb-5" /><i class="si si-printer"></i> Print</a> <a href="maintenance.php" class="btn btn-warning mr-5 mb-5">Reset</a> <a href="maintenance-pre-wo-add.php" class="btn btn-info mr-5 mb-5">Create WO</a>
                </form>           
            </div>
        </div>
        <!-- END Small Table -->
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
