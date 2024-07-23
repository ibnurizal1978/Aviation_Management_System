<?php
require_once '../config.php';
?>
<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Choose Parts</h3>
      </div>
      <div class="block-content">
            <div class="row">

              <?php
              $sql  = "SELECT parts_name, parts_number, qty FROM tbl_parts_location_stock a INNER JOIN tbl_parts b USING (parts_id) WHERE a.parts_rack_location_id = '".$_POST['parts_warehouse_id']."'";
              echo $sql;
              $h    = mysqli_query($conn, $sql);
              if(mysqli_num_rows($h)==0) {
                echo '';
              }
              ?>
              <table class="table-responsive table" width="100%">
                <tr>
                  <td>Parts Name</td>
                  <td>Parts Number</td>
                  <td>Available Qty</td>
                  <td>Change Qty</td>
                </tr>
                <?php while($row = mysqli_fetch_assoc($h)) { ?>
                <tr>
                  <td><?php echo $row['parts_name'] ?></td>
                  <td><?php echo $row['parts_number'] ?></td>
                  <td><?php echo $row['qty'] ?></td>
                  <td><input type="text" class="form-control" name="parts_number" autocomplete="off" /></td>
                </tr>
                <?php } ?>
              </table>

          </form>

        </div>
      </div>
    </div>
  <!-- END Small Table -->

  <!-- END Page Content -->
  </div>
</main>
<?php require_once 'footer.php' ?>

