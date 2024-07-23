<?php 
require_once 'header.php';
?>

<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Upload New Document</h3>
      </div>
      <div class="block-content">
                
        <div class="card-body">
          <form id="form_simpan" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Document Title</label>
                  <input type="text" class="form-control" name="document_files_name" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Description</label>
                  <input type="text" class="form-control" name="document_files_tag" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Choose Category</label>
                  <select class="form-control" required name="document_category_id">
                    <?php
                    $sql  = "SELECT * FROM tbl_document_category ORDER BY document_category_name";
                    $h    = mysqli_query($conn,$sql);
                    while($row1 = mysqli_fetch_assoc($h)) {
                    ?>
                      <option value="<?php echo $row1['document_category_id'] ?>"><?php echo $row1['document_category_name'] ?></option> 
                    <?php } ?>
                  </select>
                </div>
              </div>              
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">When This Document Arrived?</label>
                  <input type="text" class="form-control" name="document_arrival_date" id="document_arrival_date" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Source</label>
                  <select class="form-control" required name="document_source">
                      <option value="E-mail">E-mail</option>
                      <option value="POS">POS</option>
                  </select>
                </div>
              </div>                            
              <div class="col-md-4">
                <div class="form-group">
                  <label class="col-12">Browse Multiple Files (Format JPG, BMP, GIF, PNG, PDF, ZIP, RAR)</label>
                  <div class="col-12">
                      <input type="file" name="files[]" multiple="multiple" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row">              
              <div class="col-md-12">
                <div class="form-group">
                  <label class="bmd-label-floating">Who Can Access?</label>
                  <br/>
                  <?php
                  $sql  = "SELECT * FROM tbl_department WHERE client_id = '".$_SESSION['client_id']."' ORDER BY department_name";
                  $h    = mysqli_query($conn,$sql);
                  while($row1 = mysqli_fetch_assoc($h)) {
                  ?> 
                  <input type="checkbox" name="department_id[]"  value="<?php echo $row1['department_id'] ?>"><i class="dark-white"></i> <?php echo $row1['department_name'] ?><br/>
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
  </div>
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="document.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
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
      url:"document-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="document.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });

  $("#document_arrival_date").attr("maxlength", 10);
  $("#document_arrival_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });

});
</script>
