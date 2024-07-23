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
                    <form action="user-engineer.php" method="post">
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
                    <form action="user-engineer.php" method="post">
                        <div class="form-group mb-15">
                            <div class="form-material floating">
                                <select class="form-control" id="material-select2" name="s">
                                    <option></option><!-- Empty value for demostrating material select box -->
                                    <option value="1">By Name A-Z</option>
                                    <option value="3">By Active Status</option>
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
        $sql = "SELECT user_id,username,user_position,full_name,user_active_status, department_id,department_name FROM tbl_user a INNER JOIN tbl_department b USING (department_id) WHERE (username LIKE '%$txt_search%' OR full_name LIKE '%$txt_search%') AND a.client_id = '".$_SESSION['client_id']."' AND department_id = 1 LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='1'){
        $sql = "SELECT user_id,username,user_position,full_name,user_active_status, department_id,department_name FROM tbl_user a INNER JOIN tbl_department b USING (department_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND department_id = 1 ORDER by full_name LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='2'){
        $sql = "SELECT user_id,username,user_position,full_name,user_active_status, department_id,department_name FROM tbl_user a INNER JOIN tbl_department b USING (department_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND department_id = 1 ORDER by department_id LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='3'){
        $sql = "SELECT user_id,username,user_position,full_name,user_active_status, department_id,department_name FROM tbl_user a INNER JOIN tbl_department b USING (department_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND department_id = 1 ORDER by user_active_status LIMIT $offset, $dataPerPage";      
      }else{
        $sql = "SELECT user_id,username,user_position,full_name,user_active_status, department_id,department_name FROM tbl_user a INNER JOIN tbl_department b USING (department_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND department_id = 1 LIMIT $offset, $dataPerPage";
      }
      $rs_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($rs_result)==0) {
      ?>
        <div class="alert alert-info ks-solid text-center" role="alert">Oops, no data found</div>
    <?php } ?>    
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Engineer Team</h3>
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
                            <th>Name</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Active?</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                        <tr>
                            <td><?php echo $row['full_name'] ?></td>
                            <td><?php echo $row['department_name'] ?></td>
                            <td><?php echo $row['user_position'] ?></td>
                            <td>
                                <?php if($row['user_active_status']==1) { ?>
                                    <span class="badge badge-success">active</span>
                                <?php }else{ ?>
                                    <span class="badge badge-danger">inactive</span>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View Certificate" href="user-engineer-certificate.php?act=29dvi59&ntf=29dvi59-<?php echo $row["user_id"]?>-94dfvj!sdf-349ffuaw">
                                        Certificate
                                    </a>
                                    <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View OTR" href="user-engineer-otr.php?act=29dvi59&ntf=29dvi59-<?php echo $row["user_id"]?>-94dfvj!sdf-349ffuaw">
                                        OTR
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
          $sql = "SELECT count(user_id) as jumData FROM tbl_user a INNER JOIN tbl_department b USING (department_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND (username LIKE '%$txt_search%' OR full_name LIKE '%$txt_search%') AND department_id = 1";
        }elseif(@$_REQUEST['s']=='1') {           
          $sql = "SELECT count(user_id) as jumData FROM tbl_user a INNER JOIN tbl_department b USING (department_id) WHERE b.client_id = '".$_SESSION['client_id']."' AND department_id = 1 ORDER by full_name";
        }elseif(@$_REQUEST['s']=='2') {           
          $sql = "SELECT count(user_id) as jumData FROM tbl_user a INNER JOIN tbl_department b USING (department_id) WHERE b.client_id = '".$_SESSION['client_id']."' AND department_id = 1 ORDER by department_id";
        }elseif(@$_REQUEST['s']=='3') {           
          $sql = "SELECT count(user_id) as jumData FROM tbl_user a INNER JOIN tbl_department b USING (department_id) WHERE b.client_id = '".$_SESSION['client_id']."' AND department_id = 1 ORDER by user_active_status";
        }else{            
          $sql = "SELECT count(user_id) as jumData FROM tbl_user a INNER JOIN tbl_department b USING (department_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND department_id = 1";
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
