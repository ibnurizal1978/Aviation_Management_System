<?php 
require_once 'header.php';
$sql  = "SELECT aircraft_master_id,aircraft_reg_code FROM tbl_aircraft_master WHERE aircraft_master_id = '".$ntf[1]."' LIMIT 1";
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
        <h3 class="block-title">Aircraft Photo for <?php echo $row['aircraft_reg_code'] ?></h3>
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
              <div class="col-md-12">
                <div class="form-group">
                  <label class="col-12">Choose Image (Format JPG, BMP, GIF, PNG)</label>
                  <div class="col-12">
                    <input type="file" name="upload_file" id="submit_data">
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



    <h2 class="content-heading">Photo</h2>
    <div class="row items-push">
      <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
      <?php
      $sql_cert = "SELECT aircraft_photo_id,aircraft_photo_name FROM tbl_aircraft_photo WHERE aircraft_master_id = '".$ntf[1]."'";
      $h_cert   = mysqli_query($conn,$sql_cert);
      if(mysqli_num_rows($h_cert)>0) {
      while($row_cert = mysqli_fetch_assoc($h_cert)) {
      ?>
      <form id="form_edit<?php echo $row_cert['aircraft_photo_id'] ?>">
        <input type="hidden" name="t" value="8293eudjk">
        <input type="hidden" name="aircraft_photo_id" value="<?php echo $row_cert['aircraft_photo_id'] ?>">
        <input type="hidden" name="aircraft_master_id" value="<?php echo $row_cert['aircraft_master_id'] ?>">
        <div id="results_edit<?php echo $row_cert['aircraft_photo_id'] ?>"></div>
        <div class="col-md-4 animated fadeIn">
            <div class="options-container">
                <img class="img-fluid options-item" src="<?php echo $base_url ?>uploads/aircraft-brochure/<?php echo $row_cert['aircraft_photo_name'] ?>" alt="">
                <div class="options-overlay bg-black-op-75">
                    <div class="options-overlay-content">
                        <button class="btn btn-sm btn-rounded btn-alt-danger min-width-75" href="#" id="edit_data<?php echo $row_cert['aircraft_photo_id'] ?>">
                            <i class="fa fa-times"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
      </form>
      <script async="async" type="text/javascript">
      //edit data
      
      $(document).ready(function(){
        $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Save</button>');  
        $('#edit_data<?php echo $row_cert['aircraft_photo_id'] ?>').click(function(){  
          $('#form_edit2').submit(); 
          $("#results_edit").html('<img width=50 src=<?php echo $base_url ?>assets/img/loading.gif>');
          $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>'); 
        });  
        $('#form_edit<?php echo $row_cert['aircraft_photo_id'] ?>').on('submit', function(event){
          $("#results_edit<?php echo $row_cert['aircraft_photo_id'] ?>").html('<img width=50 src=<?php echo $base_url ?>assets/img/loading.gif>');
          $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>');   
          event.preventDefault();  
          $.ajax({  
            url:"aircraft-photo-delete.php",  
            method:"POST",  
            data:new FormData(this),  
            contentType:false,  
            processData:false,  
            success:function(data){ 
              $('#results_edit<?php echo $row_cert['aircraft_photo_id'] ?>').html(data);  
              $('#edit_data<?php echo $row_cert['aircraft_photo_id'] ?>').val('');
              $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Save</button>');  
            }  
          });  
        });  
      });                         
      </script>
      <?php }}else { echo "<p class=text-danger>No photo</p>"; } ?>
    </div>

    <!-- END Page Content -->
  </div>
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
<script>  
$(document).ready(function(){
  $("#button").html('<a class="btn btn-info mr-5 mb-5" href="aircraft.php"><i class="si si-arrow-left mr-5"></i>Back</a> <a href="aircraft-photo.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-camera mr-5"></i>Photos</a> <a href="aircraft-certificate.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-bag mr-5"></i>Certificate</a> <a href="aircraft-parts.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-wrench mr-5"></i>Parts</a>');  
  $('#submit_data').change(function(){  
    $('#form_simpan').submit(); 
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>'); 
  });  
  $('#form_simpan').on('submit', function(event){
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>');   
    event.preventDefault();  
    $.ajax({  
      url:"aircraft-photo-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<a class="btn btn-info mr-5 mb-5" href="aircraft.php"><i class="si si-arrow-left mr-5"></i>Back</a> <a href="aircraft-photo.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-camera mr-5"></i>Photos</a> <a href="aircraft-certificate.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-bag mr-5"></i>Certificate</a> <a href="aircraft-parts.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-wrench mr-5"></i>Parts</a>');  
      }  
    });  
  });  
});
</script>