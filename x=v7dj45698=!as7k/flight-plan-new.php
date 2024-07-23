<?php
require_once 'header.php';
?>

<!-- Main Container -->
<main id="main-container">
  <form id="form_simpan">
  <!-- Page Content -->
    <div class="content">
      <!--table-->   
      <div class="block table-responsive">
        <div class="block-header block-header-default">
          <h3 class="block-title">Create New Flight Plan</h3>
        </div>
        <div class="block-content">       
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">DATE (dd/mm/yyyy)<label class="text-danger">*</label></label>
                  <input type="text" class="form-control" id="flight_plan_date" name="flight_plan_date" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">CAPT</label>
                  <input type="text" class="form-control" value="<?php echo $_SESSION['full_name'] ?>" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">COPIL</label>
                  <select class="form-control" required name="flight_plan_copilot" style="padding-left: 5px; padding-right: 5px">
                    <option value=""> -- None -- </option>
                    <?php
                    $sql  = "SELECT user_id,full_name FROM tbl_user WHERE department_id = 4 AND user_id <> '".$_SESSION['user_id']."' AND client_id = '".$_SESSION['client_id']."'";    
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
                  <label class="bmd-label-floating">Flight #<label class="text-danger">*</label></label>
                  <input type="text" name="flight_plan_number" class="form-control" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">                                          
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">A/C REG<label class="text-danger">*</label></label>
                  <select class="form-control" required name="aircraft_reg_code">
                    <option value=""> -- Choose -- </option>
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
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Type<label class="text-danger">*</label></label>
                  <select class="form-control" required name="flight_plan_type">
                    <option value=""> -- Choose -- </option>
                    <option value="CARGO">CARGO</option>
                    <option value="REGULAR">REGULAR</option>
                    <option value="TRAINING">TRAINING</option>
                    <option value="ROUTE GUIDE">ROUTE GUIDE</option>
                    <option value="REPOS">REPOS</option>
                    <option value="FERRY">FERRY</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Rules<label class="text-danger">*</label></label>
                  <select class="form-control" required name="flight_plan_rules">
                    <option value=""> -- Choose -- </option>
                    <option value="VFR">VFR</option>
                    <option value="IFR">IFR</option>
                    <option value="VFR-IFR">VFR-IFR</option>
                    <option value="IFR-VFR">IFR-VFR</option>
                  </select>
                </div>
              </div>                               
            </div>
          </div>
        </div>
      </div>

      <div class="block table-responsive">
        <div class="block-header block-header-default">
          <h3 class="block-title">Fuel Requirement</h3>
        </div>
        <div class="block-content">
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Taxi FLT 1</label>
                  <input type="text" name="taxi_flt_1" class="form-control" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Taxi FLT 2</label>
                  <input type="text" name="taxi_flt_2" class="form-control" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Taxi FLT 3</label>
                  <input type="text" name="taxi_flt_3" class="form-control" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Taxi FLT 4</label>
                  <input type="text" name="taxi_flt_4" class="form-control" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Holding FLT 1</label>
                  <input type="text" name="holding_flt_1" class="form-control" autocomplete="off" />
                </div>
              </div>              
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Holding FLT 2</label>
                  <input type="text" name="holding_flt_2" class="form-control" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Holding FLT 3</label>
                  <input type="text" name="holding_flt_3" class="form-control" autocomplete="off" />
                </div>
              </div>              
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Holding FLT 4</label>
                  <input type="text" name="holding_flt_4" class="form-control" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Contingency FLT 1</label>
                  <input type="text" name="contingency_flt_1" class="form-control" autocomplete="off" />
                </div>
              </div>              
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Contingency FLT 2</label>
                  <input type="text" name="contingency_flt_2" class="form-control" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Contingency FLT 3</label>
                  <input type="text" name="contingency_flt_3" class="form-control" autocomplete="off" />
                </div>
              </div>              
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Contingency FLT 4</label>
                  <input type="text" name="contingency_flt_4" class="form-control" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Fuel On Board FLT 1</label>
                  <input type="text" name="fob_flt_1" class="form-control" autocomplete="off" />
                </div>
              </div>              
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Fuel On Board FLT 2</label>
                  <input type="text" name="fob_flt_2" class="form-control" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Fuel On Board FLT 3</label>
                  <input type="text" name="fob_flt_3" class="form-control" autocomplete="off" />
                </div>
              </div>              
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Fuel On Board FLT 4</label>
                  <input type="text" name="fob_flt_4" class="form-control" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">ETD FLT 1 (hh:mm)</label>
                  <input type="text" name="etd_flt_1" id="etd_flt_1" class="form-control" autocomplete="off" />
                </div>
              </div>              
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">ETD FLT 2 (hh:mm)</label>
                  <input type="text" name="etd_flt_2" id="etd_flt_2" class="form-control" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">ETD FLT 3 (hh:mm)</label>
                  <input type="text" name="etd_flt_3" id="etd_flt_3" class="form-control" autocomplete="off" />
                </div>
              </div>              
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">ETD FLT 4 (hh:mm)</label>
                  <input type="text" name="etd_flt_4" id="etd_flt_4" class="form-control" autocomplete="off" />
                </div>
              </div>
            </div>            
            <div id="results"></div><div id="button"></div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>
  <!-- END Page Content -->
  </form>
</main>
<?php require_once 'footer.php' ?>

<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="flight-plan.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
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
      url:"flight-plan-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="flight-plan.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });  
});  

$(document).ready(function() {

  $("#flight_plan_date").attr("maxlength", 11);
  $("#flight_plan_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });

  $("#etd_flt_1").attr("maxlength", 5);
  $("#etd_flt_1").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });  

  $("#etd_flt_2").attr("maxlength", 5);
  $("#etd_flt_2").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });

  $("#etd_flt_3").attr("maxlength", 5);
  $("#etd_flt_3").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });

  $("#etd_flt_4").attr("maxlength", 5);
  $("#etd_flt_4").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });  

}); 
</script>

