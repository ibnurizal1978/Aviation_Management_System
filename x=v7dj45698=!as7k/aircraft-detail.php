<?php 
require_once 'header.php';
$sql  = "SELECT *,date_format(manufacture_date, '%d/%m/%Y') as manufacture_date FROM tbl_aircraft_master a INNER JOIN tbl_aircraft_type b USING (aircraft_type_id) WHERE aircraft_master_id = '".$ntf[1]."' LIMIT 1";
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
                    <form action="aircraft.php" method="post">
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
                    <form action="aircraft.php" method="post">
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
        <h3 class="block-title">Aircraft Detail</h3>
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
            <input type="hidden" name="aircraft_master_id" value="<?php echo $row['aircraft_master_id'] ?>">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Registration No.<label class="text-danger">*</label></label>
                  <input type="text" class="form-control" name="aircraft_reg_no" autocomplete="off" value="<?php echo $row['aircraft_reg_no'] ?>" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Registration Code<label class="text-danger">*</label></label>
                  <input type="text" class="form-control" name="aircraft_reg_code" autocomplete="off" value="<?php echo $row['aircraft_reg_code'] ?>" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Aircraft Type<label class="text-danger">*</label></label>
                  <input type="text" class="form-control" name="aircraft_type" autocomplete="off" value="<?php echo $row['aircraft_type'] ?>" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Category</label>
                  <select class="btn-primary" data-style="select-with-transition" required name="aircraft_type_id" style="padding-left: 5px; padding-right: 5px">
                      <?php
                      $sql  = "SELECT * FROM tbl_aircraft_type ORDER BY aircraft_type_name";
                      $h    = mysqli_query($conn,$sql);
                      while($row1 = mysqli_fetch_assoc($h)) {
                        if($row['aircraft_type_id']==$row1['aircraft_type_id']) {
                      ?>
                      <option value="<?php echo $row1['aircraft_type_id'] ?>" selected="selected"><?php echo $row1['aircraft_type_name'] ?></option>
                      <?php }else{ ?>
                      <option value="<?php echo $row1['aircraft_type_id'] ?>"><?php echo $row1['aircraft_type_name'] ?></option> 
                      <?php }} ?>
                    </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Aircraft Serial Number</label>
                  <input type="text" class="form-control" name="aircraft_serial_number" autocomplete="off" value="<?php echo $row['aircraft_serial_number'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Engine Part Number</label>
                  <input type="text" class="form-control" name="engine_part_number" autocomplete="off" value="<?php echo $row['engine_part_number'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Engine Serial Number</label>
                  <input type="text" class="form-control" name="engine_serial_number" autocomplete="off" value="<?php echo $row['engine_serial_number'] ?>" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Prop Part Number</label>
                  <input type="text" class="form-control" name="prop_part_number" autocomplete="off" value="<?php echo $row['prop_part_number'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Prop Serial Number</label>
                  <input type="text" class="form-control" name="prop_serial_number" autocomplete="off" value="<?php echo $row['prop_serial_number'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Manufacture Year</label>
                  <input type="text" class="form-control" name="manufacture_date" id="manufacture_date" autocomplete="off" value="<?php echo $row['manufacture_date'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Delivery Year</label>
                  <select class="btn-primary" data-style="select-with-transition" required name="delivery_date" style="padding-left: 5px; padding-right: 5px">
                    <?php 
                    for($i=2000;$i<date('Y')+1;$i++) { 
                      if($row['delivery_date']==$i) {
                    ?>
                    <option value="<?php echo $i ?>" selected="selected"><?php echo $i ?></option>
                    <?php }else{ ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php }} ?>
                  </select>   
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
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="aircraft.php"><i class="si si-arrow-left mr-5"></i>Back</a> <a href="aircraft-photo.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-camera mr-5"></i>Photos</a> <a href="aircraft-certificate.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-bag mr-5"></i>Certificate</a> <a href="aircraft-parts.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-wrench mr-5"></i>Parts</a> <a href="aircraft-book.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-notebook"></i> Book</a>');  
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
      url:"aircraft-edit.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="aircraft.php"><i class="si si-arrow-left mr-5"></i>Back</a> <a href="aircraft-photo.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-camera mr-5"></i>Photos</a> <a href="aircraft-certificate.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-bag mr-5"></i>Certificate</a> <a href="aircraft-parts.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-wrench mr-5"></i>Parts</a> <a href="aircraft-book.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-notebook"></i> Book</a>');  
      }  
    });  
  });  
});

//date format
$(document).ready(function() {
  $("#manufacture_date").attr("maxlength", 10);
  $("#manufacture_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });

});
</script>
