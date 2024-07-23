<?php
require_once 'header.php';
$aircraft_parts_id = $ntf[1];
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
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Item Number: <?php echo $row['item_number'] ?></label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">ATA Code: <?php echo $row['ata_code'] ?></label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Position: <?php echo $row['position'] ?></label>
                </div>
              </div>                                          
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Description: <?php echo $row['description'] ?></label>
                </div>
              </div>              
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Part Number: <?php echo $row['part_number'] ?></label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Serial Number: <?php echo $row['serial_number'] ?></label>
                </div>
              </div>
            </div>
            <br/><br/>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="bmd-label-floating">Move this parts to</label>
                    <select required name="aircraft_master_id2">
                    <?php
                    $sql  = "SELECT * FROM tbl_aircraft_master WHERE aircraft_master_id <> '".$row['aircraft_master_id']."' AND client_id = '".$_SESSION['client_id']."' ORDER BY aircraft_reg_code";
                    $h    = mysqli_query($conn,$sql);
                    while($row1 = mysqli_fetch_assoc($h)) {
                    ?>
                      <option value="<?php echo $row1['aircraft_master_id'] ?>"><?php echo $row1['aircraft_reg_code'] ?></option> 
                    <?php } ?>
                      <option value="WAREHOUSE">WAREHOUSE</option>
                      <option value="AMO">AMO</option>
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
<?php require_once 'footer.php' ?>

<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button>');  
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
      url:"maintenance-detail-move-edit.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button>');  
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

