<?php 
require_once 'header.php';
//require_once 'components.php';
?>


<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">
        <div class="row invisible" data-toggle="appear">
            <!-- Row #3 -->
            <div class="col-md-6">
                <div class="block block-rounded block-bordered">
                    <div class="block-header block-header-default border-b">
                        <h3 class="block-title">Top 10 Parts Stock</h3>
                    </div>
                    <div class="block-content">
                        <?php
                        $sql    = "SELECT parts_rack_location_id,parts_id,parts_name,parts_treshold,parts_code,parts_rack_location_name,parts_warehouse_name,parts_stock,parts_broken,parts_replacement,parts_ready_to_use FROM tbl_parts a INNER JOIN tbl_parts_rack_location b USING (parts_rack_location_id) INNER JOIN tbl_parts_warehouse c USING (parts_warehouse_id) ORDER BY parts_stock DESC LIMIT 10";
                        $h      = mysqli_query($conn, $sql);
                        ?>                                    
                        <table class="table table-borderless table-striped">
                            <thead>
                                <tr>
                                    <th>Parts Name</th>
                                    <th>Treshold</th>
                                    <th>Current Stock</th>
                                    <th>Broken</th>
                                    <th>Replace</th>
                                    <th>Ready To Use</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($h)) { ?>
                                <tr>
                                    <td><?php echo $row["parts_name"]; ?></td>  
                                    <td>
                                    <?php 
                                    if($row['parts_stock']<$row['parts_treshold']) {
                                      echo "<font color=ff0000>".$row['parts_treshold']."</font>";
                                    }else{
                                      echo $row['parts_treshold'];
                                    } 
                                    ?>
                                    </td>
                                    <td><?php echo $row['parts_stock'] ?></td>
                                    <td><?php echo $row['parts_broken'] ?></td>
                                    <td><?php echo $row['parts_replacement'] ?></td>
                                    <td><?php echo $row['parts_ready_to_use'] ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block block-rounded block-bordered">
                    <div class="block-header block-header-default border-b">
                        <h3 class="block-title">Top 10 Customers</h3>
                    </div>
                    <div class="block-content">
                        <?php
                        $sql    = "SELECT customer_phone_number, ledger_master_amount FROM tbl_customer a INNER JOIN tbl_ledger_master b USING (customer_account_id) ORDER BY ledger_master_amount DESC LIMIT 10";
                        $h      = mysqli_query($conn2, $sql);
                        ?>                                     
                        <table class="table table-borderless table-striped">
                            <thead>
                                <tr>
                                    <th width="70%">Customer</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($h)) { ?>
                                <tr>
                                    <td><?php echo $row['customer_phone_number'] ?></td>
                                    <td>IDR <?php echo number_format($row['ledger_master_amount'],0,",",".") ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row invisible" data-toggle="appear">
            <!-- Row #3 -->
            <div class="col-md-6">
                <div class="block block-rounded block-bordered">
                    <div class="block-header block-header-default border-b">
                        <h3 class="block-title">Due List Crew License</h3>
                    </div>
                    <div class="block-content">
                        <?php
                        $sql    = "SELECT parts_rack_location_id,parts_id,parts_name,parts_treshold,parts_code,parts_rack_location_name,parts_warehouse_name,parts_stock,parts_broken,parts_replacement,parts_ready_to_use FROM tbl_parts a INNER JOIN tbl_parts_rack_location b USING (parts_rack_location_id) INNER JOIN tbl_parts_warehouse c USING (parts_warehouse_id) ORDER BY parts_stock DESC LIMIT 10";
                        $h      = mysqli_query($conn, $sql);
                        ?>                                    
                        <table class="table table-borderless table-striped">
                            <thead>
                                <tr>
                                    <th>Parts Name</th>
                                    <th>Treshold</th>
                                    <th>Current Stock</th>
                                    <th>Broken</th>
                                    <th>Replace</th>
                                    <th>Ready To Use</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($h)) { ?>
                                <tr>
                                    <td><?php echo $row["parts_name"]; ?></td>  
                                    <td>
                                    <?php 
                                    if($row['parts_stock']<$row['parts_treshold']) {
                                      echo "<font color=ff0000>".$row['parts_treshold']."</font>";
                                    }else{
                                      echo $row['parts_treshold'];
                                    } 
                                    ?>
                                    </td>
                                    <td><?php echo $row['parts_stock'] ?></td>
                                    <td><?php echo $row['parts_broken'] ?></td>
                                    <td><?php echo $row['parts_replacement'] ?></td>
                                    <td><?php echo $row['parts_ready_to_use'] ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block block-rounded block-bordered">
                    <div class="block-header block-header-default border-b">
                        <h3 class="block-title">Top 10 Customers</h3>
                    </div>
                    <div class="block-content">
                        <?php
                        $sql    = "SELECT customer_phone_number, ledger_master_amount FROM tbl_customer a INNER JOIN tbl_ledger_master b USING (customer_account_id) ORDER BY ledger_master_amount DESC LIMIT 10";
                        $h      = mysqli_query($conn2, $sql);
                        ?>                                     
                        <table class="table table-borderless table-striped">
                            <thead>
                                <tr>
                                    <th width="70%">Customer</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($h)) { ?>
                                <tr>
                                    <td><?php echo $row['customer_phone_number'] ?></td>
                                    <td>IDR <?php echo number_format($row['ledger_master_amount'],0,",",".") ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

                
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
