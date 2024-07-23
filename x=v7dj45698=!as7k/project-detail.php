<?php
require_once 'header.php';
require_once "components.php";

$sql  = "SELECT *,date_format(start_date, '%d/%m/%Y') as start_date,date_format(end_date, '%d/%m/%Y') as end_date FROM tbl_project_master WHERE project_master_id = '".$ntf[1]."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
?>

<main id="main-container">
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Detail Project</h3>       
      </div>  
    </div>
    <!-- END Small Table -->
      <div class="block-content">
        <div class="card-body">
         <form id="form_simpan">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Project Name<label class="text-danger">*</label></label>
                  <input type="text" class="form-control" name="project_master_name" value="<?php echo $row['project_master_name'] ?>" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Project Amount <label class="text-danger">(in IDR, type number only)</label></label>
                  <input type="text" class="form-control" name="project_master_amount" onkeyup = "javascript:this.value=Comma(this.value);" value="<?php echo number_format($row['project_master_amount'],0,",",".") ?>" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Start Date</label>
                  <input type="text" id="start_date" class="form-control" name="start_date" value="<?php echo $row['start_date'] ?>" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">End Date</label>
                  <input type="text" id="end_date" class="form-control" name="end_date" value="<?php echo $row['end_date'] ?>" autocomplete="off" />
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
</main>
<?php require_once 'footer.php' ?>

<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Save</button> <a class="btn btn-info pull-right" href="project.php">Back</a>');  
  $('#submit_data').click(function(){  
    $('#form_simpan').submit(); 
    $("#results").html('<img width=50 src=<?php echo $base_url ?>assets/img/loading.gif>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>'); 
  });  
  $('#form_simpan').on('submit', function(event){  
    event.preventDefault();  
    $.ajax({  
      url:"project-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Save</button> <a class="btn btn-info pull-right" href="project.php">Back</a>');  
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

function Comma(Num)
 {
       Num += '';
       Num = Num.replace(/,/g, '');
       x = Num.split('.');
       x1 = x[0];
       x2 = x.length > 1 ? '.' + x[1] : '';

         var rgx = /(\d)((\d{3}?)+)$/;

       while (rgx.test(x1))
       x1 = x1.replace(rgx, '$1' + ',' + '$2');    
       return x1 + x2;              
 } 
</script>

