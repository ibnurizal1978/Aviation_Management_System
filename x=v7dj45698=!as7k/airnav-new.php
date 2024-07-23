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
                    <form action="avtur.php" method="post">
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
                    <form action="avtur.php" method="post">
                        <div class="form-group mb-15">
                            <div class="form-material floating">
                                <select class="form-control" id="material-select2" name="s">
                                    <option></option><!-- Empty value for demostrating material select box -->
                                    <option value="k">By Date</option>
                                    <option value="1">By Price</option>
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
        <h3 class="block-title">Upload Airnav Price - Invoice</h3>
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
                  <label class="col-12" for="example-file-input">Choose File (Format XLS)</label>
                  <input type="file" name="upload_file" id="upload_user_photo">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Invoice Number</label>
                  <input type="text" class="form-control" name="airnav_invoice_number" autocomplete="off" />
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
    <br/><br/>
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Upload Airnav Price - Terminal</h3>
      </div>
      <div class="block-content">
                
        <div class="card-body">
          <form id="form_simpan2">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="col-12" for="example-file-input">Choose File (Format XLS)</label>
                  <input type="file" name="upload_file" id="upload_user_photo2">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Invoice Number</label>
                  <input type="text" class="form-control" name="airnav_invoice_number" autocomplete="off" />
                </div>
              </div>              
            </div>
            <div id="results2"></div><div id="button2"></div>
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
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Upload</button>');  
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
      url:"airnav-invoice-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Upload</button>');  
      }  
    });  
  });  


});

$(document).ready(function(){
  $("#button2").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data2"><i class="fa fa-check mr-5"></i>Upload</button>');  
  $('#submit_data2').click(function(){  
    $('#form_simpan2').submit(); 
    $("#results2").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button2").html('<button type="submit" class="btn btn-success pull-right" id="submit_data2">Loading...</button>'); 
  });  
  $('#form_simpan2').on('submit', function(event){
    $("#results2").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button2").html('<button type="submit" class="btn btn-success pull-right" id="submit_data2">Loading...</button>');   
    event.preventDefault();  
    $.ajax({  
      url:"airnav-terminal-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results2').html(data);  
        $('#submit_data2').val('');
        $("#button2").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data2"><i class="fa fa-check mr-5"></i>Upload</button>');  
      }  
    });  
  });
});  
</script>
