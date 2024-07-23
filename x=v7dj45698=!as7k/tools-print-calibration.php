<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
require_once 'components.php';
$sql    = "SELECT *,CURDATE(), DATEDIFF(next_calibration_date, CURDATE()) AS selisih,date_format(next_calibration_date, '%d/%m/%Y') as next_calibration_date2,date_format(last_calibration_date, '%d/%m/%Y') as last_calibration_date,date_format(next_calibration_date, '%d/%m/%Y') as next_calibration_date FROM tbl_tools WHERE client_id = '".$_SESSION['client_id']."' AND date(last_calibration_date)<>'0000-00-00'";
$h      = mysqli_query($conn,$sql);
?>
<body onLoad="javascript:window.print();">
<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">   
        <div class="block table-responsive">
            <div class="block-content">
                <table width="100%">
                    <tr>
                        <td width="5%"><img src="../assets/img/logo.jpg" width="80"></td>
                        <td class="text-center">
                            <b>CALIBRATION LIST TOOLS AND EQUIPMENT</b>
                            <h3>PT. SMART CAKRAWALA AVIATION</h3>
                            <b>MAINTENANCE DEPARTMENT<br/>
                            Form: SCA/MTC/068</b>
                        </td>
                    </tr>
                </table>

                <table class="table table-sm table-vcenter" border="1">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Description</th>
                            <th>Part No.</th>
                            <th>Serial No.</th>
                            <th>Calibrated Date</th>
                            <th>Expired Date</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count = 0;
                        while ($row = mysqli_fetch_assoc($h)) { 
                            $count = $count + 1;
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $count ?></td>
                            <td><?php echo $row['tools_description'] ?></td>
                            <td><?php echo $row['part_number'] ?></td>
                            <td><?php echo $row['serial_number'] ?></td>
                            <td><?php echo $row['last_calibration_date'] ?></td>
                            <td class="text-center">
                                <?php
                                if($row['selisih']<30) {
                                    echo '<b class="text-danger">'.$row['next_calibration_date'].'</b>';
                                }else{
                                    echo $row['next_calibration_date'];
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                if($row['selisih']<30) {
                                    echo '<b class="text-danger">Unserviceable</b>';
                                }else{
                                    echo $row['notes'];
                                }
                                ?>
                            </td>                            
                        </tr>
                        <?php } mysqli_free_result($h); ?>
                    </tbody>
                </table>
                <br/><br/>
                <table width="100%" border="1">
                    <tr>
                        <td valign="top" width="20%">Printed Date:<br/><br/><br/><br/><?php echo $_POST['print_date'] ?></td>
                        <td valign="top">Checked By:<br/><br/><br/><br/>Ilham Chairi<br/></td>
                    </tr>
                </table>                
            </div>
        </div>
        <!-- END Small Table -->

    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>