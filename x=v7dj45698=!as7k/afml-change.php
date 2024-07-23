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
                    <form action="afml-change.php" method="GET">
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
                    <form action="afml-change.php" method="GET">
                        <div class="form-group mb-15">
                            <div class="form-material floating">
                                <select class="form-control" id="material-select2" name="s">
                                    <option></option><!-- Empty value for demostrating material select box -->
                                    <option value="1">By Creator</option>
                                    <option value="2">By Date</option>
                                    <option value="3">By AFML No.</option>
                                    <option value="4">By Reg Code</option>
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

    <?php if(@$ntf[0]==1) { ?>
      <div class="alert alert-danger alert-dismissable" role="alert">
          <p class="mb-0">Please fill form</p>
      </div>
    <?php } ?> 

    <?php if(@$ntf[0]==2) { ?>
      <div class="alert alert-danger alert-dismissable" role="alert">
          <p class="mb-0">Duplicate AFML Page Number</p>
      </div>
    <?php } ?>         

    <?php if(@$ntf[0]==999) { ?>
      <div class="alert alert-success alert-dismissable" role="alert">
          <p class="mb-0">Yeayy. Success!</p>
      </div>
    <?php } ?>

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
        $sql = "SELECT afml_id,a.afml_page_no, b.full_name as created_by, a.aircraft_reg_code,afml_file,date_format(a.afml_date, '%d/%m/%Y') as afml_date, afml_engineer_preflight_user_id, ectm_time,ectm_altitude,ectm_ias,ectm_tq,ectm_itt,ectm_ng,ectm_np, ectm_ff,ectm_oil_temp,ectm_oil_press,ectm_oat,ectm_oat FROM tbl_afml a INNER JOIN tbl_user b using (user_id) WHERE a.aircraft_reg_code <> 'INITIAL' AND (a.afml_page_no LIKE '%$txt_search%' OR a.aircraft_reg_code LIKE '%$txt_search%' OR aircraft_serial_number LIKE '%$txt_search%' OR full_name LIKE '%$txt_search%') AND a.client_id = '".$_SESSION['client_id']."' GROUP BY a.afml_page_no ORDER BY a.afml_date DESC LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='1'){
        $sql = "SELECT afml_id,a.afml_page_no, b.full_name as created_by, a.aircraft_reg_code,afml_file,date_format(a.afml_date, '%d/%m/%Y') as afml_date, afml_engineer_preflight_user_id, ectm_time,ectm_altitude,ectm_ias,ectm_tq,ectm_itt,ectm_ng,ectm_np, ectm_ff,ectm_oil_temp,ectm_oil_press,ectm_oat,ectm_oat FROM tbl_afml a INNER JOIN tbl_user b ON a.user_id = b.user_id  WHERE a.aircraft_reg_code <> 'INITIAL' AND a.client_id = '".$_SESSION['client_id']."' GROUP BY a.afml_page_no ORDER BY afml_date LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='2'){
        $sql = "SELECT afml_id,a.afml_page_no, b.full_name as created_by, a.aircraft_reg_code,afml_file,date_format(a.afml_date, '%d/%m/%Y') as afml_date, afml_engineer_preflight_user_id,afml_captain_user_id,afml_copilot_user_id,afml_engineer_on_board_user_id, ectm_time,ectm_altitude,ectm_ias,ectm_tq,ectm_itt,ectm_ng,ectm_np, ectm_ff,ectm_oil_temp,ectm_oil_press,ectm_oat,ectm_oat FROM tbl_afml a INNER JOIN tbl_user b ON a.user_id = b.user_id  WHERE a.aircraft_reg_code <> 'INITIAL' AND a.client_id = '".$_SESSION['client_id']."' GROUP BY a.afml_page_no ORDER BY a.afml_date LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='3'){
        $sql = "SELECT afml_id,a.afml_page_no, b.full_name as created_by, a.aircraft_reg_code,afml_file,date_format(a.afml_date, '%d/%m/%Y') as afml_date, afml_engineer_preflight_user_id,afml_captain_user_id,afml_copilot_user_id,afml_engineer_on_board_user_id, ectm_time,ectm_altitude,ectm_ias,ectm_tq,ectm_itt,ectm_ng,ectm_np, ectm_ff,ectm_oil_temp,ectm_oil_press,ectm_oat,ectm_oat FROM tbl_afml a INNER JOIN tbl_user b ON a.user_id = b.user_id  WHERE a.aircraft_reg_code <> 'INITIAL' AND a.client_id = '".$_SESSION['client_id']."' GROUP BY a.afml_page_no ORDER BY a.afml_page_no LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='4'){
        $sql = "SELECT afml_id,a.afml_page_no, b.full_name as created_by, a.aircraft_reg_code,afml_file,date_format(a.afml_date, '%d/%m/%Y') as afml_date, afml_engineer_preflight_user_id,afml_captain_user_id,afml_copilot_user_id,afml_engineer_on_board_user_id, ectm_time,ectm_altitude,ectm_ias,ectm_tq,ectm_itt,ectm_ng,ectm_np, ectm_ff,ectm_oil_temp,ectm_oil_press,ectm_oat,ectm_oat FROM tbl_afml a INNER JOIN tbl_user b ON a.user_id = b.user_id WHERE a.aircraft_reg_code <> 'INITIAL' AND a.client_id = '".$_SESSION['client_id']."' GROUP BY a.afml_page_no ORDER BY a.aircraft_reg_code LIMIT $offset, $dataPerPage";                     
      }else{
        $sql = "SELECT afml_id,a.afml_page_no, b.full_name as created_by, a.aircraft_reg_code,afml_file,date_format(a.afml_date, '%d/%m/%Y') as afml_date, afml_engineer_preflight_user_id,afml_captain_user_id,afml_copilot_user_id,afml_engineer_on_board_user_id,ectm_time,ectm_altitude,ectm_ias,ectm_tq,ectm_itt,ectm_ng,ectm_np, ectm_ff,ectm_oil_temp,ectm_oil_press,ectm_oat,ectm_oat FROM tbl_afml a INNER JOIN tbl_user b ON a.user_id = b.user_id WHERE a.aircraft_reg_code <> 'INITIAL' AND a.client_id = '".$_SESSION['client_id']."' GROUP BY a.afml_page_no ORDER BY a.afml_date DESC LIMIT $offset, $dataPerPage";
      }
      $rs_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($rs_result)==0) {
      ?>
        <div class="alert alert-info ks-solid text-center" role="alert">Oops, no data found</div>
    <?php } ?>    
        <div class="block table-responsive">
            <div class="block-header block-header-default bg-gd-lake">
                <h3 class="block-title">Edit Data AFML</h3>
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
                            <th>Page No.</th>
                            <th>AFML Date</th>
                            <th>Reg. Code</th>
                            <th>Created By</th>
                            <th>File</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                        <tr>
                            <td><?php echo $row['afml_page_no'] ?></td>
                            <td><?php echo $row['afml_date'] ?></td>
                            <td><?php echo $row['aircraft_reg_code'] ?></td>
                            <td><?php echo $row['created_by'] ?></td>
                            <td>
                            <?php 
                            if($row['afml_file']<>'') { 
                                echo '<a href=../uploads/afml-form/'.$row['afml_file'].' target=_blank><i class="si si-paper-clip"></i></a>';
                            }else{
                            }
                            ?>
                            </td>                        
                            <td class="text-center">
                                <div class="btn-group">
                                  <a href="#" data-toggle="modal" data-target="#modal-upload<?php echo $row['afml_id'] ?>">
                                        re-upload
                                    </a>&nbsp;&nbsp; | &nbsp;&nbsp;
                                    <a href="#" data-toggle="modal" data-target="#modal-compose<?php echo $row['afml_id'] ?>">
                                        edit form
                                    </a>&nbsp;&nbsp; | &nbsp;&nbsp;
                                    <a href="afml-change-hour.php?act=29dvi59&ntf=29dvi59-<?php echo $row["afml_id"]?>-<?php echo $row["afml_page_no"]?>-94dfvj!sdf-349ffuaw">
                                        edit hours
                                    </a>&nbsp;&nbsp; | &nbsp;&nbsp;
                                    <a href="afml-change-log.php?act=29dvi59&ntf=29dvi59-<?php echo $row["afml_id"]?>-<?php echo $row["afml_page_no"]?>-94dfvj!sdf-349ffuaw">
                                        log
                                    </a>                                    
                                </div>
                            </td>

                              <!-- Compose Modal -->
                              <div class="modal fade" id="modal-upload<?php echo $row['afml_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="block block-themed block-transparent mb-0">
                                      <div class="block-header">
                                        <h3 class="block-title">
                                          <i class="fa fa-pencil mr-5"></i> Re-upload AFML Attachment. Page No: <?php echo $row['afml_page_no'] ?>
                                        </h3>
                                        <div class="block-options">
                                          <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                              <i class="si si-close"></i>
                                          </button>
                                        </div>
                                      </div>
                                      <div class="block-content">
                                        <form method="POST" action="afml-change-upload.php" enctype="multipart/form-data">
                                          <input type="hidden" name="afml_id" value="<?php echo $row['afml_id'] ?>">
                                          <input type="hidden" name="afml_page_no" value="<?php echo $row['afml_page_no'] ?>">
                                            <div class="row">
                                              <div class="col-md-12">
                                                <div class="form-group">
                                                  <label class="col-12">Upload AFML File (Format JPG, PDF) <label class="text-danger">PASTIKAN ANDA TIDAK SALAH UPLOAD !!</label></label>
                                                  <div class="col-12">
                                                    <input type="file" name="upload_file"> (File size must < 1 MB)
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          <br/><br/>
                                          <input type="submit" class="btn btn-success mr-5 mb-5" value="Save" /> <a class="btn btn-info mr-5 mb-5 text-white" data-dismiss="modal"><i class="si si-close"></i> Close</a>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!--end modal--> 

                            <!-- Compose Modal -->
                              <div class="modal fade" id="modal-compose<?php echo $row['afml_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="block block-themed block-transparent mb-0">
                                      <div class="block-header">
                                        <h3 class="block-title">
                                          <i class="fa fa-pencil mr-5"></i> Edit AFML Form. Page No: <?php echo $row['afml_page_no'] ?>
                                        </h3>
                                        <div class="block-options">
                                          <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                              <i class="si si-close"></i>
                                          </button>
                                        </div>
                                      </div>
                                      <div class="block-content">
                                        <form method="POST" action="afml-change-edit.php">
                                          <input type="hidden" name="afml_id" value="<?php echo $row['afml_id'] ?>">
                                            <div class="row">
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label class="bmd-label-floating">AFML DATE (Flight Date, format dd/mm/yyyy)</label>
                                                  <input type="text" class="form-control" id="afml_date" name="afml_date" autocomplete="off" value="<?php echo $row['afml_date'] ?>" />
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label class="bmd-label-floating">A/C Reg<label class="text-danger">*</label></label>
                                                  <select class="form-control" name="aircraft_reg_code">
                                                    <option value=""> -- None -- </option>
                                                    <?php
                                                    $sql1  = "SELECT aircraft_reg_code FROM tbl_aircraft_master WHERE client_id = '".$_SESSION['client_id']."'";
                                                    $h1    = mysqli_query($conn,$sql1);
                                                    while($row1 = mysqli_fetch_assoc($h1)) {
                                                      if($row['aircraft_reg_code']==$row1['aircraft_reg_code']) {
                                                    ?>
                                                    <option value="<?php echo $row1['aircraft_reg_code'] ?>" selected="selected"><?php echo $row1['aircraft_reg_code'] ?></option>
                                                  <?php }else{ ?>
                                                    <option value="<?php echo $row1['aircraft_reg_code'] ?>"><?php echo $row1['aircraft_reg_code'] ?></option>
                                                  <?php }} ?>
                                                  </select> 
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label class="bmd-label-floating">Page No.<label class="text-danger">*</label></label>
                                                  <input type="text" class="form-control" name="afml_page_no" required="required" autocomplete="off" value="<?php echo $row['afml_page_no'] ?>" />
                                                </div>
                                              </div>                
                                            </div>
                                            <div class="row">
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label class="bmd-label-floating">Captain</label>
                                                    <select class="form-control" name="afml_captain_user_id">
                                                    <option value=""> -- Choose -- </option>
                                                    <?php
                                                    $sql1  = "SELECT user_id,full_name FROM tbl_user WHERE department_id = 4 AND user_position LIKE '%Pilot%' AND user_active_status = 1 AND client_id = '".$_SESSION['client_id']."' ORDER BY full_name";
                                                    $h1    = mysqli_query($conn,$sql1);
                                                    while($row1 = mysqli_fetch_assoc($h1)) {
                                                      if($row1['user_id']==$row['afml_captain_user_id']) {
                                                    ?>
                                                    <option value="<?php echo $row1['user_id'] ?>" selected="selected"><?php echo $row1['full_name'] ?></option>
                                                  <?php }else{ ?>
                                                    <option value="<?php echo $row1['user_id'] ?>"><?php echo $row1['full_name'] ?></option>
                                                    <?php }} ?>
                                                  </select>
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label class="bmd-label-floating">Co-Pilot</label>
                                                  <select class="form-control" name="afml_copilot_user_id">
                                                    <option value=""> -- Choose -- </option>
                                                    <?php
                                                    $sql1  = "SELECT user_id,full_name FROM tbl_user WHERE department_id = 4 AND user_active_status = 1  AND client_id = '".$_SESSION['client_id']."' ORDER BY full_name";
                                                    $h1    = mysqli_query($conn,$sql1);
                                                    while($row1 = mysqli_fetch_assoc($h1)) {
                                                      if($row1['user_id']==$row['afml_copilot_user_id']) {
                                                    ?>
                                                    <option value="<?php echo $row1['user_id'] ?>" selected="selected"><?php echo $row1['full_name'] ?></option>
                                                  <?php }else{ ?>
                                                    <option value="<?php echo $row1['user_id'] ?>"><?php echo $row1['full_name'] ?></option>
                                                    <?php }} ?>
                                                  </select>
                                                </div>
                                              </div>              
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label class="bmd-label-floating">Engineer on Board (if any)</label>
                                                  <select class="form-control" name="afml_engineer_on_board_user_id">
                                                    <option value="0"> -- Choose -- </option>
                                                    <?php
                                                    $sql1  = "SELECT user_id,full_name FROM tbl_user WHERE department_id = 1 AND user_active_status = 1  AND client_id = '".$_SESSION['client_id']."' ORDER BY full_name";
                                                    $h1    = mysqli_query($conn,$sql1);
                                                    while($row1 = mysqli_fetch_assoc($h1)) {
                                                      if($row1['user_id']==$row['user_id']) {
                                                    ?>
                                                    <option value="<?php echo $row1['user_id'] ?>" selected="selected"><?php echo $row1['full_name'] ?></option>
                                                  <?php }else{ ?>
                                                    <option value="<?php echo $row1['user_id'] ?>"><?php echo $row1['full_name'] ?></option>
                                                    <?php }} ?>
                                                  </select>
                                                </div>
                                              </div>
                                            </div>           
                                            <br/><br/><b><font color="#ff0000">ECTM MANUAL ENTRY</font></b><br/><br/>
                                            <div class="row">
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                  <label class="bmd-label-floating">Time (UTC)</label>
                                                  <input type="text" class="form-control" name="ectm_time" id="ectm_time"  autocomplete="off" value="<?php echo $row['ectm_time'] ?>" />
                                                </div>
                                              </div>
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                  <label class="col-12">Altitude</label>
                                                  <div class="col-12">
                                                    <input type="text" class="form-control" name="ectm_altitude" autocomplete="off" value="<?php echo $row['ectm_altitude'] ?>"/>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                  <label class="col-12">IAS</label>
                                                  <div class="col-12">
                                                    <input type="text" class="form-control" name="ectm_ias" autocomplete="off" value="<?php echo $row['ectm_ias'] ?>" />
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                  <label class="col-12">TQ/EPR</label>
                                                  <div class="col-12">
                                                    <input type="text" class="form-control" name="ectm_tq" autocomplete="off" value="<?php echo $row['ectm_tq'] ?>" />
                                                  </div>
                                                </div>
                                              </div> 
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                  <label class="col-12">ITT/EGT</label>
                                                  <div class="col-12">
                                                    <input type="text" class="form-control" name="ectm_itt" autocomplete="off" value="<?php echo $row['ectm_itt'] ?>" />
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                  <label class="col-12">NG/NG2</label>
                                                  <div class="col-12">
                                                    <input type="text" class="form-control" name="ectm_ng" autocomplete="off" value="<?php echo $row['ectm_ng'] ?>" />
                                                  </div>
                                                </div>
                                              </div>             
                                            </div>
                                            <div class="row">
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                  <label class="bmd-label-floating">NP/N1</label>
                                                  <input type="text" class="form-control" name="ectm_np" autocomplete="off" value="<?php echo $row['ectm_np'] ?>" />
                                                </div>
                                              </div>
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                  <label class="col-12">F/F</label>
                                                  <div class="col-12">
                                                    <input type="text" class="form-control" name="ectm_ff" autocomplete="off" value="<?php echo $row['ectm_ff'] ?>" />
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                  <label class="col-12">Oil Temp</label>
                                                  <div class="col-12">
                                                    <input type="text" class="form-control" name="ectm_oil_temp" autocomplete="off" value="<?php echo $row['ectm_oil_temp'] ?>" />
                                                  </div>
                                                </div>
                                              </div> 
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                  <label class="col-12">Oil Press</label>
                                                  <div class="col-12">
                                                    <input type="text" class="form-control" name="ectm_oil_press" autocomplete="off"  value="<?php echo $row['ectm_oil_press'] ?>" />
                                                  </div>
                                                </div>
                                              </div> 
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                  <label class="col-12">OAT</label>
                                                  <div class="col-12">
                                                    <input type="text" class="form-control" name="ectm_oat" autocomplete="off" value="<?php echo $row['ectm_oat'] ?>" />
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          <br/><br/>
                                          <input type="submit" class="btn btn-success mr-5 mb-5" value="Save" /> <a class="btn btn-info mr-5 mb-5 text-white" data-dismiss="modal"><i class="si si-close"></i> Close</a>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!--end modal--> 
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
          $sql = "SELECT count(afml_id) as jumData FROM tbl_afml a INNER JOIN tbl_user b ON a.afml_captain_user_id = b.user_id WHERE aircraft_reg_code <> 'INITIAL' AND (afml_page_no LIKE '%$txt_search%' OR aircraft_reg_code LIKE '%$txt_search%' OR aircraft_serial_number LIKE '%$txt_search%' OR full_name LIKE '%$txt_search%') AND a.client_id = '".$_SESSION['client_id']."'";
        }elseif(@$_REQUEST['s']=='1') {           
          $sql = "SELECT count(afml_id) as jumData FROM tbl_afml a INNER JOIN tbl_user b ON a.afml_captain_user_id = b.user_id WHERE aircraft_reg_code <> 'INITIAL' AND a.client_id = '".$_SESSION['client_id']."' ORDER by created_by DESC";
        }elseif(@$_REQUEST['s']=='2') {           
          $sql = "SELECT count(afml_id) as jumData FROM tbl_afml a INNER JOIN tbl_user b ON a.afml_captain_user_id = b.user_id WHERE aircraft_reg_code <> 'INITIAL' AND a.client_id = '".$_SESSION['client_id']."' ORDER by created_date DESC";
        }elseif(@$_REQUEST['s']=='3') {           
          $sql = "SELECT count(afml_id) as jumData FROM tbl_afml a INNER JOIN tbl_user b ON a.afml_captain_user_id = b.user_id WHERE aircraft_reg_code <> 'INITIAL' AND a.client_id = '".$_SESSION['client_id']."' ORDER by afml_page_no DESC";
        }elseif(@$_REQUEST['s']=='4') {           
          $sql = "SELECT count(afml_id) as jumData FROM tbl_afml a INNER JOIN tbl_user b ON a.afml_captain_user_id = b.user_id WHERE aircraft_reg_code <> 'INITIAL' AND a.client_id = '".$_SESSION['client_id']."' ORDER by aircraft_reg_code DESC";                             
        }else{            
          $sql = "SELECT count(afml_id) as jumData FROM tbl_afml a INNER JOIN tbl_user b ON a.afml_captain_user_id = b.user_id WHERE aircraft_reg_code <> 'INITIAL' AND a.client_id = '".$_SESSION['client_id']."'";
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
