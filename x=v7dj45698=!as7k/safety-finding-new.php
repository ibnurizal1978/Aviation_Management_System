<?php
require_once 'header.php';
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
                    <form action="safety-finding.php" method="post">
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
                    <form action="safety-finding.php" method="post">
                        <div class="form-group mb-15">
                            <div class="form-material floating">
                                <select class="form-control" id="material-select2" name="s">
                                    <option></option><!-- Empty value for demostrating material select box -->
                                    <option value="1">By Date</option>
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
        <h3 class="block-title">Create New Finding</h3>
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
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Location<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="safety_finding_location" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Area<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="safety_finding_area" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Reference<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="safety_finding_reference" autocomplete="off" />
                </div>
              </div>
              <!--<div class="col-md-2">
                <div class="form-group">
                  <label class="bmd-label-floating"> </label>
                  <select class="form-control" required name="safety_finding_type">
                    <option value=""> -- Choose -- </option>
                    <option value="Audit">Audit</option>
                    <option value="Surveillance Period">Surveillance Period</option>
                  </select>
                </div>
              </div>-->               
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Type of Finding<span class="text-danger">*</span></label>
                  <select class="form-control" required name="safety_finding_type">
                    <option value=""> -- Choose -- </option>
                    <option value="NON COMPLIANCE">NON COMPLIANCE</option>
                    <option value="NON CONFORMANCE">NON CONFORMANCE</option>
                    <option value="NON ADHERENCE">NON ADHERENCE</option>
                    <option value="HIGH">HIGH</option>
                    <option value="MEDIUM">MEDIUM</option>
                    <option value="LOW">LOW</option>
                  </select>
                </div>
              </div>              
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Target Completion Date<span class="text-danger">*</span> (dd/mm/yyyy)</label>
                  <input type="text" class="form-control" id="safety_finding_target_date" name="safety_finding_target_date" autocomplete="off" />
                </div>
              </div>              
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Auditee Operation/Maintenance</label>
                  <select class="form-control" required name="safety_finding_target_user_id" >
                    <option value=""> -- Choose -- </option>
                    <?php
                    $sql1  = "SELECT user_id,full_name,user_manager_id FROM tbl_user where client_id = '".$_SESSION['client_id']."' AND user_manager_id IN ('0') ORDER BY full_name";
                    $h1    = mysqli_query($conn,$sql1);
                    while($row1 = mysqli_fetch_assoc($h1)) {
                    ?>
                    <option value="<?php echo $row1['user_id'] ?>"><?php echo $row1['full_name'] ?></option> 
                    <?php } ?>
                  </select>
                </div>
              </div>
              <!--<div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Audit Manager</label>
                  <select class="form-control" required name="safety_finding_team_leader" >
                    <option value=""> -- Choose -- </option>
                    <?php
                    $sql1  = "SELECT user_id,full_name FROM tbl_user where client_id = '".$_SESSION['client_id']."' AND user_id = '".$_SESSION['user_manager_id']."' ORDER BY full_name";
                    echo $sql1;
                    $h1    = mysqli_query($conn,$sql1);
                    while($row1 = mysqli_fetch_assoc($h1)) {
                    ?>
                    <option value="<?php echo $row1['user_id'] ?>"><?php echo $row1['full_name'] ?></option> 
                    <?php } ?>
                  </select>
                </div>
              </div>-->
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Finding<span class="text-danger">*</span></label>
                  <textarea class="form-control" name="safety_finding_title" rows="3" autocomplete="off"></textarea>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Corrective Action Plan<span class="text-danger">*</span></label>
                  <textarea class="form-control" name="safety_finding_description" rows="3" autocomplete="off"></textarea>
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
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="safety-finding.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
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
      url:"safety-finding-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="safety-finding.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });  
});  

$(document).ready(function() {

  $("#safety_finding_target_date").attr("maxlength", 10);
  $("#safety_finding_target_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });
      
}); 
</script>

