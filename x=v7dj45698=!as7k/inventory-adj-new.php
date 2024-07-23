<?php
require_once 'header.php';
?>
<script type="text/javascript">
   function price() {
  var tes = document.getElementById("parts_warehouse_id").value;
        document.getElementById("parts_warehouse_id_value").value=tes;
}
</script>
<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Adjustment - Choose Warehouse and Parts to Adjust</h3>
      </div>
      <div class="block-content">
        <div class="card-body">
          <form action="inventory-adj-parts.php" method="POST" onsubmit="target_popup(this)">    
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">SELECT WAREHOUSE</label>
                    <select class="form-control" name="parts_warehouse_id" onchange="price()" id="parts_warehouse_id">
                      <option> -- CHOOSE WAREHOUSE -- </option>
                      <?php
                      $sql  = "SELECT * FROM tbl_parts_warehouse WHERE client_id = '".$_SESSION['client_id']."' ORDER BY parts_warehouse_name";
                      $h    = mysqli_query($conn,$sql) or die (mysqli_error());
                      while($row = mysqli_fetch_assoc($h)) {
                      ?> 
                      <option value="<?php echo $row['parts_warehouse_id'] ?>"<?php if($_REQUEST['parts_warehouse_id']==$row['parts_warehouse_id']) echo 'selected="selected"'; ?>><?php echo $row['parts_warehouse_name'] ?></option>
                      <?php } ?>
                    </select>
                </div>                
              </div>
            </div>
          </form>
          <div class="row">              
            <div class="col-md-12">
              <form action="inventory-adj-new.php" method="GET">
                <input type="hidden" value="1" name="pilih">
                <?php if($_GET['pilih']==1) { ?>
                <input type="hidden" value="<?php echo $_GET['parts_warehouse_id'] ?>" id="parts_warehouse_id_value" name="parts_warehouse_id">
                <?php }else{ ?>
                <input type="hidden" id="parts_warehouse_id_value" name="parts_warehouse_id">
                <?php } ?>
                <input type="text" name="parts_name" placeholder="type keyword">
                <input type="submit" value="find">
              </form>
            </div>
          </div>

          <div class="row">              
            <div class="col-md-12">
              <?php
              if($_GET['pilih']==1) {
                $parts_name = $_GET['parts_name'];
                $parts_warehouse_id = $_GET['parts_warehouse_id'];
                $sql  = "SELECT parts_id, parts_name, parts_number, qty, a.parts_rack_location_id, parts_rack_location_name, parts_warehouse_name FROM tbl_parts_location_stock a INNER JOIN tbl_parts b USING (parts_id) INNER JOIN tbl_parts_rack_location c ON c.parts_rack_location_id = a.parts_rack_location_id INNER JOIN tbl_parts_warehouse d ON c.parts_warehouse_id = d.parts_warehouse_id WHERE d.parts_warehouse_id = '".$parts_warehouse_id."' AND (parts_name LIKE '%$parts_name%' OR parts_number LIKE '%$parts_name%')";
                $h    = mysqli_query($conn, $sql);
                if(mysqli_num_rows($h)==0) {
                  echo '<br/><b>no data found. Try another keyword.</b><br/>';
                }else{
              ?>
              <table width=60% border=1 class="table table-sm table-vcenter">
                <tr>
                  <td>Parts Name</td>
                  <td>Parts Number</td>
                  <td class="text-center">Available Qty</td>
                  <td class="text-center">Change Qty</td>
                  <td>Action</td>
                </tr>
                <?php while($row = mysqli_fetch_assoc($h)) { ?>
                <form action="inventory-adj-add-parts.php" method="POST">
                <tr>
                  <td>
                    <input type="text" name="parts_id" value="<?php echo $row['parts_id'] ?>">
                    <input type="hidden" name="parts_warehouse_id" value="<?php echo $_GET['parts_warehouse_id'] ?>">
                    <input type="hidden" name="parts_rack_location_id" value="<?php echo $row['parts_rack_location_id'] ?>">
                    <input type="hidden" name="qty" value="<?php echo $row['qty'] ?>">
                    <?php echo $row['parts_name'] ?>
                  </td>
                  <td><?php echo $row['parts_number'] ?></td>
                  <td class="text-center"><?php echo $row['qty'] ?></td>
                  <td class="text-center"><input size=4 type="text" name="qty2" /></td>
                  <td><input type="submit" value="Choose"></td>
                </tr>
                </form>
                <?php } ?>
              </table>
              <?php } ?>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
<br/>
  <div class="content">
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Your Adjustment List</h3>
      </div>
      <div class="block-content">
        <div class="card-body">           
            <?php
            $tgl = date('Y-m-d');
              $sql  = "SELECT * FROM tbl_parts_adj_log a INNER JOIN tbl_parts b USING (parts_id) WHERE date(a.created_date) = '".$tgl."' AND adj_status = 'PENDING'";
              $h    = mysqli_query($conn, $sql);
              if(mysqli_num_rows($h)==0) {
                echo 'List are still empty';
              }else{
            ?>
            <form action="inventory-adj-final.php" method="POST">
            <div class="row">              
              <div class="col-md-2">
                <div class="form-group">
                  <label class="bmd-label-floating">Date (dd/mm/yyyy)</label>
                  <input type="text" class="form-control" name="adj_date" id="adj_date" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Description</label>
                  <input type="text" class="form-control" name="description" autocomplete="off" />
                </div>
              </div>             
              <!--<div class="col-md-2">
                <div class="form-group">
                  <label class="bmd-label-floating">&nbsp;</label>
                  <a href="inventory-adj-parts.php&parts_warehouse_id=<?php echo $_GET['parts_warehouse_id_value'];?>">aa</a><input type="text" id="psarts_warehouse_id_value">
                </div>
              </div>-->              
            </div>             
            <table width=60% border=1 class="table table-sm table-vcenter">
              <tr>
                <td>Parts Name</td>
                <td>Parts Number</td>
                <td>Available Qty</td>
                <td>Change Qty</td>
                <td>Final Qty</td>
                <td>Action</td>
              </tr>
              <?php while($row = mysqli_fetch_assoc($h)) { ?>
              <tr>
                <td><?php echo $row['parts_name'] ?></td>
                <td><?php echo $row['parts_number'] ?></td>
                <td><?php echo $row['before_qty'] ?></td>
                <td><?php echo $row['adj_qty'] ?></td>
                <td><?php echo $row['new_qty'] ?></td>
                <td><a type="button" class="btn btn-sm btn-secondary" href="inventory-adj-cancel.php?id=<?php echo $row['id'] ?>">cancel</a></td>
              </tr>
              <?php } ?>
            </table>
            <input type="submit" value="Execute Adjustment">
            </form> 
            <?php } ?>

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

$(document).ready(function() {

  $("#adj_date").attr("maxlength", 10);
  $("#adj_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });
      
}); 
</script>

