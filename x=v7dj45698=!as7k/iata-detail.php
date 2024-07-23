<?php 
require_once 'header.php';
$sql  = "SELECT master_iata_id,iata_location,iata_province,icao_code,iata_code,iata_airport_name FROM tbl_master_iata WHERE client_id = '".$_SESSION['client_id']."' AND master_iata_id = '".$ntf[1]."' LIMIT 1";
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
        <h3 class="block-title">IATA Detail</h3>
      </div>
      <div class="block-content">
                
        <div class="card-body">
          <form id="form_simpan">
            <input type="hidden" name="master_iata_id" value="<?php echo $row['master_iata_id'] ?>" />
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Location (City, Area etc)<label class="text-danger">*</label></label>
                  <input type="text" class="form-control" name="iata_location" autocomplete="off" value="<?php echo $row['iata_location'] ?>" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">IATA CODE<label class="text-danger">*</label></label>
                  <input type="text" class="form-control" name="iata_code" autocomplete="off"  value="<?php echo $row['iata_code'] ?>" />
                </div>
              </div>              
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">ICAO CODE</label>
                  <input type="text" class="form-control" name="icao_code" autocomplete="off"  value="<?php echo $row['icao_code'] ?>"/>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Province<label class="text-danger">*</label></label>
                  <select class="form-control" required name="iata_province">
                    <option value=""> -- Choose -- </option>
                    <?php
                    $sql1  = "SELECT iata_province FROM tbl_master_iata WHERE client_id = '".$_SESSION['client_id']."' GROUP BY iata_province ORDER BY iata_province";
                    echo $sql1;
                    $h1    = mysqli_query($conn,$sql1);
                    while($row1 = mysqli_fetch_assoc($h1)) {
                      if($row['iata_province']==$row1['iata_province']) {
                    ?>
                    <option value="<?php echo $row1['iata_province'] ?>" selected="selected"><?php echo $row1['iata_province'] ?></option>
                  <?php }else{ ?>
                    <option value="<?php echo $row1['iata_province'] ?>"><?php echo $row1['iata_province'] ?></option>
                    <?php }} ?>
                  </select> 
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Airport Name</label>
                  <input type="text" class="form-control" name="iata_airport_name" autocomplete="off" value="<?php echo $row['iata_airport_name'] ?>" />
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
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="iata.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
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
      url:"iata-edit.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="iata.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });  
});
</script>
