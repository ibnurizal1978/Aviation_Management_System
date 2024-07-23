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
                    <form action="hazard.php" method="post">
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
                    <form action="hazard.php" method="post">
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
        $sql = "SELECT afml_page_no,hazard_id,hazard_reg_no,hazard_description,hazard_location,date_format(hazard_date, '%d/%m/%Y') AS hazard_date,hazard_probability,hazard_severity,hazard_risk_level,hazard_recommendation,hazard_status,hazard_action FROM tbl_hazard WHERE (hazard_description LIKE '%$txt_search%' OR hazard_location LIKE '%$txt_search%' OR hazard_date LIKE '%$txt_search%' OR hazard_risk_level LIKE '%$txt_search%' OR hazard_recommendation LIKE '%$txt_search%') AND client_id = '".$_SESSION['client_id']."' LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='1'){
        $sql = "SELECT afml_page_no,hazard_id,hazard_reg_no,hazard_description,hazard_location,date_format(hazard_date, '%d/%m/%Y') AS hazard_date,hazard_probability,hazard_severity,hazard_risk_level,hazard_recommendation,hazard_status,hazard_action FROM tbl_hazard WHERE client_id = '".$_SESSION['client_id']."' ORDER by hazard_description LIMIT $offset, $dataPerPage";      
      }else{
        $sql = "SELECT afml_page_no,hazard_id,hazard_reg_no,hazard_description,hazard_location,date_format(hazard_date, '%d/%m/%Y') AS hazard_date,hazard_probability,hazard_severity,hazard_risk_level,hazard_recommendation,hazard_status,hazard_action FROM tbl_hazard WHERE client_id = '".$_SESSION['client_id']."' ORDER BY hazard_created_date DESC LIMIT $offset, $dataPerPage";
      }
      $rs_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($rs_result)==0) {
        $i=1;
      ?>
        <div class="alert alert-info ks-solid text-center" role="alert">Oops, no data found</div>
    <?php } ?>    
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <table class="table-vcenter" width="100%" border="0">
                    <tr>
                        <td width="10%"><img src="../assets/img/logo.jpg" width="100"></td>
                        <td class="text-center"><h3 class="block-title">HAZARD REGISTER</h3>
                            <b>PT. SMART CAKRAWALA AVIATION</b></td>
                    </tr>
                </table>
                <div class="block-options">
                    <div class="block-options-item">
                        <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                            <i class="fa fa-filter"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="block-content">
                <table class="table-sm table-vcenter table-bordered">
                    <thead>
                        <tr class="text-center" style="background: #21B345; color: #fff; font-weight: lowercase">
                            <th>No</th>
                            <th>AFML Page No.</th>
                            <th>Created On</th>                            
                            <th>Register<br/> No</th>
                            <th>Description of <br/>Hazard</th>
                            <th>Loc</th>
                            <th>Prob</th>
                            <th>Severity</th>
                            <th>Risk Level</th>
                            <th>Recommendation</th>
                            <th>Mitigation</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                        <tr>
                            <td align="center"><?php echo ++$i//$row['safety_finding_no'] ?></td>
                            <td class="text-center"><?php echo $row['afml_page_no'] ?></td>
                            <td class="text-center"><?php echo $row['hazard_date'] ?></td>
                            <td class="text-center"><?php echo $row['hazard_reg_no'] ?></td>
                            <td><?php echo $row['hazard_description'] ?></td>
                            <td class="text-center"><?php echo $row['hazard_location'] ?></td>
                            <td class="text-center"><?php echo $row['hazard_probability'] ?></td>
                            <td class="text-center"><?php echo $row['hazard_severity'] ?></td>
                            <?php
                            $merah = array('3A','4A','4B','5A','5B','5C');
                            $orange= array('2A','2B','2C','3B','3C','3D','4C','4D','4E','5D','5E');
                            $hijau = array('1A','1B','1C','1D','1E','2D','2E','3E');
                            if(in_array($row['hazard_risk_level'],$merah)) {
                                $warna = '#ff0000';
                            }elseif(in_array($row['hazard_risk_level'],$orange)) {
                                $warna = '#ff6600';
                            }elseif(in_array($row['hazard_risk_level'],$hijau)) {
                                $warna = '#7FED9A';                 
                            }else{
                                $warna = '#a5a5a5';
                            }
                            ?>
                            <td class="text-center" style="background: <?php echo $warna ?>"><?php echo $row['hazard_risk_level'] ?></td>
                            <td><?php echo $row['hazard_recommendation'] ?></td>
                            <td><?php echo $row['hazard_action'] ?></td>
                            <td class="text-center"><?php echo $row['hazard_status'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View" href="safety-finding-detail.php?act=29dvi59&ntf=29dvi59-<?php echo $row["hazard_id"]?>-94dfvj!sdf-349ffuaw">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                            </td>
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
          $sql = "SELECT count(hazard_id) as jumData FROM tbl_hazard WHERE client_id = '".$_SESSION['client_id']."' AND (hazard_description LIKE '%$txt_search%' OR hazard_location LIKE '%$txt_search%' OR hazard_date LIKE '%$txt_search%' OR hazard_risk_level LIKE '%$txt_search%' OR hazard_recommendation LIKE '%$txt_search%')";
        }elseif(@$_REQUEST['s']=='1') {           
          $sql = "SELECT count(hazard_id) as jumData FROM tbl_hazard WHERE client_id = '".$_SESSION['client_id']."'";
        }else{            
          $sql = "SELECT count(hazard_id) as jumData FROM tbl_hazard WHERE client_id = '".$_SESSION['client_id']."'";
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
