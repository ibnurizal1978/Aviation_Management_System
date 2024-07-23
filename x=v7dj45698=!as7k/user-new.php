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
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="bmd-label-floating">Name</label>
                    <input type="text" class="form-control" name="full_name" autocomplete="off" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="bmd-label-floating">Position</label>
                    <input type="text" class="form-control" name="user_position" autocomplete="off" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="bmd-label-floating">Username</label>
                    <input type="text" class="form-control" name="username" />
                  </div>
                </div>                
              </div>
              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label class="bmd-label-floating">Home Address</label>
                    <input type="text" class="form-control" name="user_home_address" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="bmd-label-floating">Email Address</label>
                    <input type="text" class="form-control" name="user_email_address" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Phone</label>
                    <input type="text" class="form-control" name="user_phone" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Date of Birth</label>
                    <input type="text" class="form-control" name="user_birth_date" id="user_birth_date" />
                  </div>
                </div>                
              </div>                            
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Password</label>
                    <input type="password" class="form-control" name="txt_password" autocomplete="off" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Confirm Password</label>
                    <input type="password" class="form-control" name="txt_password2" autocomplete="off" />
                  </div>
                </div>                
              </div>
              <p>&nbsp;</p>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="bmd-label-floating">Manager</label>
                    <select class="btn-primary" data-style="select-with-transition" required name="user_manager_id" style="padding-left: 5px; padding-right: 5px">
                      <option value="0"> -- None -- </option>
                      <?php
                      $sql  = "SELECT user_id,full_name FROM tbl_user where client_id = '".$_SESSION['client_id']."' AND user_manager_id = 0 ORDER BY full_name";
                      $h    = mysqli_query($conn,$sql);
                      while($row1 = mysqli_fetch_assoc($h)) {
                      ?>
                       <option value="<?php echo $row1['user_id'] ?>"><?php echo $row1['full_name'] ?></option> 
                      <?php } ?>
                    </select>
                  </div>
                </div>                
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="bmd-label-floating">Department</label>
                    <select class="btn-primary" data-style="select-with-transition" required name="department_id" style="padding-left: 5px; padding-right: 5px">
                        <?php
                        $sql  = "SELECT * FROM tbl_department where client_id = '".$_SESSION['client_id']."' ORDER BY department_name";
                        $h    = mysqli_query($conn,$sql);
                        while($row1 = mysqli_fetch_assoc($h)) {
                        ?>
                         <option value="<?php echo $row1['department_id'] ?>"><?php echo $row1['department_name'] ?></option> 
                        <?php } ?>
                      </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="bmd-label-floating">Module Access</label>
                    <br/>
                    <?php
                    $sql  = "SELECT * FROM tbl_nav_menu y  INNER JOIN tbl_nav_header b USING (nav_header_id) ORDER BY nav_header_name";
                    $h    = mysqli_query($conn,$sql);
                    while($row1 = mysqli_fetch_assoc($h)) {
                    ?> 
                    <input type="checkbox" name="nav_menu_id[]"  value="<?php echo $row1['nav_menu_id'] ?>"><i class="dark-white"></i> <?php echo $row1['nav_header_name'].' - '.$row1['nav_menu_name'] ?><br/>
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
      url:"user-add.php",  
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
