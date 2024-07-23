<?php 
require_once 'header.php';
?>

<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Create New Operation Price</h3>
      </div>
      <div class="block-content">
                
        <div class="card-body">
          <form id="form_simpan">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                    <label class="bmd-label-floating">Operation Type</label>
                    <select class="form-control" name="jenis_operasi_name">
                        <option value="">--- Choose ---</option>
                        <option value="Papua">Papua</option>
                        <option value="Non-Papua">Non-Papua</option>
                        <option value="LN">LN</option>
                        <option value="ARQ">ARQ</option>
                    </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label class="bmd-label-floating">IATA CODE</label>
                    <select required name="afml_route_from" class="form-control">
                        <option value=""> -- Choose -- </option>
                        <?php
                        $sql1  = "SELECT iata_code,icao_code FROM tbl_master_iata WHERE iata_code NOT IN ('','-') AND icao_code NOT IN ('','-') ORDER BY iata_code";
                        $h1    = mysqli_query($conn,$sql1);
                        while($row1 = mysqli_fetch_assoc($h1)) {
                        ?>
                        <option value="<?php echo $row1['iata_code'] ?>"><?php echo $row1['iata_code'].' / '.$row1['icao_code'] ?></option>
                        <?php } ?>
                    </select>
                </div>
              </div>              
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Price</label>
                  <input type="text" class="form-control" name="jenis_operasi_price" autocomplete="off" />
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
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="jenis-operasi.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
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
      url:"iata-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="jenis-operasi.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });  
});
</script>
