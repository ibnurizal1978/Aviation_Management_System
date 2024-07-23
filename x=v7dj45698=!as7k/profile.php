<?php 
require_once 'header.php';
$sql  = "SELECT *,date_format(user_birth_date, '%d/%m/%Y') as user_birth_date, date_format(user_last_login, '%d/%m/%Y') as user_last_login FROM tbl_user a INNER JOIN tbl_department b USING (department_id) WHERE user_id = '".$_SESSION['user_id']."' AND a.client_id = '".$_SESSION['client_id']."' LIMIT 1";
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
        <h3 class="block-title">User</h3>
      </div>
      <div class="block-content">
                
        <div class="card-body">
          <form id="form_simpan">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Name</label>
                  <input type="text" class="form-control" name="full_name" value="<?php echo $row['full_name'] ?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Position</label>
                  <input type="text" class="form-control" name="user_position" value="<?php echo $row['user_position'] ?>" disabled>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Username</label>
                  <input type="text" class="form-control" name="username" value="<?php echo $row['username'] ?>" disabled>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Home Address</label>
                  <input type="text" class="form-control" name="user_home_address" value="<?php echo $row['user_home_address'] ?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Marital Status</label>
                  <input type="text" class="form-control" name="user_marital_status" value="<?php echo $row['user_marital_status'] ?>">
                </div>
              </div>              
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Email Address</label>
                  <input type="text" class="form-control" name="user_email_address" value="<?php echo $row['user_email_address'] ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Phone</label>
                  <input type="text" class="form-control" name="user_phone" value="<?php echo $row['user_phone'] ?>">
                </div>
              </div>
              <!--<div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Date of Birth</label>
                  <input type="text" class="form-control" name="user_birth_date" id="user_birth_date" value="<?php echo $row['user_birth_date'] ?>" disabled>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Last Login</label>
                  <input type="text" class="form-control" value="<?php echo $row['user_last_login'] ?>" disabled>
                </div>
              </div>--> 
            </div>     
            <div id="results"></div><div id="button"></div>
            <div class="clearfix"></div>
          </form>
        </div>
      </div>
    </div>
    <!-- END Small Table -->


    <!-- change password -->    
    <div class="row">
      <div class="col-md-12 col-xl-12">
        <div class="block">
          <div class="block-header block-header-default">
            <h3 class="block-title">Change Password</h3>
          </div>
          <div class="block-content">
            <p>
              <form id="form_simpan2">
                <input type="hidden" name="user_id" value="<?php echo $row['user_id'] ?>">
                <input type="hidden" name="int" value="1">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="bmd-label-floating">Input New Password</label>
                      <input type="password" class="form-control" name="txt_password" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="bmd-label-floating">Confirm New Password</label>
                      <input type="password" class="form-control" name="txt_password2" />
                    </div>
                  </div>
                </div>
                <div id="results2"></div><div id="button2"></div>
                <div class="clearfix"></div>
              </form>
            </p>
          </div>
        </div>
      </div>
    </div>
    <!-- END change password -->

    <!-- upload gambar -->    
    <div class="row">
      <div class="col-md-3 col-xl-3">
        <div class="block">
          <div class="block-header block-header-default">
            <h3 class="block-title">Upload Your <br/><b>Photo</b></h3>
          </div>
          <div class="block-content">
            <p>
              <form id="export_user_photo" enctype="multipart/form-data">
                <input type="hidden" name="int" value="1">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div id="results_user_photo"></div>
                      <a class="img-link" href="be_pages_generic_profile.html">
                          <img class="img-avatar" src="<?php echo $base_url ?>uploads/user/<?php echo $row['user_photo'] ?>" alt="">
                      </a>                      
                      <label class="col-12" for="example-file-input">Choose Photo (Format JPG, BMP, GIF, PNG)</label>
                      <div class="col-12">
                        <input type="file" name="upload_file" id="upload_user_photo">
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-xl-3">
        <div class="block">
          <div class="block-header block-header-default">
            <h3 class="block-title">Upload Your<br/><b>Signature Only</b></h3>
          </div>
          <div class="block-content">
            <p>
              <form id="export_user_signature" enctype="multipart/form-data">
                <input type="hidden" name="int" value="1">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div id="results_user_signature"></div>
                      <a class="img-link" href="be_pages_generic_profile.html">
                          <img class="img-avatar" src="<?php echo $base_url ?>uploads/user-signature/<?php echo $row['user_signature'] ?>" alt="">
                      </a>                      
                      <label class="col-12" for="example-file-input">Choose Image (Format JPG, BMP, GIF, PNG)</label>
                      <div class="col-12">
                        <input type="file" name="upload_file" id="upload_user_signature">
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </p>
          </div>
        </div>
      </div> 
      <div class="col-md-3 col-xl-3">
        <div class="block">
          <div class="block-header block-header-default">
            <h3 class="block-title">Upload Your<br/><b>Signature + RTS Stamp</b></h3>
          </div>
          <div class="block-content">
            <p>
              <form id="export_user_signature_rts_stamp" enctype="multipart/form-data">
                <input type="hidden" name="int" value="1">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div id="results_user_signature_rts_stamp"></div>
                      <a class="img-link" href="#">
                          <img class="img-avatar" src="<?php echo $base_url ?>uploads/user-signature/<?php echo $row['user_signature_rts_stamp'] ?>" alt="">
                      </a>                      
                      <label class="col-12" for="example-file-input">Choose Image (Format JPG, BMP, GIF, PNG)</label>
                      <div class="col-12">
                        <input type="file" name="upload_file" id="upload_user_signature_rts_stamp">
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-xl-3">
        <div class="block">
          <div class="block-header block-header-default">
            <h3 class="block-title">Upload Your<br/><b>Signature + RII Stamp</b></h3>
          </div>
          <div class="block-content">
            <p>
              <form id="export_user_signature_rii_stamp" enctype="multipart/form-data">
                <input type="hidden" name="int" value="1">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div id="results_user_signature_rii_stamp"></div>
                      <a class="img-link" href=#>
                          <img class="img-avatar" src="<?php echo $base_url ?>uploads/user-signature/<?php echo $row['user_signature_rii_stamp'] ?>" alt="">
                      </a>                      
                      <label class="col-12" for="example-file-input">Choose Image (Format JPG, BMP, GIF, PNG)</label>
                      <div class="col-12">
                        <input type="file" name="upload_file" id="upload_user_signature_rii_stamp">
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </p>
          </div>
        </div>
      </div>                  
    </div>
    <!-- END upload gambar -->

    <!-- END Page Content -->
  </div>
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="user.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
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
      url:"user-edit-individual.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="user.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });  
});

//change password
$(document).ready(function(){
  $("#button2").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data2"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="user.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
  $('#submit_data2').click(function(){  
    $('#form_simpan2').submit(); 
    $("#results2").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button2").html('<button type="submit" class="btn btn-success pull-right" id="submit_data2">Loading...</button>'); 
  });  
  $('#form_simpan2').on('submit', function(event){  
    event.preventDefault();  
    $.ajax({  
      url:"user-change-password.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results2').html(data);  
        $('#submit_data2').val('');
        $("#button2").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data2"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="user.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });
});  

//upload foto profil
$(document).ready(function(){  
  $('#upload_user_photo').change(function(){  
    $('#export_user_photo').submit(); 
    $("#results_user_photo").html('Uploading...'); 
  });  
  $('#export_user_photo').on('submit', function(event){  
    event.preventDefault();  
    $.ajax({  
      url:"user-upload-photo.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results_user_photo').html(data);  
        $('#upload_user_photo').val('');  
      }  
    });  
  });  
});

//upload foto signature
$(document).ready(function(){  
  $('#upload_user_signature').change(function(){  
    $('#export_user_signature').submit(); 
    $("#results_user_signature").html('Uploading...'); 
  });  
  $('#export_user_signature').on('submit', function(event){  
    event.preventDefault();  
    $.ajax({  
      url:"user-upload-signature.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results_user_signature').html(data);  
        $('#upload_user_signature').val('');  
      }  
    });  
  });  
});

//upload foto signature + rts stamp
$(document).ready(function(){  
  $('#upload_user_signature_rts_stamp').change(function(){  
    $('#export_user_signature_rts_stamp').submit(); 
    $("#results_user_signature_rts_stamp").html('Uploading...'); 
  });  
  $('#export_user_signature_rts_stamp').on('submit', function(event){  
    event.preventDefault();  
    $.ajax({  
      url:"user-upload-signature-rts-stamp.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results_user_signature_rts_stamp').html(data);  
        $('#upload_user_signature_rts_stamp').val('');  
      }  
    });  
  });  
});  

//upload foto signature + rii stamp
$(document).ready(function(){  
  $('#upload_user_signature_rii_stamp').change(function(){  
    $('#export_user_signature_rii_stamp').submit(); 
    $("#results_user_signature_rii_stamp").html('Uploading...'); 
  });  
  $('#export_user_signature_rii_stamp').on('submit', function(event){  
    event.preventDefault();  
    $.ajax({  
      url:"user-upload-signature-rii-stamp.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results_user_signature_rii_stamp').html(data);  
        $('#upload_user_signature_rii_stamp').val('');  
      }  
    });  
  });  
});

//date format
$(document).ready(function() {
  $("#user_birth_date").attr("maxlength", 10);

  $("#user_birth_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });

});
</script>
