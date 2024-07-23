<?php
require_once 'header.php';
require_once "components.php";

$sql  = "SELECT project_master_id,project_master_name FROM tbl_project_master WHERE project_master_id = '".$ntf[1]."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
?>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title ">Assign Engineer to Project: <?php echo $row['project_master_name'] ?></h4>
            <p class="card-category">Click "Save" to store to database.</b></p>
          </div>
          <div class="card-body">
           <form id="form_simpan">
              <input type="hidden" name="project_master_id" value="<?php echo $row['project_master_id'] ?>">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">Engineer Full Name</label>
                    <br/>
                    <?php
                    $sql2  =" SELECT user_id,full_name, (SELECT count(*) FROM tbl_project_engineer_team x WHERE x.engineer_user_id = y.user_id AND project_master_id = '".$row['project_master_id']."') AS ada FROM tbl_user y WHERE department_id = 1 ORDER BY full_name ";
                    $h2    = mysqli_query($conn,$sql2);
                    while($row2 = mysqli_fetch_assoc($h2)) {
                      if($row2['ada']>0){
                    ?> 
                    <input type="checkbox" name="user_id[]" checked value="<?php echo $row2['user_id'] ?>"><i class="dark-white"></i> <?php echo $row2['full_name'] ?><br/>
                    <?php }else{ ?>
                    <input type="checkbox" name="user_id[]" value="<?php echo $row2['user_id'] ?>"><i class="dark-white"></i> <?php echo $row2['full_name'] ?><br/>
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
    </div>

  </div>
</div>
<?php require_once 'footer.php' ?>

<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Save</button> <a class="btn btn-info pull-right" href="project-assign-engineer.php">Back</a>');  
  $('#submit_data').click(function(){  
    $('#form_simpan').submit(); 
    $("#results").html('<img width=50 src=<?php echo $base_url ?>assets/img/loading.gif>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button> <a class="btn btn-info pull-right" href="project-assign-engineer.php">Back</a>'); 
  });  
  $('#form_simpan').on('submit', function(event){  
    event.preventDefault();  
    $.ajax({  
      url:"project-assign-engineer-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Save</button>');  
      }  
    });  
  });    
    
});
</script>

