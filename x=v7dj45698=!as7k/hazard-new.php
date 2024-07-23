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
                    <form action="hazard.php" method="post">
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
                    <form action="hazard.php" method="post">
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
        <h3 class="block-title">Create New Hazard <?php if(@$ntf[1] <>'') { ?>. AFML Page No.: <?php echo $ntf[1]; } ?></h3>
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
            <input type="hidden" class="form-control" name="afml_page_no" value="<?php echo @$ntf[1] ?>" />
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
                  <label class="bmd-label-floating">Prob/Vulner</label>
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
                  <label class="bmd-label-floating">Sev</label>
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

