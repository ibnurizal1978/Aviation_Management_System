<?php
require_once 'header.php';
$sql  = "SELECT *,CURDATE(), DATEDIFF(safety_finding_target_date, CURDATE()) AS selisih,safety_finding_id,safety_finding_no,safety_finding_status,date_format(safety_finding_target_date, '%d/%m/%Y') AS safety_finding_target_date,date_format(created_date, '%d/%m/%Y') AS created_date FROM tbl_safety_finding WHERE client_id = '".$_SESSION['client_id']."' AND safety_finding_id = '".$ntf[1]."' LIMIT 1";
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
        <h3 class="block-title">AUDIT/SURVEILLANCE FINDING</h3>
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
          <table class="table table-sm table-vcenter table-bordered">
            <tr>
              <td colspan="2" style="background: #E6E6E6"><h3>PART I - FINDING SECTION</h3></td>
            </tr>
            <tr>
              <td width="50%"><b>COMPANY:</b> <?php echo $_SESSION['client_name'] ?></td>
              <td><b>FINDING NO:</b> <?php echo $row['safety_finding_no'] ?></td>
            </tr>
            <tr>
              <td width="50%"><b>LOCATION:</b> <?php echo $row['safety_finding_location'] ?></td>
              <td><b>AREA:</b> <?php echo $row['safety_finding_area'] ?></td>
            </tr>
            <tr>
              <td width="50%"><b>REFERENCE:</b> <?php echo $row['safety_finding_reference'] ?></td>
              <td><b>TYPE OF FINDING:</b> <?php echo $row['safety_finding_type'] ?></td>
            </tr>
            <tr>
              <td colspan="3"><b>FINDING:</b><br/><br/><?php echo $row['safety_finding_title'] ?><br/><br/><br/><br/><br/><br/></td>
            </tr>
            <tr>
              <td colspan="3"><b>CORRECTIVE ACTION PLAN:</b><br/><br/><?php echo $row['safety_finding_description'] ?><br/><br/><br/><br/><br/><br/></td>
            </tr>
          </table>
          <table class="table table-sm table-vcenter table-bordered">
            <tr>
              <td><b>Auditor/Inspector:</b> <?php echo $_SESSION['full_name'] ?></td>
              <td><b>Date:</b> <?php echo $row['created_date'] ?></td>
              <td rowspan="3" align="center"><b>Target Completion Date:</b><br/>                                 
                <?php
                if($row['selisih']<3) {
                    echo '<span class="text-danger">'.$row['safety_finding_target_date'].'</span>';
                }else{
                    echo '<span class="text-success">'.$row['safety_finding_target_date'].'</span>';
                }
                echo '<br/><b>Status</b>&nbsp;';
                if($row['safety_finding_status']=='OPEN') {
                  echo '<span class="badge badge-danger">OPEN</span>';
                }else{
                  echo '<span class="badge badge-success">CLOSED</span>';
                }                
                ?>
              </td>
            </tr>
            <tr>
              <td><b>Auditee Operation/Maintenance:</b> <?php echo $row['safety_finding_target_full_name'] ?></td>
              <td><b>Date:</b></td>
            </tr>
            <tr>
              <td><b>Audit Manager/Team Leader:</b> <?php echo $row['safety_finding_team_leader_full_name'] ?></td>
              <td><b>Date:</b> <?php echo $row['created_date'] ?></td>
            </tr>
          </table>

          <?php if($row['safety_finding_status']=='OPEN' && $row['reply_status']==0) { ?>
          <form id="form_simpan" enctype="multipart/form-data">
            <input type="hidden" name="safety_finding_id" value="<?php echo $row['safety_finding_id'] ?>">
            <table class="table table-sm table-vcenter table-bordered">
              <tr>
                <td style="background-color: #E6E6E6"><b>PART II - CORRECTIVE SECTION</b></td>
              </tr>
              <tr>
                <td>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="bmd-label-floating"><b>Corrective Action</b><span class="text-danger">*</span></label>
                        <textarea class="form-control" name="safety_finding_response_corrective_action" rows="3" autocomplete="off"></textarea>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="col-12"><b>Choose Proof File (Format JPG, BMP, GIF, PNG, PDF, XLS, DOC)</b></label>
                        <div class="col-12">
                          <input type="file" name="upload_file" />
                        </div>
                      </div>
                    </div>
                  </div>                                  
                  <div id="results"></div><div id="button"></div>
                  <div class="clearfix"></div>
                </td>
              </tr>
            </table>
          </form>
          <?php 
          }else{
          $sql2 = "SELECT *,date_format(created_date, '%d/%m/%Y') AS created_date FROM tbl_safety_finding_response WHERE safety_finding_id = '".$row['safety_finding_id']."' LIMIT 1";
          $h2   = mysqli_query($conn,$sql2);
          $row2 = mysqli_fetch_assoc($h2);
          ?>
          <table class="table table-sm table-vcenter table-bordered">
            <tr>
              <td><h3>PART II - CORRECTIVE SECTION</h3></td>
            </tr>
            <tr>
              <td><b>Corrective Action:</b><br/><br/><br/> <?php echo $row2['safety_finding_response_corrective_action'] ?><br/><br/><br/><br/><br/><br/></td>
            </tr>
            <tr>
              <td><b>File:</b> <a href="<?php echo $base_url ?>uploads/safety/<?php echo $row2['safety_finding_response_file'] ?>" target="_blank"><i class="si si-paper-clip"></i></a></td>
            </tr>
            <tr>
              <td>Auditee Operation/Maintenance: <?php echo $row2['created_full_name'] ?><br/>
                Date: <?php echo $row2['created_date'] ?>
              </td>
            </tr>
            <tr>
              <td>Verified by Auditor: <?php echo $row['safety_finding_team_leader_full_name'] ?><br/>
                Date <?php echo $row2['created_date'] ?>
              </td>
            </tr>
            <tr>
              <td><a class="btn btn-info mr-5 mb-5" href="safety-finding-print.php?act=29dvi59&ntf=29dvi59-<?php echo $row["safety_finding_id"]?>-94dfvj!sdf-349ffuaw"><i class="si si-printer mr-5"></i>Print</a></td>
            </tr>
            <tr>
              <td>
                <?php if($_SESSION['department_id']==7) { ?>
                <form id="form_simpan2">
                  <input type="hidden" name="safety_finding_id" value="<?php echo $row['safety_finding_id'] ?>">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="col-12"><b>Closed this finding?</b></label>
                        <select class="form-control" required name="safety_finding_status">
                          <option value=""> -- Choose -- </option>
                          <option value="OPEN">STILL OPEN</option>
                          <option value="CLOSED">CLOSED</option>
                        </select>
                      </div>
                    </div>
                  </div>                                  
                  <div id="results2"></div><div id="button2"></div>
                  <div class="clearfix"></div>
                </form>
                <?php } ?>
              </td>
            </tr>                
          </table>
          <?php } ?>          
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
      url:"safety-finding-detail-add.php",  
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

$(document).ready(function(){
  $("#button2").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data2"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="safety-finding.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
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
      url:"safety-finding-detail-update.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results2').html(data);  
        $('#submit_data2').val('');
        $("#button2").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data2"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="safety-finding.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });  
}); 
</script>

