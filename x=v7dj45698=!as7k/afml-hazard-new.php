<?php
require_once 'header.php';
$sql  = "SELECT afml_id,afml_page_no FROM tbl_afml WHERE afml_id = '".$ntf[1]."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h    = mysqli_query($conn, $sql);
$r    = mysqli_fetch_assoc($h);
?>

<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Create New Hazard. AFML No. : <?php echo $r['afml_page_no'] ?></h3>
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
            <input type="hidden" class="form-control" name="afml_page_no" value="<?php echo $r['afml_page_no'] ?>" />
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Reg No<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="hazard_reg_no" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Description of Hazard<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="hazard_description" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Location<span class="text-danger">*</span></label>
                  <select class="form-control" required name="hazard_location">
                    <option value=""> -- Choose -- </option>
                    <?php
                    $sql2  = "SELECT iata_code FROM tbl_master_iata WHERE iata_code NOT IN ('','-') ORDER BY iata_code";
                    $h2    = mysqli_query($conn,$sql2);
                    while($row2 = mysqli_fetch_assoc($h2)) {
                    ?>
                    <option value="<?php echo $row2['iata_code'] ?>"><?php echo $row2['iata_code'] ?></option>
                    <?php } ?>
                  </select>
                </div> 
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Date<span class="text-danger">*</span> (dd/mm/yyyy)</label>
                  <input type="text" class="form-control" id="hazard_date" name="hazard_date" autocomplete="off" />
                </div>                
              </div>                          
            </div>
            <div class="row">
              <div class="col-md-4">              
                <div class="form-group">
                  <label class="bmd-label-floating">Probability/Vulnerability</label>
                  <select class="form-control" required name="hazard_probability" >
                    <option value="0"> -- Choose -- </option>
                    <?php for ($i=1;$i<6;$i++) { ?>
                    ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option> 
                    <?php } ?>
                  </select>
                </div>
              </div>              
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Severity</label>
                  <select class="form-control" required name="hazard_severity" >
                    <option value="0"> -- Choose -- </option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                  </select>
                </div>
              </div>             
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Recommendation<span class="text-danger">*</span></label>
                  <textarea class="form-control" name="hazard_recommendation" rows="1" autocomplete="off"></textarea>
                </div>
              </div>
            </div>
            <div class="row">                                  
              <div id="results"></div><div id="button"></div>
              <div class="clearfix"></div>
            </div>
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
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="hazard.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
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
      url:"hazard-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="hazard.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });  
});  

$(document).ready(function() {

  $("#hazard_date").attr("maxlength", 10);
  $("#hazard_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });
      
}); 
</script>

