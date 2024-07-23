<?php 
require_once 'header.php';
//require_once 'components.php';

//cari receipt no yang ada 
$sql = "SELECT aircraft_reg_code,afml_date,afml_route_from,afml_route_to FROM tbl_afml_detail_old";
$h   = mysqli_query($conn,$sql);
while($row = mysqli_fetch_assoc($h)) {
    $sql_check_invoice = "SELECT airnav_invoice_id,airnav_invoice_from,airnav_invoice_to,airnav_reg_code, airnav_invoice_date FROM tbl_airnav_invoice WHERE airnav_invoice_date = '".$row['afml_date']."' AND airnav_checked_status = 0"; //cari di table airnav_invoice yang checked = 0, cocokin FROM, TO, date sama reg_code\
    $h_check_invoice = mysqli_query($conn,$sql_check_invoice);
    $row_check_invoice = mysqli_fetch_assoc($h_check_invoice);
        
    if($row['afml_date'] == $row_check_invoice['airnav_invoice_date'] &&
        $row['afml_route_from'] == $row_check_invoice['airnav_invoice_from'] &&
        $row['afml_route_to'] == $row_check_invoice['airnav_invoice_to'] &&
        $row['aircraft_reg_code'] == $row_check_invoice['airnav_reg_code']) 
    {
        $sqlx = "UPDATE tbl_airnav_invoice SET airnav_checked_status = 1 WHERE airnav_invoice_id = '".$row_check_invoice['airnav_invoice_id']."'";
        mysqli_query($conn,$sqlx);
    }
    
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
                    <form action="airnav-check-invoice.php" method="post">
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
                    <form action="airnav-check-invoice.php" method="post">
                        <div class="form-group mb-15">
                            <div class="form-material floating">
                                <select class="form-control" id="material-select2" name="s">
                                    <option></option><!-- Empty value for demostrating material select box -->
                                    <option value="1">By From</option>
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

    <!-- Small Table -->
    <!--begin list data-->
    <?php
      @$page = @$_REQUEST['page'];
      $dataPerPage = 30;
      if(isset($_GET['page']))
      {
          $noPage = $_GET['page'];
      }
      else $noPage = 1;
      @$offset = ($noPage - 1) * $dataPerPage;
      //for total count data
      if(@$_REQUEST['s']=='1091vdf8ame151') {
        $txt_search   = input_data(filter_var($_REQUEST['txt_search'],FILTER_SANITIZE_STRING));
        $sql = "SELECT airnav_invoice_id,airnav_invoice_number,airnav_invoice_from,airnav_invoice_to,airnav_reg_code,airnav_time_in,airnav_time_out FROM tbl_airnav_invoice WHERE (airnav_invoice_from LIKE '%$txt_search%' OR airnav_invoice_from LIKE '%$txt_search%' OR airnav_invoice_number LIKE '%$txt_search%') AND airnav_checked_status = 0 AND client_id = '".$_SESSION['client_id']."' LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='1'){
        $sql = "SELECT airnav_invoice_id,airnav_invoice_number,airnav_invoice_from,airnav_invoice_to,airnav_reg_code,airnav_time_in,airnav_time_out FROM tbl_airnav_invoice WHERE client_id = '".$_SESSION['client_id']."' AND airnav_checked_status = 0 ORDER by airnav_invoice_from LIMIT $offset, $dataPerPage";      
      }else{
        $sql = "SELECT airnav_invoice_id,airnav_invoice_number,airnav_invoice_from,airnav_invoice_to,airnav_reg_code,airnav_time_in,airnav_time_out,date_format(airnav_invoice_date, '%d/%m/%Y') as airnav_invoice_date FROM tbl_airnav_invoice WHERE airnav_checked_status = 0 ORDER by airnav_invoice_date LIMIT $offset, $dataPerPage";
      }
      $h = mysqli_query($conn, $sql);
      if(mysqli_num_rows($h)==0) {
      ?>
        <div class="alert alert-info ks-solid text-center" role="alert">Oops, no data found</div>
    <?php } ?>    
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Unmatched Airnav Invoice <a class="btn btn-success mr-5 mb-5" href="airnav-check-invoice.php"><i class="si si-refresh mr-5"></i>Re-check</a></h3>
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
                            <th>Invoice No.</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Date</th>
                            <th>Reg Code</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($h)) { ?>
                        <tr>
                            <td><?php echo $row['airnav_invoice_number'] ?></td>
                            <td><?php echo $row['airnav_invoice_from'] ?></td>
                            <td><?php echo $row['airnav_invoice_to'] ?></td>
                            <td><?php echo $row['airnav_invoice_date'] ?></td>
                            <td><?php echo $row['airnav_reg_code'] ?></td>
                            <td><?php echo $row['airnav_time_in'] ?></td>
                            <td><?php echo $row['airnav_time_out'] ?></td>
                            <td><a href="airnav-waiver-edit.php?iekmfdak&ntf=<?php echo $row['airnav_terminal_id'] ?>-inv-<?php echo $row['airnav_invoice_number'] ?>" onclick="return confirm('Are you sure data is correct?');">waiver</a></td>
                        </tr>
                        <?php } mysqli_free_result($h); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Small Table -->

        <?php
        // COUNT TOTAL NUMBER OF ROWS IN TABLE
        if(@$_REQUEST['s']=='1091vdf8ame151') {           
          $sql = "SELECT count(airnav_invoice_id) as jumData FROM tbl_airnav_invoice WHERE client_id = '".$_SESSION['client_id']."' AND (airnav_invoice_from LIKE '%$txt_search%' OR airnav_invoice_from LIKE '%$txt_search%' OR airnav_invoice_number LIKE '%$txt_search%') AND airnav_checked_status = 0";
        }elseif(@$_REQUEST['s']=='3') {           
          $sql = "SELECT count(airnav_invoice_id) as jumData FROM tbl_airnav_invoice WHERE client_id = '".$_SESSION['client_id']."' AND airnav_checked_status = 0 ORDER by avtur_price_from";
        }else{            
          $sql = "SELECT count(airnav_invoice_id) as jumData FROM tbl_airnav_invoice WHERE airnav_checked_status = 0 AND client_id = '".$_SESSION['client_id']."'";
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
