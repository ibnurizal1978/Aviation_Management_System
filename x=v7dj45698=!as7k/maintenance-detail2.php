<?php
require_once 'header.php';
$aircraft_parts_id = $ntf[1];
$aircraft_master_id = $ntf[2];
$sql  = "SELECT aircraft_parts_id, aircraft_master_id, date_format(installed_date, '%d/%m/%Y') as installed_date, date_format(next_install_date, '%d/%m/%Y') as next_install_date, item_number, ata_code, position, description, part_number, serial_number, mth, hrs, ldg, next_ldg, next_hrs, aircraft_master_id FROM tbl_aircraft_parts WHERE client_id = '".$_SESSION['client_id']."' AND aircraft_parts_id = '".$aircraft_parts_id."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
?>

<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Detail Parts</h3>
      </div>
      <div class="block-content">
        <div class="card-body">
          <form id="form_simpan">
            <input type="hidden" value="<?php echo $row['aircraft_parts_id'] ?>" name="aircraft_parts_id" />
            <input type="hidden" value="<?php echo $row['aircraft_master_id'] ?>" name="aircraft_master_id" />
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Item Number</label>
                  <input type="text" class="form-control" value="<?php echo $row['item_number'] ?>" name="item_number" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">ATA Code</label>
                  <input type="text" class="form-control" value="<?php echo $row['ata_code'] ?>" name="ata_code" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Position</label>
                  <input type="text" class="form-control" value="<?php echo $row['position'] ?>" name="position" autocomplete="off" />
                </div>
              </div>                                          
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Description<label class="text-danger">*</label></label>
                  <input type="text" class="form-control" value="<?php echo $row['description'] ?>" name="description" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Part Number</label>
                  <input type="text" class="form-control" value="<?php echo $row['part_number'] ?>" name="part_number" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Serial Number<label class="text-danger">*</label></label>
                  <input type="text" class="form-control" value="<?php echo $row['serial_number'] ?>" name="serial_number" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">MTH</label>
                  <input type="text" class="form-control" value="<?php echo $row['mth'] ?>" name="mth" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">LDG</label>
                  <input type="text" class="form-control" value="<?php echo $row['ldg'] ?>" name="ldg" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">HRS</label>
                  <input type="text" class="form-control" value="<?php echo $row['hrs'] ?>" name="hrs" autocomplete="off" />
                </div>
              </div>                                          
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Installed Date<label class="text-danger">*</label></label>
                  <input type="text" class="form-control" value="<?php echo $row['installed_date'] ?>" id="installed_date" name="installed_date" autocomplete="off" />
                </div>
              </div>
            </div>
            <!--
            <div class="row">              
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Next LDG</label>
                  <input type="text" class="form-control" value="<?php echo $row['next_ldg'] ?>" name="next_ldg" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Next HRS</label>
                  <input type="text" class="form-control" value="<?php echo $row['next_hrs'] ?>" name="next_hrs" autocomplete="off" />
                </div>
              </div>                                          
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Next Install Date</label>
                  <input type="text" class="form-control" value="<?php echo $row['next_install_date'] ?>" id="next_install_date" name="next_install_date" autocomplete="off" />
                </div>
              </div>             
            </div>
            -->
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
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="maintenance-detail.php?aircraft_master_id=<?php echo $aircraft_master_id ?>"><i class="si si-arrow-left mr-5"></i>Back</a>');  
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
      url:"maintenance-detail-edit.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="maintenance-detail.php?aircraft_master_id=<?php echo $aircraft_master_id ?>"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });  
});  

$(document).ready(function() {

  $("#installed_date").attr("maxlength", 10);
  $("#installed_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });

  $("#next_install_date").attr("maxlength", 10);
  $("#next_install_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });
      
}); 
</script>

