<?php 
require_once 'header.php';
if(@$_REQUEST['xv']=='67utyhgfbvwefhnfb') {
  $user_id = $_SESSION['user_id'];
}else{
  $user_id = $ntf[1];
}
$sql = "SELECT user_id,full_name FROM tbl_user WHERE user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$rs_result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($rs_result);

$sql2 = "SELECT otr_no,user_otr_id,amel_no,date_format(amel_validity_date, '%d/%m/%Y') as amel_validity_date,date_format(otr_validity_date, '%d/%m/%Y') as otr_validity_date,general_license,aircraft_type,authorized_limitation,engine_run_up,weight_balance,compass_swing,boroscope,stamp_no,rii_stamp,inspector_stamp,remark FROM tbl_user_otr WHERE user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($h2);
?>

<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">OTR Data for <?php echo $row['full_name'] ?></h3>
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
          <form id="form_simpan_data" enctype="multipart/form-data">
            <input type="hidden" name="t" value="8293eudjk">
            <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
            <input type="hidden" name="user_otr_id" value="<?php echo $row2['user_otr_id'] ?>">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">AMEL No.</label>
                  <input type="text" class="form-control" name="amel_no" value="<?php echo $row2['amel_no'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">AMEL Validity Date</label>
                  <input type="text" class="form-control" id="amel_validity_date" name="amel_validity_date" value="<?php echo $row2['amel_validity_date'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">OTR No.</label>
                  <input type="text" class="form-control" id="otr_no" name="otr_no" value="<?php echo $row2['otr_no'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">OTR Validity Date</label>
                  <input type="text" class="form-control" id="otr_validity_date" name="otr_validity_date" value="<?php echo $row2['otr_validity_date'] ?>" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">General License</label>
                  <input type="text" class="form-control" name="general_license" value="<?php echo $row2['general_license'] ?>" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Aircraft Type</label>
                  <input type="text" class="form-control" name="aircraft_type" value="<?php echo $row2['aircraft_type'] ?>" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Authorized Limitation</label>
                  <input type="text" class="form-control" name="authorized_limitation" value="<?php echo $row2['authorized_limitation'] ?>" />
                </div>
              </div>                
            </div> 
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <?php if($row2['engine_run_up']=='Y') { ?>
                    <input type="checkbox" name="engine_run_up" value="Y" checked="checked"><i class="dark-white"></i>
                  <?php }else{ ?>
                    <input type="checkbox" name="engine_run_up" value="Y"><i class="dark-white"></i>
                  <?php } ?>
                  <label class="bmd-label-floating">Engine Run Up</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <?php if($row2['weight_balance']=='Y') { ?>
                    <input type="checkbox" name="weight_balance" value="Y" checked="checked"><i class="dark-white"></i>
                  <?php }else{ ?>
                    <input type="checkbox" name="weight_balance" value="Y"><i class="dark-white"></i>
                  <?php } ?>
                  <label class="bmd-label-floating">Weight Balance</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <?php if($row2['compass_swing']=='Y') { ?>
                    <input type="checkbox" name="compass_swing" value="Y" checked="checked"><i class="dark-white"></i>
                  <?php }else{ ?>
                    <input type="checkbox" name="compass_swing" value="Y"><i class="dark-white"></i>
                  <?php } ?>
                  <label class="bmd-label-floating">Compass Swing</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <?php if($row2['boroscope']=='Y') { ?>
                    <input type="checkbox" name="boroscope" value="Y" checked="checked"><i class="dark-white"></i>
                  <?php }else{ ?>
                    <input type="checkbox" name="boroscope" value="Y"><i class="dark-white"></i>
                  <?php } ?>
                  <label class="bmd-label-floating">Boroscope</label>
                </div>
              </div>                                
            </div>
            <br/>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="bmd-label-floating">Remark</label>
                  <input type="text" class="form-control" name="remark" value="<?php echo $row2['remark'] ?>" />
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

    <!--upload stamp-->
    <div id="results_upload_stamp"></div>
    <form id="form_upload_stamp" enctype="multipart/form-data">
      <input type="hidden" name="t" value="erinf_853549w5jrwenfs">
      <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
      <input type="hidden" name="user_otr_id" value="<?php echo $row2['user_otr_id'] ?>">    
      <div class="block table-responsive">
        <div class="block-header block-header-default">
          <h3 class="block-title">Stamp Data</h3>
        </div>
        <div class="block-content">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="bmd-label-floating">Choose Stamp Type</label>
                <select class="btn-primary" required name="stamp_type" style="padding-left: 5px; padding-right: 5px">
                    <option value="">-- Choose Stamp here --</option>
                    <option value="1">Stamp No</option>
                    <option value="2">RII Stamp</option>
                    <option value="3">Inspector Stamp</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-12">Upload file (Format JPG, BMP, GIF, PNG)</label>
                <div class="col-12">
                    <input type="file" name="upload_file" id="upload_file" />
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <?php if($row2['stamp_no']!='') { ?>
                  <b>Stamp No</b><br/>
                  <a href="<?php echo $base_url ?>uploads/stamp/<?php echo $row2['stamp_no'] ?>" target="_blank"><img src="<?php echo $base_url ?>uploads/stamp/<?php echo $row2['stamp_no'] ?>" width="80" /></a><br><br/>
                <?php }
                if($row2['rii_stamp']<>'') { ?>
                  <b>RII Stamp</b><br/>
                  <a href="<?php echo $base_url ?>uploads/stamp/<?php echo $row2['rii_stamp'] ?>" target="_blank"><img src="<?php echo $base_url ?>uploads/stamp/<?php echo $row2['rii_stamp'] ?>" width="80" /></a><br/><br/>
                <?php }
                if($row2['inspector_stamp']<>'') { ?>
                  <b>Inspector Stamp</b><br/>
                  <a href="<?php echo $base_url ?>uploads/stamp/<?php echo $row2['inspector_stamp'] ?>" target="_blank"><img src="<?php echo $base_url ?>uploads/stamp/<?php echo $row2['inspector_stamp'] ?>" width="80" /></a>
                <?php } ?>
              </div>
            </div>            
          </div>
        </div>
      </div>
    </form>

    <!-- END Page Content -->
  </div>
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="user-engineer.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
  $('#btn_submit_data').click(function(){  
    $('#form_simpan').submit(); 
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>');
  });  
  $('#form_simpan_data').on('submit', function(event){
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>');  
    event.preventDefault();  
    $.ajax({  
      url:"user-engineer-otr-edit.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#btn_submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="user-engineer.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });  
}); 

//upload stamp
$(document).ready(function(){  
  $('#upload_file').change(function(){  
    $('#form_upload_stamp').submit(); 
    $("#results_upload_stamp").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>'); 
  });  
  $('#form_upload_stamp').on('submit', function(event){  
    event.preventDefault();  
    $.ajax({  
      url:"user-engineer-otr-edit-stamp.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results_upload_stamp').html(data);  
        $('#upload_file').val('');  
      }  
    });  
  });  
});

$(document).ready(function() {
  $("#amel_validity_date").attr("maxlength", 10);
  $("#amel_validity_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });

  $("#otr_validity_date").attr("maxlength", 10);
  $("#otr_validity_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });  
});
</script>
