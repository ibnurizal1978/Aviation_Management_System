<?php 
require_once 'header.php';
//require_once 'components.php';
?>

<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
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
                    <form action="tools.php" method="post">
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
                    <form action="tools.php" method="post">
                        <div class="form-group mb-15">
                            <div class="form-material floating">
                                <select class="form-control" id="material-select2" name="s">
                                    <option></option><!-- Empty value for demostrating material select box -->
                                    <option value="1">By Name A-Z</option>
                                    <option value="2">By Type</option>
                                    <option value="3">By Calibration Date</option>
                                    <option value="4">By Upcoming Expiry Date</option>
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

            <!-- Print Tools Calibration -->
            <div class="block pull-r-l">
                <div class="block-header bg-body-light">
                    <h3 class="block-title">
                        <i class="fa fa-fw fa-pencil font-size-default mr-5"></i>Print - Calibration Tools
                    </h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                    </div>
                </div>
                <div class="block-content">
                    <form action="tools-print-calibration.php" method="post">
                        <div class="form-group">
                            <div class="">
                                <label class="bmd-label-floating">Print Date (dd/mm/yyyy)</label>
                                <input type="text" class="form-control" name="print_date" id="print_date" autocomplete="off" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-block btn-alt-primary">
                                    <i class="fa fa-file-o mr-5"></i> Print
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>            
            <!-- END Print Tools Calibration -->

            <!-- Print Tools General -->
            <div class="block pull-r-l">
                <div class="block-header bg-body-light">
                    <h3 class="block-title">
                        <i class="fa fa-fw fa-pencil font-size-default mr-5"></i>Print - General Tools
                    </h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                    </div>
                </div>
                <div class="block-content">
                    <form action="tools-print-general.php" method="post">
                        <div class="form-group mb-15">
                            <div class="form-material floating">
                                <select class="form-control" id="material-select2" name="base_type">
                                    <option></option><!-- Empty value for demostrating material select box -->
                                    <option value="Base">On Base</option>
                                    <option value="Aircraft">On Aircraft</option>
                                </select>
                                <label for="material-select2">Choose Base</label>
                            </div>
                        </div>                        
                        <div class="form-group mb-15">
                            <div class="form-material floating">
                                <select class="form-control" id="material-select2" name="parts_location_id">
                                    <?php
                                    $sql  = "SELECT * FROM tbl_parts_location where client_id = '".$_SESSION['client_id']."' ORDER BY parts_location_name";
                                    $h    = mysqli_query($conn,$sql);
                                    while($row1 = mysqli_fetch_assoc($h)) {
                                    ?>
                                     <option value="<?php echo $row1['parts_location_id'] ?>"><?php echo $row1['parts_location_name'] ?></option> 
                                    <?php } ?>
                                </select>
                                <label for="material-select2">Choose Location</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="">
                                <label class="bmd-label-floating">Control Date (dd/mm/yyyy)</label>
                                <input type="text" class="form-control" name="datex" id="datex" autocomplete="off" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-block btn-alt-primary">
                                    <i class="fa fa-file-o mr-5"></i> Print
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>            
            <!-- END Print Tools General -->

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

    <!-- Small Table -->
    <!--begin list data-->
    <?php
    $sql = "SELECT *,date_format(created_date, '%d/%m/%Y') as created_date FROM tbl_aircraft_parts_history WHERE client_id = '".$_SESSION['client_id']."' ORDER BY aircraft_parts_history_id DESC";
    $rs_result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($rs_result)==0) {
      ?>
        <div class="alert alert-info ks-solid text-center" role="alert">Oops, no data found</div>
    <?php } ?>    
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Log - Aircraft Parts</h3>
                <div class="block-options">
                    <div class="block-options-item">
                        <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                            <i class="fa fa-filter"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="block-content">
                <table class="table table-sm table-vcenter">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Serial Number</th>
                            <th>Part Number</th>
                            <th>Location</th>
                            <th>Created On</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                        <tr>
                            <td><?php echo $row['history_type'] ?></td>
                            <td><?php echo $row['description'] ?></td>
                            <td><?php echo $row['prev_serial_number'] ?></td>
                            <td><?php echo $row['prev_part_number'] ?></td>
                            <td><?php echo 'Old: '.$row['prev_location'].'<br/>New: '.$row['new_location'] ?></td>
                            <td><?php echo $row['created_date'] ?></td>
                            <td><?php echo $row['full_name'] ?></td>
                        </tr>
                        <?php } mysqli_free_result($rs_result); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Small Table -->

        <?php
        // COUNT TOTAL NUMBER OF ROWS IN TABLE
        if(@$_REQUEST['s']=='1091vdf8ame151') {           
          $sql = "SELECT count(tools_id) as jumData FROM tbl_tools a INNER JOIN tbl_parts_location b USING (parts_location_id) WHERE (tools_name LIKE '%$txt_search%' OR tools_description LIKE '%$txt_search%' OR parts_location_name LIKE '%$txt_search%' OR part_number LIKE '%$txt_search%' OR serial_number LIKE '%$txt_search%') AND a.client_id = '".$_SESSION['client_id']."'";
        }elseif(@$_REQUEST['s']=='1') {           
          $sql = "SELECT count(tools_id) as jumData FROM tbl_tools a INNER JOIN tbl_parts_location b USING (parts_location_id) WHERE a.client_id = '".$_SESSION['client_id']."' ORDER by tools_name";
        }elseif(@$_REQUEST['s']=='2') {           
          $sql = "SELECT count(tools_id) as jumData FROM tbl_tools a INNER JOIN tbl_parts_location b USING (parts_location_id) WHERE a.client_id = '".$_SESSION['client_id']."' ORDER by tools_type";
        }elseif(@$_REQUEST['s']=='3') {           
          $sql = "SELECT count(tools_id) as jumData FROM tbl_tools a INNER JOIN tbl_parts_location b USING (parts_location_id) WHERE a.client_id = '".$_SESSION['client_id']."' ORDER by date(last_calibration_date) DESC";
        }elseif(@$_REQUEST['s']=='3') {           
          $sql = "SELECT count(tools_id) as jumData FROM tbl_tools a INNER JOIN tbl_parts_location b USING (parts_location_id) WHERE a.client_id = '".$_SESSION['client_id']."' ORDER by date(next_calibration_date) DESC";              
        }else{            
          $sql = "SELECT count(tools_id) as jumData FROM tbl_tools a INNER JOIN tbl_parts_location b USING (parts_location_id) WHERE a.client_id = '".$_SESSION['client_id']."'";
        }          

        $hasil  = mysqli_query($conn,$sql);
        $data     = mysqli_fetch_assoc($hasil);
        $jumData = $data['jumData'];
        $jumPage = ceil($jumData/$dataPerPage);

        if ($noPage > 1) echo  "<a class=hal href='".$_SERVER['PHP_SELF']."?s=".@$_REQUEST['s']."&client_id=".@$_REQUEST['client_id']."&txt_search=".@$_REQUEST['txt_search']."&page=".($noPage-1)."'><i class=\"fa fa-angle-left\"></i></a>";

        for($page = 1; $page <= $jumPage; $page++)
        {
            if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage))
            {
                if ((@$showPage == 1) && ($page != 2))  echo "...";
                if ((@$showPage != ($jumPage - 1)) && ($page == $jumPage))  echo "...";
                if ($page == $noPage) echo " <b>".$page."</b> ";
                else echo " <a class=hal href='".$_SERVER['PHP_SELF']."?s=".@$_REQUEST['s']."&client_id=".@$_REQUEST['client_id']."&txt_search=".@$_REQUEST['txt_search']."&page=".$page."'>".$page."</a> ";
                @$showPage = $page;
            }
        }

        if ($noPage < $jumPage) echo "<a class=hal href='".$_SERVER['PHP_SELF']."?s=".@$_REQUEST['s']."&client_id=".@$_REQUEST['client_id']."&txt_search=".@$_REQUEST['txt_search']."&page=".($noPage+1)."'><i class=\"fa fa-angle-right\"></i></a>";
        mysqli_free_result($hasil); 
        ?>

    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
<script>  
$(document).ready(function(){
//date format
  $("#print_date").attr("maxlength", 10);
  $("#print_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });

  $("#datex").attr("maxlength", 10);
  $("#datex").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });  

});
</script>