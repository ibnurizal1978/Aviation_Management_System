<?php 
require_once 'header.php';
$sql  = "SELECT *,date_format(user_birth_date, '%d/%m/%Y') as user_birth_date, date_format(user_last_login, '%d/%m/%Y') as user_last_login FROM tbl_user WHERE user_id = '".$ntf[1]."' LIMIT 1";
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
                    <form action="user.php" method="post">
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
                    <form action="user.php" method="post">
                        <div class="form-group mb-15">
                            <div class="form-material floating">
                                <select class="form-control" id="material-select2" name="s">
                                    <option></option><!-- Empty value for demostrating material select box -->
                                    <option value="1">By Name A-Z</option>
                                    <option value="2">By Department</option>
                                    <option value="3">By Active Status</option>
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
        <h3 class="block-title">User</h3>
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
            <input type="hidden" name="user_id" value="<?php echo $row['user_id'] ?>">
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
                  <input type="text" class="form-control" name="user_position" value="<?php echo $row['user_position'] ?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Username</label>
                  <input type="text" class="form-control" name="username" value="<?php echo $row['username'] ?>">
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
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Date of Birth</label>
                  <input type="text" class="form-control" name="user_birth_date" id="user_birth_date" value="<?php echo $row['user_birth_date'] ?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Last Login</label>
                  <input type="text" class="form-control" value="<?php echo $row['user_last_login'] ?>">
                </div>
              </div> 
            </div>     
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Manager</label>
                  <select class="btn-primary" data-style="select-with-transition" required name="user_manager_id" style="padding-left: 5px; padding-right: 5px">
                    <option value="0"> -- None -- </option>
                    <?php
                    $sql1  = "SELECT user_id,full_name,user_manager_id FROM tbl_user where client_id = '".$_SESSION['client_id']."' AND user_manager_id = 0 ORDER BY full_name";
                    $h1    = mysqli_query($conn,$sql1);
                    while($row1 = mysqli_fetch_assoc($h1)) {
                      if($row['user_manager_id']==$row1['user_id']) {
                    ?>
                    <option value="<?php echo $row1['user_id'] ?>" selected="selected"><?php echo $row1['full_name'] ?></option>
                    <?php }else{ ?>
                    <option value="<?php echo $row1['user_id'] ?>"><?php echo $row1['full_name'] ?></option> 
                    <?php }} ?>
                  </select>
                </div>
              </div>                             
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Department</label>
                  <select class="btn-primary" data-style="select-with-transition" required name="department_id" style="padding-left: 5px; padding-right: 5px">
                      <?php
                      $sql  = "SELECT * FROM tbl_department where client_id = '".$_SESSION['client_id']."' ORDER BY department_name";
                      $h    = mysqli_query($conn,$sql);
                      while($row1 = mysqli_fetch_assoc($h)) {
                        if($row['department_id']==$row1['department_id']) {
                      ?> 
                        <option value="<?php echo $row1['department_id'] ?>" selected="selected"><?php echo $row1['department_name'] ?></option>
                      <?php }else{ ?>
                       <option value="<?php echo $row1['department_id'] ?>"><?php echo $row1['department_name'] ?></option> 
                      <?php }} ?>
                    </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Active Status</label>
                    <select class="btn-primary" required name="user_active_status" style="padding-left: 5px; padding-right: 5px">
                      <?php if($row['user_active_status']==1) { ?>
                        <option value="1" selected="selected">Yes</option>
                        <option value="0">No</option>
                      <?php }else{ ?>
                        <option value="1">Yes</option>
                        <option value="0" selected="selected">No</option>
                      <?php } ?>
                    </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Module Access</label>
                  <br/>
                  <?php
                    $sql2  =" SELECT *, (SELECT count(*) FROM tbl_nav_user x WHERE x.nav_menu_id = y.nav_menu_id AND user_id = '".$row['user_id']."') AS ada FROM tbl_nav_menu y  INNER JOIN tbl_nav_header b USING (nav_header_id) ORDER BY nav_header_name ";
                    $h2    = mysqli_query($conn,$sql2);
                    while($row2 = mysqli_fetch_assoc($h2)) {
                      if($row2['ada']>0){
                    ?> 
                    <input type="checkbox" name="nav_menu_id[]" checked value="<?php echo $row2['nav_menu_id'] ?>"><i class="dark-white"></i> <?php echo $row2['nav_header_name'].' - '.$row2['nav_menu_name'] ?><br/>
                    <?php }else{ ?>
                    <input type="checkbox" name="nav_menu_id[]" value="<?php echo $row2['nav_menu_id'] ?>"><i class="dark-white"></i> <?php echo $row2['nav_header_name'].' - '.$row2['nav_menu_name'] ?><br/>
                    <?php }} ?>
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
      url:"user-edit.php",  
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
    $("#results_user_photo").html('<img width=50 src=<?php echo $base_url ?>assets/img/loading.gif> Uploading...'); 
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
