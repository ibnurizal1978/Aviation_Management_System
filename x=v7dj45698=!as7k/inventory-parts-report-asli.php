<script type="text/javascript">
function openInNewTab(url) {
  var win = window.open(url, '_blank');
  win.focus();
}  
</script>
<body onLoad="openInNewTab('inventory-parts-report.php');"></body>
<h3>PDF opened in new browser window.</h3>
<b><a href=inventory-parts-report.php>Click here to open report if browser doesn't automatically redirect</a>
<br/>
<b><a href=javascript:window.history.back()>Click here to go back to previous page</a></b></h3>
<?php
/*
session_start();
require_once '../config.php'; 
require_once 'components.php';

$sql_kurs = "SELECT parts_kurs_amount FROM tbl_parts_kurs WHERE active_status = 1";
$h_kurs   = mysqli_query($conn, $sql_kurs);
$row_kurs = mysqli_fetch_assoc($h_kurs);


$sql_total  = "SELECT sum(parts_price) as total_price, sum(parts_stock) as total_qty FROM tbl_parts";
$h_total    = mysqli_query($conn, $sql_total);
$row_total  = mysqli_fetch_assoc($h_total);
$total_in_usd   = $row_total['total_price']*$row_total['total_qty'];


?>

<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">
    <?php
      $sql = "SELECT parts_id,parts_name, parts_number, parts_stock,parts_price FROM tbl_parts WHERE client_id = '".$_SESSION['client_id']."' ORDER BY parts_name ASC";
      $rs_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($rs_result)==0) {
      ?>
        <div class="alert alert-info ks-solid text-center" role="alert">Please choose date period</div>
    <?php } ?>    
        <div class="block table-responsive">
            <div class="block-content">
              <div class="text-center">
                <h5>PT. Smart Cakrawala Aviation - Parts Data</h5> 
                <span class="text-danger">(As of <?php echo date('d M Y') ?>)</span>
                <br/>
                Kurs: <?php echo 'Rp. '.number_format($row_kurs['parts_kurs_amount'],0,",",".") ?>
              </div>
              <br/><br/>
                <table class="table table-sm table-vcenter" width="100%" border="1">
                    <thead>
                        <tr>
                            <th width="50%">Part Name</th>
                            <th>Qty</th>
                            <th width="15%">Value</th>
                            <th width="15%">Total Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                        <tr>
                            <td><?php echo $row['parts_name'].' '.$row['parts_number'] ?></td>
                            <td><?php echo $row['parts_stock'] ?></td>
                            <td><?php echo $row['parts_price']*$row['parts_stock'] ?></td>
                            <td><?php echo $row['parts_stock']*$row['parts_price']*$row_kurs['parts_kurs_amount'] ?></td>
                        </tr>
                        <?php } mysqli_free_result($rs_result); ?>
                        <tr class="text-danger">
                          <td>&nbsp;</td>
                          <td><?php echo $row_total['total_qty'] ?></td>
                          <td><?php echo '$ '.number_format($row_total['total_price'],0,",",".") ?></td>
                          <td><?php echo 'Rp. '.number_format($row_total['total_price']*$row_kurs['parts_kurs_amount'],0,",",".") ?></td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php require_once 'footer.php' */ ?>
