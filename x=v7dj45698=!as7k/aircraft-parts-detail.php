<?php 
require_once 'header.php';
$sql  = "SELECT *,date_format(installed_date, '%d/%m/%Y') as installed_date FROM tbl_aircraft_master a INNER JOIN tbl_aircraft_parts b USING (aircraft_master_id) WHERE aircraft_parts_id = '".$ntf[1]."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
$aircraft_master_id = $row['aircraft_master_id'];
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
                    <form action="aircraft-parts.php" method="post">
                        <input type="hidden" name="s" value="1091vdf8ame151">
                        <input type="hidden" name="aircraft_master_id" value="<?php echo $row['aircraft_master_id'] ?>">
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
                    <form action="aircraft-parts.php" method="post">
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
        <h3 class="block-title">Detail Components for <?php echo $row['aircraft_reg_code'] ?></h3>
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
            <input type="hidden" name="aircraft_parts_id" value="<?php echo $row['aircraft_parts_id'] ?>">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Item No</label>
                  <input type="text" class="form-control" name="item_number" autocomplete="off" value="<?php echo $row['item_number'] ?>" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Position</label>
                  <input type="text" class="form-control" name="position" autocomplete="off" value="<?php echo $row['position'] ?>" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Description</label>
                  <input type="text" class="form-control" name="description" autocomplete="off" value="<?php echo $row['description'] ?>" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Part No.</label>
                  <input type="text" class="form-control" name="part_number" autocomplete="off" value="<?php echo $row['part_number'] ?>" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Serial Number</label>
                  <input type="text" class="form-control" name="serial_number" autocomplete="off" value="<?php echo $row['serial_number'] ?>" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Installed Date</label>
                  <input type="text" class="form-control" name="installed_date" id="installed_date" autocomplete="off" value="<?php echo $row['installed_date'] ?>" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">MTH</label>
                  <input type="text" class="form-control" name="mth" autocomplete="off" value="<?php echo $row['mth'] ?>" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">HRS</label>
                  <input type="text" class="form-control" name="hrs" autocomplete="off" value="<?php echo $row['hrs'] ?>" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">LDG</label>
                  <input type="text" class="form-control" name="ldg" autocomplete="off" value="<?php echo $row['ldg'] ?>" />
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
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="aircraft.php"><i class="si si-arrow-left mr-5"></i>Back</a> <a href="aircraft-photo.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-camera mr-5"></i>Photos</a> <a href="aircraft-certificate.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-bag mr-5"></i>Certificate</a> <a href="aircraft-parts.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-wrench mr-5"></i>Parts</a>');  
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
      url:"aircraft-parts-edit.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="aircraft.php"><i class="si si-arrow-left mr-5"></i>Back</a> <a href="aircraft-photo.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-camera mr-5"></i>Photos</a> <a href="aircraft-certificate.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-bag mr-5"></i>Certificate</a> <a href="aircraft-parts.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-wrench mr-5"></i>Parts</a>');  
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
