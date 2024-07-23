<?php 
require_once 'header.php';
$sql  = "SELECT aircraft_reg_code,aircraft_master_id FROM tbl_aircraft_master WHERE aircraft_master_id = '".$ntf[1]."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
?>


<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Upload New Certificate for <?php echo $row['aircraft_reg_code'] ?></h3>
      </div>
      <div class="block-content">
                
        <div class="card-body">
          <form id="form_simpan">
            <input type="hidden" name="aircraft_master_id" value="<?php echo $row['aircraft_master_id'] ?>">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Certificate Type</label>
                  <select class="form-control" name="aircraft_document_master_id">
                    <option value="">--Choose--</option>
                    <?php
                    $sql  = "SELECT * FROM tbl_aircraft_document_master ORDER BY aircraft_document_master_name";
                    $h    = mysqli_query($conn,$sql);
                    while($row1 = mysqli_fetch_assoc($h)) {
                    ?>
                     <option value="<?php echo $row1['aircraft_document_master_id'] ?>"><?php echo $row1['aircraft_document_master_name'] ?></option> 
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Date Published</label>
                  <input type="text" class="form-control" id="start_date" name="start_date" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">End Date</label>
                  <input type="text" class="form-control" id="end_date" name="end_date" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-12">Choose Image (Format JPG, BMP, GIF, PNG, PDF)</label>
                  <div class="col-12">
                    <input type="file" name="upload_file" id="aircraft_document_file">
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

    <!--certificate-->
    <div class="block table-responsive">
      <div class="block-header block-header-default">
          <h3 class="block-title">Uploaded Certificate</h3>
      </div>
      <div class="block-content">
        <?php
        $sql_cert = "SELECT aircraft_document_id,aircraft_document_master_id,aircraft_document_master_name, aircraft_document_file, date_format(start_date, '%d/%m/%Y') as start_date, date_format(end_date, '%d/%m/%Y') as end_date FROM tbl_aircraft_document a INNER JOIN tbl_aircraft_document_master USING (aircraft_document_master_id) WHERE aircraft_master_id = '".$ntf[1]."'";
        $h_cert   = mysqli_query($conn,$sql_cert);
        if(mysqli_num_rows($h_cert)>0) {
        ?>
        <table class="table table-sm table-vcenter">
          <thead>
            <tr>
              <th>Name</th>
              <th>Date Published</th>
              <th>End Date</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row_cert = mysqli_fetch_assoc($h_cert)) { ?>
            <form id="form_edit<?php echo $row_cert['aircraft_document_id'] ?>">
              <input type="hidden" name="t" value="8293eudjk">
              <input type="hidden" name="aircraft_document_id" value="<?php echo $row_cert['aircraft_document_id'] ?>"> 
              <tr>
                <td><?php echo $row_cert['aircraft_document_master_name'] ?></td>
                <td><?php echo $row_cert['start_date'] ?></td>
                <td><?php echo $row_cert['end_date'] ?></td>
                <td class="text-center">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete" id="edit_data<?php echo $row_cert['aircraft_document_id'] ?>"><i class="fa fa-close"></i>
                        </button>
                        <div id="results_edit<?php echo $row_cert['aircraft_document_id'] ?>"></div>
                    </div>
                </td>
              </tr>
              <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
              <script>
              //edit data
              $(document).ready(function(){  
                $('#edit_data<?php echo $row_cert['aircraft_document_id'] ?>').click(function(){  
                  $('#form_edit<?php echo $row_cert['user_aircraft_document_id'] ?>').submit(); 
                  $("#results_edit<?php echo $row_cert['aircraft_document_id'] ?>").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
                });  
                $('#form_edit<?php echo $row_cert['aircraft_document_id'] ?>').on('submit', function(event){
                  $("#results_edit<?php echo $row_cert['aircraft_document_id'] ?>").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');   
                  event.preventDefault();  
                  $.ajax({  
                    url:"aircraft-certificate-delete.php",  
                    method:"POST",  
                    data:new FormData(this),  
                    contentType:false,  
                    processData:false,  
                    success:function(data){ 
                      $('#results_edit<?php echo $row_cert['aircraft_document_id'] ?>').html(data);  
                      $('#edit_data<?php echo $row_cert['aircraft_document_id'] ?>').val('');  
                    }  
                  });  
                });  
              });                         
              </script>            
              <?php } ?>
            </form>
          </tbody>
        </table>
      <?php }else{ echo 'No data yet'; } mysqli_free_result($rs_result); ?>
      </div>
    </div>
    <!-- END Page Content -->
  </div>
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="aircraft.php"><i class="si si-arrow-left mr-5"></i>Back</a> <a href="aircraft-photo.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-camera mr-5"></i>Photos</a> <a href="aircraft-certificate.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-bag mr-5"></i>Certificate</a> <a href="aircraft-parts.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-wrench mr-5"></i>Parts</a>');  
  $('#submit_data').change(function(){  
    $('#form_simpan').submit(); 
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>'); 
  });  
  $('#form_simpan').on('submit', function(event){
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>');   
    event.preventDefault();  
    $.ajax({  
      url:"aircraft-certificate-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="aircraft.php"><i class="si si-arrow-left mr-5"></i>Back</a> <a href="aircraft-photo.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-camera mr-5"></i>Photos</a> <a href="aircraft-certificate.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-bag mr-5"></i>Certificate</a> <a href="aircraft-parts.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_master_id"]?>-94dfvj!sdf-349ffuaw" class="btn btn-warning mr-5 mb-5"><i class="si si-wrench mr-5"></i>Parts</a>');  
      }  
    });  
  });

  $("#start_date").attr("maxlength", 10);
  $("#start_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });

  $("#end_date").attr("maxlength", 10);
  $("#end_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });

});
</script>