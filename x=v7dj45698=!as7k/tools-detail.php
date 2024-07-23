<?php 
require_once 'header.php';
$sql  = "SELECT *,date_format(last_calibration_date, '%d/%m/%Y') as last_calibration_date,date_format(next_calibration_date, '%d/%m/%Y') as next_calibration_date FROM tbl_tools a INNER JOIN tbl_parts_location b USING (parts_location_id) WHERE tools_id = '".$ntf[1]."' AND a.client_id = '".$_SESSION['client_id']."' LIMIT 1";
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
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Tools Detail</h3>
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
            <input type="hidden" name="tools_id" value="<?php echo $row['tools_id'] ?>">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Tools Name<label class="text-danger">*</label></label>
                  <input type="text" class="form-control" name="tools_name" autocomplete="off" value="<?php echo $row['tools_name'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Description</label>
                  <input type="text" class="form-control" name="tools_description" autocomplete="off" value="<?php echo $row['tools_description'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Size</label>
                  <input type="text" class="form-control" name="size" autocomplete="off" value="<?php echo $row['size'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Manufacturer</label>
                  <input type="text" class="form-control" name="manufacturer" autocomplete="off" value="<?php echo $row['manufacturer'] ?>" />
                </div>
              </div>              
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Part Number</label>
                  <input type="text" class="form-control" name="part_number" autocomplete="off" value="<?php echo $row['part_number'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Serial Number</label>
                  <input type="text" class="form-control" name="serial_number" autocomplete="off" value="<?php echo $row['serial_number'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Qty</label>
                  <input type="text" class="form-control" name="qty" autocomplete="off" value="<?php echo $row['qty'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Location</label>
                  <select class="form-control" data-style="select-with-transition" required name="parts_location_id">
                    <?php
                    $sql  = "SELECT * FROM tbl_parts_location WHERE client_id = '".$_SESSION['client_id']."' ORDER BY parts_location_name";
                    $h    = mysqli_query($conn,$sql);
                    while($row1 = mysqli_fetch_assoc($h)) {
                      if($row['parts_location_id']==$row1['parts_location_id']) {
                    ?>
                    <option value="<?php echo $row1['parts_location_id'] ?>" selected="selected"><?php echo $row1['parts_location_name'] ?></option>
                    <?php }else{ ?>
                    <option value="<?php echo $row1['parts_location_id'] ?>"><?php echo $row1['parts_location_name'] ?></option> 
                    <?php }} ?>
                  </select>
                </div>
              </div>              
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Notes</label>
                  <input type="text" class="form-control" name="notes" autocomplete="off" value="<?php echo $row['notes'] ?>" />
                  </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Type</label>
                  <select class="form-control" required name="tools_type">
                    <?php if($row['tools_type']=='GENERAL') { ?>
                      <option value="GENERAL" selected="selected">GENERAL</option>
                      <option value="SPECIAL">SPECIAL</option>
                    <?php }else{ ?>
                      <option value="GENERAL">GENERAL</option>
                      <option value="SPECIAL" selected="selected">SPECIAL</option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Last Calibration Date</label>
                  <input type="text" class="form-control" name="last_calibration_date" id="last_calibration_date" autocomplete="off" value="<?php echo $row['last_calibration_date'] ?>" />   
                </div>
              </div>                               
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Next Calibration Date</label>
                  <input type="text" class="form-control" id="next_calibration_date" autocomplete="off" value="<?php echo $row['next_calibration_date'] ?>"  />   
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
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="tools.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
  $('#submit_data').click(function(){  
    $('#form_simpan').submit(); 
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
  });  
  $('#form_simpan').on('submit', function(event){
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');   
    event.preventDefault();  
    $.ajax({  
      url:"tools-edit.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="tools.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });  

//date format
  $("#last_calibration_date").attr("maxlength", 10);
  $("#last_calibration_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });
  $("#next_calibration_date").attr("maxlength", 10);
  $("#next_calibration_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });

});
</script>
