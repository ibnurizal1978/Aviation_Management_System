<?php
require_once 'header.php';
?>

<!--separator credit limit-->
<script type='text/javascript'>
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
<!--end separator credit limit-->

<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Create New Parts Data</h3>
      </div>
      <div class="block-content">
        <div class="card-body">
          <form id="form_simpan">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Parts Name</label>
                  <input type="text" class="form-control" name="parts_name" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Parts Number</label>
                  <input type="text" class="form-control" name="parts_number" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Treshold Qty</label>
                  <input type="text" class="form-control" name="pats_treshold" value="0" autocomplete="off" />
                </div>                
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Qty</label>
                  <input type="text" class="form-control" name="parts_stock" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Price in US$ (type number only)</label>
                  <input type="text" class="form-control" onkeyup = "javascript:this.value=Comma(this.value);" name="parts_price" autocomplete="off" />
                </div>
              </div>              
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Location</label>
                    <select class="form-control" name="parts_rack_location_id">
                      <?php
                      $sql  = "SELECT * FROM tbl_parts_rack_location a INNER JOIN tbl_parts_warehouse b USING (parts_warehouse_id) WHERE a.client_id = '".$_SESSION['client_id']."' ORDER BY a.parts_rack_location_name";
                      $h    = mysqli_query($conn,$sql);
                      while($row = mysqli_fetch_assoc($h)) {
                      ?> 
                      <option value="<?php echo $row['parts_rack_location_id'] ?>"><?php echo $row['parts_rack_location_name'].' - '.$row['parts_warehouse_name'] ?></option>
                      <?php } ?>
                    </select>
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
<?php require_once 'footer.php' ?>

<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="inventory-parts.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
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
      url:"inventory-parts-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="inventory-parts.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });  
});  
</script>

