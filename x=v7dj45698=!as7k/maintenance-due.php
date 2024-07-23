<?php 
require_once 'header.php';
//require_once 'components.php';
?>

<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">    
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Maintenance Due List</h3>
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
                    <form method="post" action="maintenance-detail.php">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Select Aircraft</label>
                                    <select required name="aircraft_master_id">
                                    <?php
                                    $sql  = "SELECT * FROM tbl_aircraft_master WHERE client_id = '".$_SESSION['client_id']."' ORDER BY aircraft_reg_code";
                                    $h    = mysqli_query($conn,$sql);
                                    while($row1 = mysqli_fetch_assoc($h)) {
                                    ?>
                                        <option value="<?php echo $row1['aircraft_master_id'] ?>"><?php echo $row1['aircraft_reg_code'] ?></option> 
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="bmd-label-floating">View projection in</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">                            
                                    <input type="text" class="form-control" name="due_value" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">                            
                                    <select required name="projection_type">
                                        <option value="M">MOS</option>
                                        <option value="H">HRS</option>
                                        <option value="L">LDG</option>
                                    </select>                                    
                                </div>
                            </div> 
                        </div>
                        <input type="submit" class="btn btn-success mr-5 mb-5" value="View" />
                        <!--<div id="results"></div><div id="button"></div>
                        <div class="clearfix"></div>-->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-eye mr-5"></i>View</button>');  
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
      url:"maintenance-detail.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-eye mr-5"></i>View</button>');  
      }  
    });  
  });  
});
</script>