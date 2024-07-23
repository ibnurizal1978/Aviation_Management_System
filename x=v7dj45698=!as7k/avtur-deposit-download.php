<?php
session_start();
require_once '../config.php';

//date_default_timezone_set($_SESSION['client_timezone']);

header("Content-type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=download-avtur-deposit.xls"); 

$x = input_data(filter_var($_POST['x'],FILTER_SANITIZE_STRING));

if($x == '1304') {
    $trx_to        =   input_data(filter_var($_POST['trx_to'],FILTER_SANITIZE_STRING));
    if($trx_to=='X') {
        $trx_to = "('INTERNAL','EXTERNAL')";
    }else{
        $trx_to = "('".$trx_to."')";
    }

    $download_from =   input_data(filter_var($_POST['download_from'],FILTER_SANITIZE_STRING));
    $download_to   =   input_data(filter_var($_POST['download_to'],FILTER_SANITIZE_STRING));
    $from_Y     = substr($download_from,6,4);
    $from_M     = substr($download_from,3,2);
    $from_D     = substr($download_from,0,2);
    $from       = $from_Y.'-'.$from_M.'-'.$from_D;
    $from_tampil = $from_D.'-'.$from_M.'-'.$from_Y;

    $to_Y       = substr($download_to,6,4);
    $to_M       = substr($download_to,3,2);
    $to_D       = substr($download_to,0,2);
    $to         = $to_Y.'-'.$to_M.'-'.$to_D;
    $to_tampil  = $to_D.'-'.$to_M.'-'.$to_Y;

    $sql = "SELECT date_format(afml_fuel_date, '%d/%m/%Y') as afml_fuel_date2,afml_receipt_no,full_name,afml_notes,afml_amount,iata_code,aircraft_reg_code,afml_price,transaction_type,saldo_akhir FROM tbl_avtur_afml WHERE client_id = '".$_SESSION['client_id']."' AND (afml_fuel_date BETWEEN '".$from."' AND '".$to."') AND transaction_belong_to IN ".$trx_to."  ORDER BY afml_fuel_date";    

}else{
 
    $trx_to     =   input_data(filter_var($_POST['trx_to'],FILTER_SANITIZE_STRING));    
    $month      =   input_data(filter_var($_POST['month'],FILTER_SANITIZE_STRING));
    $year       =   input_data(filter_var($_POST['year'],FILTER_SANITIZE_STRING));

    if($trx_to=='X') {
        $trx_to = "('INTERNAL','EXTERNAL')";
    }else{
        $trx_to = "('".$trx_to."')";
    }

    $sql = "SELECT date_format(afml_fuel_date2, '%d/%m/%Y') as afml_fuel_date2,afml_receipt_no,full_name,afml_notes,afml_amount,iata_code,aircraft_reg_code,afml_price,transaction_type,saldo_akhir FROM tbl_avtur_afml WHERE client_id = '".$_SESSION['client_id']."' AND month(afml_fuel_date)='".$month."' AND year(afml_fuel_date) = '".$year."' AND transaction_belong_to IN ".$trx_to." ORDER BY afml_fuel_date";        
}
$h   = mysqli_query($conn,$sql) or die(mysqli_error());
?>

<style>
@import url(https://fonts.googleapis.com/css?family=Titillium+Web:300italic,400italic,600italic,700italic,800italic,400,600,700,300,800);

BODY {
    MARGIN: 0px; background-color:ffffff; font-family: "Titillium Web", "Helvetica Neue", sans-serif;
}
BODY {
    FONT-SIZE: 11px; font-family: "Titillium Web", "Helvetica Neue", sans-serif;
}
.judul {FONT-SIZE: 16px; font-family: "Titillium Web", "Helvetica Neue", sans-serif;
}
TD {
    FONT-SIZE: 11px; font-family: "Titillium Web", "Helvetica Neue", sans-serif;
}
TH {
    FONT-SIZE: 12px; font-family: "Titillium Web", "Helvetica Neue", sans-serif;
}
</style>


<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">    
        <table class="table table-sm table-bordered" border=1>
            <tr style="font-weight: bold">
                <td>Tgl</td>
                <td>No Receipt</td>
                <td>Engineer</td>
                <td>Keterangan</td>
                <td>QTY Liter</td>
                <td>IATA</td>
                <td>Register</td>
                <td class="text-center">D</td>
                <td class="text-center">K</td>
                <td>Saldo</td>
            </tr>
            <?php while($row = mysqli_fetch_assoc($h)) { ?>
            <tr>
                <td><?php echo $row['afml_fuel_date2'] ?></td>
                <td><?php echo $row['afml_receipt_no'] ?></td>
                <td><?php echo $row['full_name'] ?></td>
                <td><?php echo $row['afml_notes'] ?></td>
                <td><?php echo $row['afml_amount'] ?></td>
                <td><?php echo $row['iata_code'] ?></td>
                <td><?php echo $row['aircraft_reg_code'] ?></td>
                <td class="text-right"><?php if($row['transaction_type']=='D') { echo number_format($row['afml_price'],0,",","."); } ?></td> 
                <td class="text-right"><?php if($row['transaction_type']=='K') { echo number_format($row['afml_price'],0,",","."); } ?></td>
                <td class="text-right"><?php echo number_format($row['saldo_akhir'],0,",","."); ?></td> 
            </tr>
            <?php } ?>
        </table>       
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>