<?php 
require_once 'header.php';
$sql  = "SELECT *,date_format(client_created_date, '%d/%m/%Y') as client_created_date,date_format(client_expiry_date, '%d/%m/%Y') as client_expiry_date FROM tbl_client WHERE client_id = '".$ntf[1]."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
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
                    <form action="client.php" method="post">
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
                    <form action="client.php" method="post">
                        <div class="form-group mb-15">
                            <div class="form-material floating">
                                <select class="form-control" id="material-select2" name="s">
                                    <option></option><!-- Empty value for demostrating material select box -->
                                    <option value="1">By Name A-Z</option>
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
        <h3 class="block-title">Client Detail</h3>
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
            <input type="hidden" name="client_id" value="<?php echo $row['client_id'] ?>">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Client Name</label>
                  <input type="text" class="form-control" name="client_name" value="<?php echo $row['client_name'] ?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Code</label>
                  <input type="text" class="form-control" name="client_code" value="<?php echo $row['client_code'] ?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Email Address</label>
                  <input type="email" class="form-control" name="client_email_address" value="<?php echo $row['client_email_address'] ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Created on</label>
                  <input type="text" class="form-control" name="" value="<?php echo $row['client_created_date'] ?>">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Address</label>
                  <input type="text" class="form-control" name="client_address" value="<?php echo $row['client_address'] ?>">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Phone Number</label>
                  <input type="text" class="form-control" name="client_phone" value="<?php echo $row['client_phone'] ?>">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Package</label>
                  <select class="form-control" required name="client_package">
                    <?php if($row['client_package']=='Hore') { ?>
                      <option value="Hore" selected="selected">Hore</option>
                      <option value="Yea">Yea</option>
                      <option value="Huu">Huu</option>
                    <?php }elseif($row['client_package']=='Yea'){ ?>
                      <option value="Hore">Hore</option>
                      <option value="Yea" selected="selected">Yea</option>
                      <option value="Huu">Huu</option>
                    <?php }else{ ?>
                      <option value="Hore">Hore</option>
                      <option value="Yea">Yea</option>
                      <option value="Huu" selected="selected">Huu</option>
                    <?php } ?>                        
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Active Status</label>
                  <select class="form-control" required name="client_active_status">
                    <?php if($row['client_active_status']=='Y') { ?>
                      <option value="Y" selected="selected">Yes</option>
                      <option value="N">No</option>
                    <?php }else{ ?>
                      <option value="Y">Yes</option>
                      <option value="N" selected="selected">No</option>
                    <?php } ?>                        
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Expired at</label>
                  <input type="text" class="form-control" name="" value="<?php echo $row['client_expiry_date'] ?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Billing Status</label>
                  <?php if($row['client_billing_status']==1) { ?>
                  <input type="text" class="form-control" value="PAID">
                  <?php }else{ ?>
                  <input type="text" class="form-control" value="UNPAID">
                  <?php } ?>  
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
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="client.php"><i class="si si-arrow-left mr-5"></i>Back</a> <a href="client-billing.php?act=29dvi59&ntf=29dvi59-<?php echo $row["client_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="fa fa-dollar mr-5"></i>Billing</a>');  
  $('#submit_data').click(function(){  
    $('#form_simpan').submit(); 
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
  });  
  $('#form_simpan').on('submit', function(event){
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');  
    event.preventDefault();  
    $.ajax({  
      url:"client-edit.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="client.php"><i class="si si-arrow-left mr-5"></i>Back</a> <a href="client-billing.php?act=29dvi59&ntf=29dvi59-<?php echo $row["client_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="fa fa-dollar mr-5"></i>Billing</a>');  
      }  
    });  
  });  
});  
</script>