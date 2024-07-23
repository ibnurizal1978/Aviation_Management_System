<?php 
session_start();
require_once '../config.php';
require_once 'components.php';
if($_POST['afml_page_no'] == '') {
  $aircraft_reg_code = $ntf[1].'-'.$ntf[2];
  $afml_page_no       = $ntf[3];
  $afml_id            = $ntf[4];
}else{
  $aircraft_reg_code = $_POST['aircraft_reg_code'];
  $afml_page_no       = $_POST['afml_page_no'];
  $afml_id          = $_POST['afml_id'];  
}
$sql  = "SELECT *,date_format(manufacture_date, '%d/%m/%Y') as manufacture_date FROM tbl_aircraft_master a INNER JOIN tbl_aircraft_type b USING (aircraft_type_id) WHERE aircraft_reg_code = '".$aircraft_reg_code."' LIMIT 1";

$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
$aircraft_master_id = $row['aircraft_master_id'];
?>

<h3 class="block-title">Change Components for <?php echo $row['aircraft_reg_code'] ?></h3>
<a href="afml-detail-new.php?act=29dvi59&ntf=29dvi59-<?php echo $afml_id ?>-94dfvj!sdf-349ffuaw">Back to AFML</a>
<br/><br/>
<?php
if(@$_POST['s']=='1091vdf8ame151') {
  $txt_search   = input_data(filter_var($_REQUEST['txt_search'],FILTER_SANITIZE_STRING));
  $aircraft_master_id   = input_data(filter_var($_POST['aircraft_master_id'],FILTER_SANITIZE_STRING));
  $sql = "SELECT *,date_format(installed_date, '%d/%m/%Y') as installed_date FROM tbl_aircraft_parts WHERE (description LIKE '%$txt_search%' OR part_number LIKE '%$txt_search%' OR serial_number LIKE '%$txt_search%' OR item_number LIKE '%$txt_search%') AND aircraft_master_id ='".$aircraft_master_id."' AND client_id = '".$_SESSION['client_id']."'";     
  $rs_result = mysqli_query($conn, $sql);
}
if(mysqli_num_rows($rs_result)==0) {
?>
  <p class="text-center text-danger" role="alert">Oops, no data found, or you not yet search component</p>
<?php  
//exit(); 
}
?>


<?php if(@$_GET['act']=='79dvi59g') { ?>
  <div class="alert alert-danger alert-dismissable" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
      <p class="mb-0">Please fill part number and serial number</p>
  </div>
<?php } ?> 

<?php if(@$_GET['act']=='79dvi5s9g') { ?>
  <div class="alert alert-danger alert-dismissable" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
      <p class="mb-0">Old and new serial number cannot similar</p>
  </div>
<?php } ?> 

<?php if(@$_GET['act']=='79djvi59g') { ?>
  <div class="alert alert-danger alert-dismissable" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
      <p class="mb-0">New serial number already in database</p>
  </div>
<?php } ?> 

<?php if(@$_GET['act']=='29dvi59') { ?>
  <div class="alert alert-success alert-dismissable" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
      <p class="mb-0">Data successfully updated</p>
  </div>
<?php } ?>



<form action="afml-component-change.php" method="POST">
  <input type="hidden" name="s" value="1091vdf8ame151" />
  <input type="hidden" name="afml_id" value="<?php echo $afml_id ?>" />
  <input type="hidden" name="afml_page_no" value="<?php echo $afml_page_no ?>" />
  <input type="hidden" name="aircraft_reg_code" value="<?php echo $aircraft_reg_code ?>" />
  <input type="hidden" name="aircraft_master_id" value="<?php echo $aircraft_master_id ?>" />
  Find component here <input type="text" name="txt_search" />
  <input type="submit" class="btn btn-success mr-5 mb-5" value="Find" />
</form>
<br/><br/>
<table class="table table-sm table-vcenter">
  <thead>
    <tr>
      <th>Item No</th>
      <th>Position</th>
      <th>Description</th>
      <th>Part No.</th>
      <th>Serial No.</th>
      <th>Installed Date</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
      <tr>
        <td><?php echo $row['item_number'] ?></td>
        <td><?php echo $row['position'] ?></td>
        <td><?php echo $row['description'] ?></td>
        <td><?php echo $row['part_number'] ?></td>
        <td><?php echo $row['serial_number'] ?></td>
        <td><?php echo $row['installed_date'] ?></td>
        <td><a class="btn btn-success mr-5 mb-5" data-toggle="modal" data-target="#modal-compose<?php echo $row['aircraft_parts_id'] ?>">Change</a></td>

        <!-- Compose Modal -->
        <div class="modal fade" id="modal-compose<?php echo $row['aircraft_parts_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modal-compose" aria-hidden="true">
          <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
              <div class="block block-themed block-transparent mb-0">
                <div class="block-header">
                  <h3 class="block-title">
                    <i class="fa fa-pencil mr-5"></i> Change Parts <?php echo $row['description'] ?> for <?php echo $aircraft_reg_code ?>
                  </h3>
                  <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                  </div>
                </div>
                <div class="block-content">
                  <form method="POST" action="afml-component-change-add.php">
                    <input type="hidden" name="afml_page_no" value="<?php echo $afml_page_no ?>" />
                    <input type="hidden" name="aircraft_parts_id" value="<?php echo $row['aircraft_parts_id'] ?>" />
                    <input type="hidden" name="aircraft_reg_code" value="<?php echo $aircraft_reg_code ?>" />
                    <input type="hidden" name="aircraft_master_id" value="<?php echo $aircraft_master_id ?>" />
                    <input type="hidden" name="old_part_number" value="<?php echo $row['part_number'] ?>" />
                    <input type="hidden" name="old_serial_number" value="<?php echo $row['serial_number'] ?>" />
                    <input type="hidden" name="position" value="<?php echo $row['position'] ?>" />
                    <input type="hidden" name="description" value="<?php echo $row['description'] ?>" />
                    <input type="hidden" name="afml_id" value="<?php echo $afml_id ?>" />
                    <div class="form-group row">
                      <div class="col-12">
                        <div class="form-material form-material-primary input-group">
                          <input type="text" class="form-control"  name="new_part_number" />
                          <label for="message-subject" class="bmd-label-floating">Type New P/N Here (Current P/N is <?php echo $row['part_number'] ?>)</label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-12">
                        <div class="form-material form-material-primary input-group">
                          <input type="text" class="form-control" name="new_serial_number" />
                          <label for="message-subject" class="bmd-label-floating">Type New S/N Here (Current S/N is <?php echo $row['serial_number'] ?>)</label>
                        </div>
                      </div>                                  
                    </div>
                    <div class="form-group row">
                      <div class="col-12">
                        <div class="form-material form-material-primary input-group">
                          <input type="text" class="form-control" name="reason" />
                          <label for="message-subject" class="bmd-label-floating">Reason for Removal</label>
                        </div>
                      </div>                                  
                    </div>                    
                    <br/><br/>
                    <input type="submit" class="btn btn-success mr-5 mb-5" value="Save" /> <a class="btn btn-info mr-5 mb-5 text-white" data-dismiss="modal"><i class="si si-close"></i> Close</a>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--end modal-->
      </tr>           
      <?php } mysqli_free_result($rs_result);  ?>
  </tbody>
</table>
 <!-- END Main Container -->
<?php require_once 'footer.php' ?>
<script>
$(document).ready(function(){  
  $('#upload_file').change(function(){  
    $('#form_simpan').submit(); 
    $("#results").html('<img width=50 src=<?php echo $base_url ?>assets/img/loading.gif> Uploading...'); 
  });  
  $('#form_simpan').on('submit', function(event){  
    event.preventDefault();  
    $.ajax({  
      url:"aircraft-parts-upload.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#upload_file').val('');  
      }  
    });  
  });  
}); 
</script>