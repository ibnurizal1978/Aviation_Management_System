<?php 
require_once 'header.php';
if(@$_REQUEST['xv']=='67utyhgfbvwefhnfb') {
  $user_id = $_SESSION['user_id'];
}else{
  $user_id = $ntf[1];
}
  $sql  = "SELECT *,date_format(user_birth_date, '%d/%m/%Y') as user_birth_date, date_format(user_last_login, '%d/%m/%Y') as user_last_login FROM tbl_user WHERE user_id = '".$user_id."' LIMIT 1";
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
        <h3 class="block-title">Add New Certificate for <?php echo $row['full_name'] ?></h3>
      </div>
      <div class="block-content">
                
        <div class="card-body">
          <form id="form_simpan_data" enctype="multipart/form-data">
            <input type="hidden" name="t" value="8293eudjk">
            <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Certificate Name</label>
                    <select class="form-control" data-style="select-with-transition" required name="certificate_master_id" style="padding-left: 5px; padding-right: 5px">
                        <?php
                        $sql  = "SELECT certificate_master_id,certificate_master_name FROM tbl_certificate_master WHERE department_id = '".$_SESSION['department_id']."'";
                        $h    = mysqli_query($conn,$sql);
                        while($row1 = mysqli_fetch_assoc($h)) {
                        ?>
                         <option value="<?php echo $row1['certificate_master_id'] ?>"><?php echo $row1['certificate_master_name'] ?></option> 
                        <?php } ?>
                      </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Date Taken (type in dd/mm/yyyy)</label>
                  <input type="text" class="form-control" id="user_certificate_date" name="user_certificate_date" value="<?php echo $row_cert['user_certificate_date'] ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">End Date (type in dd/mm/yyyy)</label>
                  <input type="text" class="form-control" id="end_date" name="end_date" />
                </div>
              </div>              
              <div class="col-md-3">
                <div class="form-group">
                  <label class="col-12">Browse Certificate (Format JPG, BMP, GIF, PNG, PDF, ZIP, RAR)</label>
                  <div class="col-12">
                      <input type="file" name="upload_file" />
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

    <div class="block table-responsive">
      <div class="block-header block-header-default">
          <h3 class="block-title">Certificates</h3>
      </div>
      <div class="block-content">
        <table class="table table-sm table-vcenter">
          <thead>
            <tr>
              <th>Certificate Name</th>
              <th>Date Taken</th>
              <th>Attachment</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql_cert = "SELECT user_id,certificate_master_id, certificate_master_name, user_certificate_id, user_certificate_file, date_format(user_certificate_date, '%d/%m/%Y') as user_certificate_date, date_format(user_certificate_next, '%d/%m/%Y') as user_certificate_next FROM tbl_certificate_master a INNER JOIN tbl_user_certificate USING (certificate_master_id) WHERE user_id = '".$user_id."'";
            $h_cert   = mysqli_query($conn,$sql_cert);
            if(mysqli_num_rows($h_cert)>0) {
            while($row_cert = mysqli_fetch_assoc($h_cert)) {
            ?>
            <tr>
              <td><?php echo $row_cert['certificate_master_name'] ?></td>
              <td><?php echo $row_cert['user_certificate_date'] ?></td>
              <td><a class="btn btn-info" href="<?php echo $base_url ?>uploads/certificate/<?php echo $row_cert['user_certificate_file'] ?>" target="_blank">View File</a></td>
              <td class="text-center">
                <div class="btn-group">
                  <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete" href="user-engineer-certificate-delete.php?act=29dvi59&ntf=29dvi59-<?php echo $row_cert['user_certificate_id'] ?>-<?php echo $row_cert['user_id'] ?>-94dfvj!sdf-349ffuaw" onclick="return confirm('Are you sure you want to delete this item?');"><i class="si si-close"></i></a>
                </div>
              </td>
            </tr>
            <?php }} mysqli_free_result($h_cert); ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- END Page Content -->
  </div>
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="user-engineer.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
  $('#btn_submit_data').click(function(){  
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>');
  });  
  $('#form_simpan_data').on('submit', function(event){
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>');  
    event.preventDefault();  
    $.ajax({  
      url:"user-engineer-certificate-add.php",  
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
</script>


<script type="text/javascript">
$(document).ready(function() {
  $("#user_certificate_date").attr("maxlength", 10);
  $("#user_certificate_date").keyup(function(){
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
