<?php 
require_once 'header.php';
//require_once 'components.php';

//cari receipt no yang ada 
$sql = "SELECT do_number FROM tbl_avtur_pertamina LEFT JOIN tbl_avtur_afml ON tbl_avtur_afml.afml_receipt_no = tbl_avtur_pertamina.do_number WHERE afml_receipt_no <> ' AND tbl_avtur_pertamina.checked_status = 0'";
$h   = mysqli_query($conn,$sql);
while($row = mysqli_fetch_assoc($h)) { 
    //update table yang receipt number udah matched
    $sqlx = "UPDATE tbl_avtur_afml SET checked_status = 1 WHERE afml_receipt_no = '".$row['do_number']."'";
    $sqly = "UPDATE tbl_avtur_pertamina SET checked_status = 1 WHERE do_number = '".$row['do_number']."'";
    //echo $sqlx.'<br/>';
    //echo $sqly.'<br/>';
}

$sql = "SELECT avtur_pertamina_date,do_number,a.aircraft_reg_code FROM tbl_avtur_pertamina a LEFT OUTER JOIN tbl_avtur_afml b ON (a.do_number = b.afml_receipt_no) where b.afml_receipt_no IS NULL AND a.client_id = '".$_SESSION['client_id']."'";
//echo $sql;
$h   = mysqli_query($conn,$sql);
    //while($row = mysqli_fetch_assoc($h)) { 
    //$sql3 = "SELECT * FROM tbl_avtur_afml WHERE afml_fuel_date = '".$row['avtur_pertamina_date']."'";
    //$h3 = mysqli_query($conn,$sql3);
    //$row3 = mysqli_fetch_assoc($h3);
    //echo 'DO NUMBER: '.$row['do_number'].' | date | '.$row['avtur_pertamina_date'].' | Engineer :'.$row3['full_name'].'<br/>';
    //}  
//exit();
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
                    <form action="avtur.php" method="post">
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
                    <form action="avtur.php" method="post">
                        <div class="form-group mb-15">
                            <div class="form-material floating">
                                <select class="form-control" id="material-select2" name="s">
                                    <option></option><!-- Empty value for demostrating material select box -->
                                    <option value="k">By Date</option>
                                    <option value="1">By Price</option>
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
        $sql = "SELECT *,date_format(avtur_price_from, '%d/%m/%Y') as avtur_price_from,date_format(avtur_price_to, '%d/%m/%Y') as avtur_price_to FROM tbl_avtur_price WHERE (avtur_price_from LIKE '%$txt_search%' OR avtur_price_to LIKE '%$txt_search%' OR iata_code LIKE '%$txt_search%' OR avtur_price LIKE '%$txt_search%') AND client_id = '".$_SESSION['client_id']."' LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='1'){
        $sql = "SELECT *,date_format(avtur_price_from, '%d/%m/%Y') as avtur_price_from,date_format(avtur_price_to, '%d/%m/%Y') as avtur_price_to FROM tbl_avtur_price WHERE client_id = '".$_SESSION['client_id']."' ORDER by avtur_price_from LIMIT $offset, $dataPerPage";      
      }elseif (@$_REQUEST['s']=='k'){
        $avtur_price_from   = input_data(filter_var($_REQUEST['avtur_price_from'],FILTER_SANITIZE_STRING));
        $avtur_price_to   = input_data(filter_var($_REQUEST['avtur_price_to'],FILTER_SANITIZE_STRING));
        $avtur_price_from_y   = substr($avtur_price_from,6,4);
        $avtur_price_from_m   = substr($avtur_price_from,3,2);
        $avtur_price_from_d   = substr($avtur_price_from,0,2);
        $avtur_price_from_f   = $avtur_price_from_y.'-'.$avtur_price_from_m.'-'.$avtur_price_from_d;

        $avtur_price_to_y   = substr($avtur_price_to,6,4);
        $avtur_price_to_m   = substr($avtur_price_to,3,2);
        $avtur_price_to_d   = substr($avtur_price_to,0,2);
        $avtur_price_to_f   = $avtur_price_to_y.'-'.$avtur_price_to_m.'-'.$avtur_price_to_d;            
        $sql = "SELECT *,date_format(avtur_price_from, '%d/%m/%Y') as avtur_price_from,date_format(avtur_price_to, '%d/%m/%Y') as avtur_price_to FROM tbl_avtur_price WHERE client_id = '".$_SESSION['client_id']."' AND (avtur_price_from >= '".$avtur_price_from_f."' OR avtur_price_to <= '".$avtur_price_to_f."') ORDER by avtur_price_from LIMIT $offset, $dataPerPage";
      }else{

      }
      //$rs_result = mysqli_query($conn, $sql);
      //if(mysqli_num_rows($rs_result)==0) {
      ?>
        <div class="alert alert-info ks-solid text-center" role="alert">Oops, no data found</div>
    <?php //} ?>    
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Avtur <a class="btn btn-success mr-5 mb-5" href="avtur-pertamina.php"><i class="si si-refresh mr-5"></i>Re-check</a></h3>
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
                            <th>DO Number</th>
                            <th>Date</th>
                            <th>Reg Code</th>
                            <th style="text-align: right;">Engineer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while ($row = mysqli_fetch_assoc($h)) { 
                            $sql3 = "SELECT * FROM tbl_avtur_afml WHERE afml_fuel_date = '".$row['avtur_pertamina_date']."' AND aircraft_reg_code = '".$row['aircraft_reg_code']."'";
                            //echo $sql3;
                            $h3 = mysqli_query($conn,$sql3);
                            $row3 = mysqli_fetch_assoc($h3);
                        ?>
                        <tr>
                            <td><?php echo $row['do_number'] ?></td>
                            <td><?php echo $row['avtur_pertamina_date'] ?></td>
                            <td><?php echo $row['aircraft_reg_code'] ?></td>
                            <td><?php echo $row3['full_name'] ?></td>
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
          $sql = "SELECT count(avtur_price_id) as jumData FROM tbl_avtur_price WHERE client_id = '".$_SESSION['client_id']."' AND (avtur_price_from LIKE '%$txt_search%' OR avtur_price_to LIKE '%$txt_search%' OR iata_code LIKE '%$txt_search%' OR avtur_price LIKE '%$txt_search%')";
        }elseif(@$_REQUEST['s']=='3') {           
          $sql = "SELECT count(avtur_price_id) as jumData FROM tbl_avtur_price WHERE client_id = '".$_SESSION['client_id']."' ORDER by avtur_price_from";
        }elseif(@$_REQUEST['s']=='k') {           
          $sql = "SELECT count(avtur_price_id) as jumData FROM tbl_avtur_price WHERE client_id = '".$_SESSION['client_id']."' AND (avtur_price_from >= '".$avtur_price_from_f."' OR avtur_price_to <= '".$avtur_price_to_f."') ORDER by avtur_price_from";
        }else{            
          $sql = "SELECT count(avtur_price_id) as jumData FROM tbl_avtur_price WHERE client_id = '".$_SESSION['client_id']."'";
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
