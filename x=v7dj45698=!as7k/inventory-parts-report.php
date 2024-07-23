<?php
session_start();
require_once '../config.php'; 
require('../assets/fpdf/fpdf.php');

//kurs
$sql_kurs = "SELECT parts_kurs_amount FROM tbl_parts_kurs WHERE active_status = 1";
$h_kurs   = mysqli_query($conn, $sql_kurs);
$row_kurs = mysqli_fetch_assoc($h_kurs);

$sql_total  = "SELECT sum(parts_price*parts_stock) as total_price, sum(parts_stock) as total_qty FROM tbl_parts";
$h_total    = mysqli_query($conn, $sql_total);
$row_total  = mysqli_fetch_assoc($h_total);
$total_in_usd   = $row_total['total_price']*$row_total['total_qty'];


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
// Membuat string
$pdf->Cell(185,7,'PT. Smart Cakrawala Aviation - Parts Data',0,1,'C');
$pdf->SetFont('Arial','B',9);
$pdf->Cell(185  ,7,'(As of '.date('d M Y').')',0,1,'C');
// Setting spasi kebawah supaya tidak rapat
$pdf->Cell(10,7,'',0,1);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(10,6,'No.','B',0,'L');
$pdf->Cell(70,6,'Part Name','B',0,'L');
$pdf->Cell(20,6,'Qty','B',0,'C');
$pdf->Cell(45,6,'Value ($)','B',0,'R');
$pdf->Cell(45,6,'Total Value (IDR)','B',0,'R');
$pdf->Cell(10,7,'',0,1);

$pdf->SetFont('Arial','',10);
$query = mysqli_query($conn, "SELECT a.parts_id,a.parts_name, sum(d.qty) as qty, a.parts_treshold, a.parts_number, serial_number, parts_price FROM tbl_parts a INNER JOIN tbl_parts_location_stock d ON a.parts_id = d.parts_id WHERE a.client_id = '".$_SESSION['client_id']."' GROUP BY parts_id ORDER BY parts_name ASC");
//$query  = mysqli_query($conn, "SELECT parts_id,parts_name, parts_number, parts_stock,parts_price FROM tbl_parts WHERE client_id = '".$_SESSION['client_id']."' ORDER BY parts_name ASC");
//$q2     = mysqli_query($conn, "SELECT count(parts_id) as id FROM tbl_parts WHERE client_id = '".$_SESSION['client_id']."' GROUP BY parts_name");
//$r2     = mysqli_fetch_assoc($q2);
$i=1;
while ($row = mysqli_fetch_array($query)){
	$parts_name = str_replace('&amp;', '&', $row['parts_name']);
    $pdf->Cell(10,6,$i,0,0);
    $pdf->Cell(70,6,$parts_name.' '.$row['parts_number'],0,0);
    $pdf->Cell(20,6,$row['qty'],0,0,C);
    $pdf->Cell(45,6,number_format($row['parts_price']*$row['qty'],2,",","."),0,0,R);
    $pdf->Cell(45,6,number_format($row['qty']*$row['parts_price']*$row_kurs['parts_kurs_amount'],2,",","."),0,0,R);
    $pdf->Cell(10,7,'',0,1);
    $i++;
}
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(255, 0, 0);
$pdf->Cell(10,6,'',0,0,R);
$pdf->Cell(70,6,'TOTAL',0,0,R);
$pdf->Cell(20,6,$row_total['total_qty'],0,0,C);
$pdf->Cell(45,6,number_format($row_total['total_price'],2,",","."),0,0,R);
$pdf->Cell(45,6,number_format($row_total['total_price']*$row_kurs['parts_kurs_amount'],2,",","."),0,0,R);

$pdf->Output();

require_once 'components.php';


?>

