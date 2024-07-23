<?php 
require_once 'header.php';
//require_once 'components.php';
$sql  = "SELECT km_name,km_files_tag,date_format(created_date, '%d/%m/%Y') as created_date FROM tbl_km_content a INNER JOIN tbl_km_files_department b USING (km_content_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND km_department_id = '".$_SESSION['department_id']."' AND km_content_id = '".$ntf[1]."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
?>

<link rel="stylesheet" href="../assets/js/plugins/select2/select2.min.css">
<link rel="stylesheet" href="../assets/js/plugins/select2/select2-bootstrap.min.css">


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
                    <form action="files.php" method="post">
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
                    <form action="files.php" method="post">
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
        <!-- Page Content -->
        <div class="content">
            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <!-- Tasks List -->
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Available File for "<?php echo $row['km_name'] ?>"</h3>
                        <div class="block-options">
                            <div class="block-options-item">
                                <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                                    <i class="fa fa-filter"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!--begin list data-->
                    <?php
                      function convertToReadableSize($size){
                        $base = log($size) / log(1024);
                        $suffix = array("", "KB", "MB", "GB", "TB");
                        $f_base = floor($base);
                        return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
                    }

                    $sql = "SELECT km_files_id,km_file_name,km_file_size,date_format(created_date, '%d/%m/%Y') as created_date FROM tbl_km_files a INNER JOIN tbl_km_files_department b USING (km_content_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND km_department_id = '".$_SESSION['department_id']."' AND km_content_id = '".$ntf[1]."' ORDER BY km_file_name";
                    $rs_result = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($rs_result)==0) {
                    ?>
                    <p class="text-center text-danger" role="alert">Oops, no file for this Contents</p>
                      <?php } $row2 = mysqli_fetch_assoc($rs_result); ?>                                        
                    <div class="js-task-list">
                        <!-- Task -->
                        <div class="js-task block block-rounded mb-5 animated fadeIn" data-task-id="9" data-task-completed="false" data-task-starred="false">
                            <table class="table table-borderless table-vcenter mb-0">
                                <tr>
                                    <td><?php echo nl2br($row['km_files_tag']) ?></td>
                                </tr>
                            </table>
                        </div>

                        <div class="js-task block block-rounded mb-5 animated fadeIn" data-task-id="9" data-task-completed="false" data-task-starred="false">

                            <table class="table table-borderless table-vcenter mb-0">
                                <tr>
                                    <td class="js-task-content font-w600">
                                        <?php echo '<b>'.$row2['km_file_name'].'</b><br/><i> Uploaded on '.$row2['created_date'].'</i><br/>';
                                        echo 'size: '.convertToReadableSize($row2['km_file_size']); ?>
                                    </td>
                                    <td class="text-right" style="width: 100px;">
                                        <!--<a class="js-task-star btn btn-sm btn-alt-warning" href="files-download-history.php?act=29dvi59&ntf=29dvi59-<?php echo $row2["km_files_id"]?>-94dfvj!sdf-349ffuaw" ><i class="si si-eyeglasses"></i></a>--> <a class="js-task-star btn btn-sm btn-alt-danger" href="files-download.php?act=29dvi59&ntf=29dvi59-<?php echo $row2["km_files_id"]?>-94dfvj!sdf-349ffuaw" ><i class="fa fa-download"></i></a>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- END Task -->
                    </div>
                    <!-- END Tasks List -->
                </div>
            </div>
        </div>
        <!-- END Page Content -->
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>