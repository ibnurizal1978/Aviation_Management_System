<?php
require_once 'header.php';
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
                    <form action="afml.php" method="post">
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
                    <form action="afml.php" method="post">
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
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Create New AFML</h3>
        <div class="block-options">
          <div class="block-options-item">
            <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                <i class="fa fa-filter"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="block-content">
        <div class="card-body">
          <form id="form_simpan">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">DATE<label class="text-danger">*</label></label>
                  <input type="text" class="form-control" id="afml_date" name="afml_date" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">A/C REG<label class="text-danger">*</label></label>
                  <select class="form-control" required name="aircraft_reg_code" style="padding-left: 5px; padding-right: 5px">
                    <option value=""> -- None -- </option>
                    <?php
                    $sql1  = "SELECT aircraft_reg_code FROM tbl_aircraft_master WHERE client_id = '".$_SESSION['client_id']."'";
                    echo $sql1;
                    $h1    = mysqli_query($conn,$sql1);
                    while($row1 = mysqli_fetch_assoc($h1)) {
                    ?>
                    <option value="<?php echo $row1['aircraft_reg_code'] ?>"><?php echo $row1['aircraft_reg_code'] ?></option>
                    <?php } ?>
                  </select> 
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">PAGE NO<label class="text-danger">*</label></label>
                  <input type="text" class="form-control" name="afml_page_no" required="required" autocomplete="off" />
                </div>
              </div>                
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">CAPT<label class="text-danger">*</label></label>
                    <select class="form-control" required name="afml_pilot" style="padding-left: 5px; padding-right: 5px">
                    <option value=""> -- Choose -- </option>
                    <?php
                    $sql  = "SELECT user_id,full_name FROM tbl_user WHERE department_id = 4 AND client_id = '".$_SESSION['client_id']."'";
                    echo $sql;
                    $h    = mysqli_query($conn,$sql);
                    while($row = mysqli_fetch_assoc($h)) {
                    ?>
                    <option value="<?php echo $row['user_id'] ?>"><?php echo $row['full_name'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">COPIL</label>
                  <select class="form-control" name="afml_copilot" style="padding-left: 5px; padding-right: 5px">
                    <option value=""> -- Choose -- </option>
                    <?php
                    $sql  = "SELECT user_id,full_name FROM tbl_user WHERE department_id = 4 AND client_id = '".$_SESSION['client_id']."'";
                    echo $sql;
                    $h    = mysqli_query($conn,$sql);
                    while($row = mysqli_fetch_assoc($h)) {
                    ?>
                    <option value="<?php echo $row['user_id'] ?>"><?php echo $row['full_name'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">ENG on Board</label>
                  <select class="form-control" name="afml_copilot" style="padding-left: 5px; padding-right: 5px">
                    <option value=""> -- None -- </option>
                    <?php
                    $sql  = "SELECT user_id,full_name FROM tbl_user WHERE department_id = 1 AND client_id = '".$_SESSION['client_id']."'";
                    echo $sql;
                    $h    = mysqli_query($conn,$sql);
                    while($row = mysqli_fetch_assoc($h)) {
                    ?>
                    <option value="<?php echo $row['user_id'] ?>"><?php echo $row['full_name'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>              
              <div class="col-md-3">
                <div class="form-group">
                  <label class="col-12">Upload AFML File (Format JPG, PDF)</label>
                  <div class="col-12">
                    <input type="file" name="upload_file">
                  </div>
                </div>
              </div>              
            </div>
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label class="bmd-label-floating">Time Preflight</label>
                  <input type="text" class="form-control" name="afml_time_preflight" autocomplete="off" id="afml_time_preflight"  />
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="bmd-label-floating">Time Daily</label>
                  <input type="text" class="form-control" name="afml_time_daily" id="afml_time_daily" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="bmd-label-floating">Station Preflight</label><br/>
                  <select class="form-control" required name="afml_station_preflight" style="padding-left: 5px; padding-right: 5px">
                    <option value="0"> -- None -- </option>
                    <?php
                    $sql  = "SELECT iata_code FROM tbl_master_iata WHERE iata_code NOT IN ('','-') ORDER BY iata_code";
                    $h    = mysqli_query($conn,$sql);
                    while($row = mysqli_fetch_assoc($h)) {
                    ?>
                    <option value="<?php echo $row['iata_code'] ?>"><?php echo $row['iata_code'] ?></option>
                    <?php } ?>
                  </select> 
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="bmd-label-floating">Station Daily</label><br/>
                  <select class="form-control" required name="afml_station_daily" style="padding-left: 5px; padding-right: 5px">
                    <option value="0"> -- None -- </option>
                    <?php
                    $sql  = "SELECT iata_code FROM tbl_master_iata WHERE iata_code NOT IN ('','-') ORDER BY iata_code";
                    $h    = mysqli_query($conn,$sql);
                    while($row = mysqli_fetch_assoc($h)) {
                    ?>
                    <option value="<?php echo $row['iata_code'] ?>"><?php echo $row['iata_code'] ?></option>
                    <?php } ?>
                  </select> 
                </div>
              </div> 
              <div class="col-md-2">
                <div class="form-group">
                  <label class="bmd-label-floating">LIC No. Preflight</label>
                  <input type="text" class="form-control" name="afml_lic_preflight" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="bmd-label-floating">LIC No. Daily</label>
                  <input type="text" class="form-control" name="afml_lic_daily" autocomplete="off" />
                </div>
              </div>                               
            </div>
            <!--<div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="bmd-label-floating">Your Notes</label>
                  <input type="text" class="form-control" name="afml_notes_pilot" autocomplete="off" />
                </div>
              </div>              
            </div>-->
            <br/><br/><b><font color="#ff0000">ECTM MANUAL ENTRY</font></b><br/><br/>
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label class="bmd-label-floating">Time (UTC)</label>
                  <input type="text" class="form-control" name="etcm_time" id="etcm_time"  autocomplete="off" />
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="col-12">Altitude</label>
                  <div class="col-12">
                    <input type="text" class="form-control" name="ectm_altitude" autocomplete="off" />
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="col-12">IAS</label>
                  <div class="col-12">
                    <input type="text" class="form-control" name="ectm_ias" autocomplete="off" />
                  </div>
                </div>
              </div> 
              <div class="col-md-2">
                <div class="form-group">
                  <label class="col-12">TQ/EPR</label>
                  <div class="col-12">
                    <input type="text" class="form-control" name="ectm_tq" autocomplete="off" />
                  </div>
                </div>
              </div> 
              <div class="col-md-2">
                <div class="form-group">
                  <label class="col-12">ITT/EGT</label>
                  <div class="col-12">
                    <input type="text" class="form-control" name="ectm_itt" autocomplete="off" />
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="col-12">NG/NG2</label>
                  <div class="col-12">
                    <input type="text" class="form-control" name="ectm_ng" autocomplete="off" />
                  </div>
                </div>
              </div>                                                                      
            </div>
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label class="bmd-label-floating">NP/N1</label>
                  <input type="text" class="form-control" name="ectm_np" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="col-12">F/F</label>
                  <div class="col-12">
                    <input type="text" class="form-control" name="ectm_ff" autocomplete="off" />
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="col-12">Oil Temp</label>
                  <div class="col-12">
                    <input type="text" class="form-control" name="ectm_oil_temp" autocomplete="off" />
                  </div>
                </div>
              </div> 
              <div class="col-md-2">
                <div class="form-group">
                  <label class="col-12">Oil Press</label>
                  <div class="col-12">
                    <input type="text" class="form-control" name="ectm_oil_press" autocomplete="off" />
                  </div>
                </div>
              </div> 
              <div class="col-md-2">
                <div class="form-group">
                  <label class="col-12">OAT</label>
                  <div class="col-12">
                    <input type="text" class="form-control" name="ectm_oat" autocomplete="off" />
                  </div>
                </div>
              </div>                                                                      
            </div>            
            <div id="results"></div><div id="button"></div>
            <div class="clearfix"></div>
          </form>

        </div>
      </div>
    </div>
  <!-- END Small Table -->

  <!-- END Page Content -->
  </div>
</main>
<?php require_once 'footer.php' ?>

<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="afml.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
  $('#submit_data').click(function(){  
    $('#form_simpan').submit(); 
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>');
  });  
  $('#form_simpan').on('submit', function(event){
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>');    
    event.preventDefault();  
    $.ajax({  
      url:"afml-add-new-version.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="afml.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });  
});  

$(document).ready(function() {

  $("#afml_date").attr("maxlength", 11);
  $("#afml_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });

  $("#etcm_time").attr("maxlength", 5);
  $("#etcm_time").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });  

  $("#afml_time_preflight").attr("maxlength", 5);
  $("#afml_time_preflight").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });

  $("#afml_time_daily").attr("maxlength", 5);
  $("#afml_time_daily").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });
      
}); 
</script>

