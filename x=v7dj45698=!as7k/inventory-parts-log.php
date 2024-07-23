<?php 
require_once 'header.php';
//require_once 'components.php';
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
                    <form action="inventory-parts-log.php" method="post">
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
                    <form action="inventory-parts-log.php" method="post">
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

    <!-- Small Table -->
    <!--begin list data-->
    <?php
    $parts_id = $ntf[1];
      @$page = @$_REQUEST['page'];
      $dataPerPage = 10;
      if(isset($_GET['page']))
      {
          $noPage = $_GET['page'];
      }
      else $noPage = 1;
      @$offset = ($noPage - 1) * $dataPerPage;
      //for total count data
      if(@$_REQUEST['s']=='1091vdf8ame151') {
        $txt_search   = input_data(filter_var($_REQUEST['txt_search'],FILTER_SANITIZE_STRING));
        $sql = "SELECT parts_name,parts_stock_transaction_type,parts_stock_amount,full_name,parts_stock_created_date as parts_stock_created_date2, DATE_FORMAT(DATE_ADD(parts_stock_created_date,INTERVAL '".$_SESSION['selisih']."' HOUR), '%d/%m/%Y on %H:%i:%s') as parts_stock_created_date FROM tbl_parts_stock a INNER JOIN tbl_parts b USING (parts_id) INNER JOIN tbl_user c USING (user_id) WHERE (parts_name LIKE '%$txt_search%') AND a.client_id = '".$_SESSION['client_id']."' AND a.parts_id = '".$parts_id."' LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='1'){
        $sql = "SELECT parts_name,parts_stock_transaction_type,parts_stock_amount,parts_stock_created_by_fullname,parts_stock_created_date as parts_stock_created_date2, DATE_FORMAT(DATE_ADD(parts_stock_created_date,INTERVAL '".$_SESSION['selisih']."' HOUR), '%d/%m/%Y on %H:%i:%s') as parts_stock_created_date FROM tbl_parts_stock a INNER JOIN tbl_parts b USING (parts_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND a.parts_id = '".$parts_id."' ORDER by parts_name LIMIT $offset, $dataPerPage";      
      }else{
        $sql = "SELECT parts_name,parts_stock_transaction_type,parts_stock_amount,full_name,parts_stock_created_date as parts_stock_created_date2, DATE_FORMAT(DATE_ADD(parts_stock_created_date,INTERVAL '".$_SESSION['selisih']."' HOUR), '%d/%m/%Y on %H:%i:%s') as parts_stock_created_date FROM tbl_parts_stock a INNER JOIN tbl_parts b USING (parts_id) INNER JOIN tbl_user c USING (user_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND a.parts_id = '".$parts_id."' LIMIT $offset, $dataPerPage";
      }
      $rs_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($rs_result)==0) {
      ?>
        <div class="alert alert-info ks-solid text-center" role="alert">Oops, no data found</div>
    <?php } ?>    
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Parts Movement History</h3>
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
                            <th>Parts Name</th>
                            <th>Action</th>
                            <th>Quantity</th>
                            <th>Action Date</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                        <tr>
                            <td><?php echo $row['parts_name'] ?></td>
                            <td><?php echo $row['parts_stock_transaction_type'] ?></td>
                            <td><?php echo $row['parts_stock_amount'] ?></td>
                            <td><?php echo $row['parts_stock_created_date']. ' (GMT '.$_SESSION['selisih'].')'; ?></td>
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
          $sql = "SELECT count(parts_rack_location_id) as jumData FROM tbl_parts_rack_location a INNER JOIN tbl_parts_warehouse b USING (parts_warehouse_id)  WHERE a.client_id = '".$_SESSION['client_id']."' AND (parts_rack_location_name LIKE '%$txt_search%')";
        }elseif(@$_REQUEST['s']=='1') {           
          $sql = "SELECT count(parts_rack_location_id) as jumData FROM tbl_parts_rack_location a INNER JOIN tbl_parts_warehouse b USING (parts_warehouse_id) WHERE a.client_id = '".$_SESSION['client_id']."' ORDER by parts_rack_location_name";
        }else{            
          $sql = "SELECT count(parts_rack_location_id) as jumData FROM tbl_parts_rack_location a INNER JOIN tbl_parts_warehouse b USING (parts_warehouse_id) WHERE a.client_id = '".$_SESSION['client_id']."'";
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
