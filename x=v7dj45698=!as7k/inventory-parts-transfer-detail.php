<?php 
require_once 'header.php';
//require_once 'components.php';
$sql  = "SELECT parts_name FROM tbl_parts WHERE client_id = '".$_SESSION['client_id']."' AND parts_id = '".$ntf[1]."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
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
                    <form action="inventory-parts-transfer.php" method="GET">
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
                    <form action="inventory-parts-transfer.php" method="GET">
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
        $sql = "SELECT a.parts_id,a.parts_name, sum(d.qty) as qty, a.parts_number, serial_number, parts_price, d.parts_rack_location_id FROM tbl_parts a INNER JOIN tbl_parts_rack_location b USING (parts_rack_location_id) INNER JOIN tbl_parts_warehouse c USING (parts_warehouse_id) INNER JOIN tbl_parts_location_stock d ON a.parts_id = d.parts_id  WHERE (parts_name LIKE '%$txt_search%' OR parts_number LIKE '%$txt_search%') AND a.client_id = '".$_SESSION['client_id']."' GROUP BY parts_id ORDER BY parts_name ASC LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='1'){
        $sql = "SELECT a.parts_id,a.parts_name, sum(d.qty) as qty, a.parts_number, serial_number, parts_price, d.parts_rack_location_id FROM tbl_parts a INNER JOIN tbl_parts_rack_location b USING (parts_rack_location_id) INNER JOIN tbl_parts_warehouse c USING (parts_warehouse_id) INNER JOIN tbl_parts_location_stock d ON a.parts_id = d.parts_id WHERE a.client_id = '".$_SESSION['client_id']."' GROUP BY parts_id ORDER by parts_name LIMIT $offset, $dataPerPage";      
      }else{
        $sql = "SELECT parts_warehouse_id, parts_warehouse_name FROM tbl_parts_warehouse a WHERE a.client_id = '".$_SESSION['client_id']."' ORDER BY parts_warehouse_name ASC";
      }
      $rs_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($rs_result)==0) {
      ?>
        <div class="alert alert-info ks-solid text-center" role="alert">Oops, no data found</div>
    <?php } ?>    
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Parts Stock Detail: <?php echo $row['parts_name'] ?></h3>
            </div>
            <div class="block-content">
                <table class="table table-sm table-vcenter">
                    <thead>
                        <tr>
                            <th width="50%">Warehouse Name</th>
                            <th>Rack <span class=pull-right>Qty</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                        <tr>
                            <td><?php echo $row['parts_warehouse_name'] ?></td>
                            <td>
                              <?php 
                              $sql1 = "SELECT parts_rack_location_id, parts_rack_location_name FROM tbl_parts_rack_location WHERE parts_warehouse_id = '".$row['parts_warehouse_id']."'";
                              $h1   = mysqli_query($conn, $sql1);
                              while($row1 = mysqli_fetch_assoc($h1)) {
                                $parts_rack_location_id = $row1['parts_rack_location_id'];
                                echo $row1['parts_rack_location_name'];

                                $sql2 = "SELECT qty FROM tbl_parts_location_stock WHERE parts_id = '".$ntf[1]."' AND parts_rack_location_id = '".$parts_rack_location_id."' LIMIT 1";
                                //echo $sql2.'<br/>';
                                $h2   = mysqli_query($conn, $sql2);
                                $row2 = mysqli_fetch_assoc($h2);
                                if($row2['qty']=='') { $qty = 0; }else{ $qty = '<b class=text-success>'.$row2['qty'].'</b>'  ; }
                                echo ' <span class=pull-right>('.$qty.' items)</span><br/>';
                              }
                              ?>
                            </td>                            
                        </tr>
                        <?php } mysqli_free_result($rs_result); ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
