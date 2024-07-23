<?php 
require_once 'header.php';
$sql  = "SELECT * FROM tbl_jenis_operasi_iata a INNER JOIN tbl_master_iata b USING (master_iata_id) INNER JOIN tbl_jenis_operasi c ON a.jenis_operasi_id = c.jenis_operasi_id WHERE a.client_id = '".$_SESSION['client_id']."' AND a.id = '".$ntf[1]."' LIMIT 1";
$h    = mysqli_query($conn, $sql);
$row  = mysqli_fetch_assoc($h);
?>

<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Edit Jenis Operasi</h3>
      </div>
      <div class="block-content">

        <?php if(@$_GET['act']=='79dvi59g') { ?>
          <div class="alert alert-danger alert-dismissable" role="alert">
              <p class="mb-0">Please fill all form!</p>
          </div>
        <?php } ?>   

        <?php if(@$_GET['act']=='49856twnaq4') { ?>
          <div class="alert alert-success alert-dismissable" role="alert">
              <p class="mb-0">Success</p>
          </div>
        <?php } ?> 

        <?php if(@$_GET['act']=='aasfa') { ?>
          <div class="alert alert-danger alert-dismissable" role="alert">
              <p class="mb-0">Duplicate data</p>
          </div>
        <?php } ?>

        <div class="card-body">
          <form action="jenis-operasi-edit.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                    <label class="bmd-label-floating">Operation Type</label>
                    <select required name="jenis_operasi_id" class="form-control">
                        <option value=""> -- Choose -- </option>
                        <?php
                        $sql1  = "SELECT * FROM tbl_jenis_operasi WHERE client_id = '".$_SESSION['client_id']."'";
                        $h1    = mysqli_query($conn,$sql1);
                        while($row1 = mysqli_fetch_assoc($h1)) {
                          if($row['jenis_operasi_id'] == $row1['jenis_operasi_id']) {
                        ?>
                        <option value="<?php echo $row1['jenis_operasi_id'] ?>" selected="selected"><?php echo $row1['jenis_operasi_name'] ?></option>
                        <?php }else{ ?>
                        <option value="<?php echo $row1['jenis_operasi_id'] ?>"><?php echo $row1['jenis_operasi_name'] ?></option>
                        <?php }} ?>
                    </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label class="bmd-label-floating">IATA CODE</label>
                    <select required name="master_iata_id" class="form-control">
                        <option value=""> -- Choose -- </option>
                        <?php
                        $sql1  = "SELECT master_iata_id, iata_code,icao_code, iata_province FROM tbl_master_iata ORDER BY iata_province";
                        $h1    = mysqli_query($conn,$sql1);
                        while($row1 = mysqli_fetch_assoc($h1)) {
                          if($row['master_iata_id'] == $row1['master_iata_id']) {
                        ?>
                        <option value="<?php echo $row1['master_iata_id'] ?>" selected="selected"><?php echo $row1['iata_code'].' / '.$row1['icao_code']." (".$row1['iata_province'].")" ?></option>
                          <?php }else{ ?>
                            <option value="<?php echo $row1['master_iata_id'] ?>"><?php echo $row1['iata_code'].' / '.$row1['icao_code']." (".$row1['iata_province'].")" ?></option>
                        <?php }} ?>
                    </select>
                </div>
              </div>              
            </div>                        
            </div>
            <div><input type="submit" class="btn btn-success mr-5 mb-5" value="Edit" /><a href="jenis-operasi.php">Back</a></div>
          </form>
        </div>
      </div>
    </div>

    <!-- END Page Content -->
  </div>
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>