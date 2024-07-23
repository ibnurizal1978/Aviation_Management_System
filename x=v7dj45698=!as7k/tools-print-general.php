<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
require_once 'components.php';
$sql    = "SELECT * FROM tbl_parts_location WHERE parts_location_id = '".$_POST['parts_location_id']."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h      = mysqli_query($conn,$sql);
$row    = mysqli_fetch_assoc($h);

$sql2    = "SELECT *,date_format(last_calibration_date, '%d/%m/%Y') as last_calibration_date,date_format(next_calibration_date, '%d/%m/%Y') as next_calibration_date FROM tbl_tools a INNER JOIN tbl_parts_location b USING (parts_location_id) WHERE a.parts_location_id = '".$row['parts_location_id']."' AND a.client_id = '".$_SESSION['client_id']."' AND date(last_calibration_date)='0000-00-00'";
$h2      = mysqli_query($conn,$sql2);
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
                            <h3>GENERAL TOOLS & GSE CONTROL LISTED </h3>
                            <b>
                                <?php echo $_POST['base_type'].': '.$row['parts_location_name'] ?>
                            <br/>
                            Date/Month Control: <?php echo $_POST['datex'] ?>
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
                            <th>Location</th>
                            <th>Quantity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count = 0;
                        while ($row = mysqli_fetch_assoc($h2)) { 
                            $count = $count + 1;
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $count ?></td>
                            <td><?php echo $row['tools_description'] ?></td>
                            <td><?php echo $row['part_number'] ?></td>
                            <td><?php echo $row['serial_number'] ?></td>
                            <td><?php echo $row['parts_location_name'] ?></td>
                            <td><?php echo $row['qty']; ?></td>
                            <td><?php echo $row['notes']; ?></td>
                        </tr>
                        <?php } mysqli_free_result($h); ?>
                    </tbody>
                </table>
                <br/><br/>
                <table width="100%" border="1">
                    <tr>
                        <td valign="top" width="20%">CONTROLLED BY:<br/><br/><br/><br/>(Dede Irpan, Amd)<br/>Store Keeper</td>
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