<?php
require_once "../config.php";
/* coba diubah ke menit */
function hoursToMinutes($hours) 
{ 
    $minutes = 0; 
    if (strpos($hours, ':') !== false) 
    { 
        // Split hours and minutes. 
        list($hours, $minutes) = explode(':', $hours); 
    } 
    return $hours * 60 + $minutes; 
} 

// Transform minutes like "105" into hours like "1:45". 
function minutesToHours($minutes) 
{ 
    $hours = (int)($minutes / 60); 
    $minutes -= $hours * 60; 
    return sprintf("%02d:%02.0f", $hours, $minutes); 
}

echo 'ON 03:35 =>'.hoursToMinutes('05:03');
echo '<br/>';
echo 'OFF 23:34 =>'.hoursToMinutes('23:34');
echo '<br/>';
echo 'ON - OFF = 215 - 1414';
echo '<br/>';
$totala = 215-1414+1440;
if($totala<0) { $totala = $totala+1440; }
echo 'ON 03:35 =>'.hoursToMinutes('03:35');
echo '<br/>';
echo 'OFF 23:34 =>'.hoursToMinutes('23:34');
echo '<br/>';
echo 'ON - OFF = 215 - 1414';
echo '<br/>';
$totalb = 215-1414+1440;
if($totalb<0) { $totalb = $totalb+1440; }

$totalc = $totala+$totalb;
echo minutesToHours('41');
?>
<table class="table table-sm table-vcenter table-bordered" border="1">
                  <tr align="center">
                    <td>AFML Page</td>
                    <td colspan="2"><b>ROUTE</b></td>
                    <td colspan="4"><b>BLOCK</b></td>
                    <td rowspan="2"><b>T/O</b></td>
                    <td rowspan="2"><b>LDG</b></td>
                    <td colspan="2"><b>FLT</b></td>
                    <td rowspan="2"><b>LDG/CYCLE</b></td>
                    <td colspan="3"><b>FUEL</b></td>
                    <td rowspan="2"><b>RECEIPT NO</b></td>
                    <td colspan="2"><b>ADDED</b></td>
                    <td rowspan="2"><b>Action</b></td>                    
                  </tr>                      
                  <tr align="center">
                    <td><b>FROM</b></td>
                    <td><b>TO</b></td>
                    <td><b>OFF</b></td>
                    <td><b>ON</b></td>
                    <td><b>HRS</b></td>
                    <td><b>MINS</b></td>
                    <td><b>HRS</b></td>
                    <td><b>MINS</b></td>
                    <td><b>REM</b></td>
                    <td><b>UPLIFT</b></td>
                    <td><b>TOTAL</b></td>
                    <td><b>OIL</b></td>
                    <td><b>HYD</b></td>                    
                  </tr>
                  <?php
                  $sql2 = "SELECT *,date_format(afml_block_on, '%H:%i') as afml_block_on, date_format(afml_block_off, '%H:%i') as afml_block_off, date_format(afml_to, '%H:%i') as afml_to, date_format(afml_ldg, '%H:%i') as afml_ldg FROM tbl_afml_detail ORDER BY afml_detail_id ASC";
                  $h2   = mysqli_query($conn,$sql2);
                  while($row2 = mysqli_fetch_assoc($h2)) {

                    $afml_block_on   = hoursToMinutes($row2['afml_block_on']);
                    $afml_block_off  = hoursToMinutes($row2['afml_block_off']);
                    $afml_block_hrs  = hoursToMinutes($row2['afml_block_hrs']);
                    $afml_to         = hoursToMinutes($row2['afml_to']);
                    $afml_ldg        = hoursToMinutes($row2['afml_ldg']);
                    $afml_flt_hrs    = hoursToMinutes($row2['afml_flt_hrs']);

                    $sql3 = "INSERT INTO tbl_afml_detail_new
                    (afml_page_no,afml_date,
                    aircraft_reg_code,
                    afml_route_from,afml_route_to,
                    afml_icao_from,afml_icao_to,
                    afml_block_on,afml_block_off,
                    afml_block_hrs,
                    afml_to,afml_ldg,
                    afml_flt_hrs,
                    afml_ldg_cycle,
                    afml_fuel_rem,afml_fuel_uplift,
                    afml_fuel_total,
                    afml_receipt_no,afml_added_oil,
                    afml_added_hyd,fuel_attachment,
                    afml_fuel_date,
                    engineer_created_date,engineer_user_id,
                    created_date,user_id,client_id,
                    trx_id)
                    VALUES
                    ('".$row2['afml_page_no']."','".$row2['afml_date']."',
                    '".$row2['aircraft_reg_code']."',
                    '".$row2['afml_route_from']."','".$row2['afml_route_to']."',
                    '".$row2['afml_icao_from']."','".$row2['afml_icao_to']."',
                    '".$afml_block_on."', '".$afml_block_off."',
                    '".$afml_block_hrs."',
                    '".$afml_to."', '".$afml_ldg."',
                    '".$afml_flt_hrs."',
                    '".$row2['afml_ldg_cycle']."',
                    '".$row2['afml_fuel_rem']."','".$row2['afml_fuel_uplift']."',
                    '".$row2['afml_fuel_total']."',
                    '".$row2['afml_receipt_no']."', '".$row2['afml_added_oil']."',
                    '".$row2['afml_added_hyd']."', '".$row2['fuel_attachment']."',
                    '".$row2['afml_fuel_date']."',
                    '".$row2['engineer_created_date']."','".$row2['engineer_user_id']."',
                    '".$row2['created_date']."','".$row2['user_id']."','".$row2['client_id']."',
                    '".$row2['trx_id']."'
                    )";
                    mysqli_query($conn,$sql3);
                    echo $sql3.'<br/><br/>';
                    $afml_block_hrs = substr($row2['afml_block_hrs'],0,2);
                    $afml_block_min = substr($row2['afml_block_hrs'],3,2);
                    $afml_flt_hrs = substr($row2['afml_flt_hrs'],0,2);
                    $afml_flt_min = substr($row2['afml_flt_hrs'],3,2);
                  ?>  
                  <tr>
                    <td><?php echo $row2['afml_page_no'] ?></td>
                    <td><?php echo $row2['afml_route_from'] ?></td>
                    <td><?php echo $row2['afml_route_to'] ?></td>  
                    <td><?php echo $row2['afml_block_off'].' > '.hoursToMinutes($row2['afml_block_off']) ?></td>
                    <td><?php echo $row2['afml_block_on'] ?></td>
                    <td><?php echo $afml_block_hrs ?></td>
                    <td><?php echo $afml_block_min ?></td>
                    <td><?php echo $row2['afml_to'] ?></td>
                    <td><?php echo $row2['afml_ldg'] ?></td>
                    <td><?php echo $afml_flt_hrs ?></td>
                    <td><?php echo $afml_flt_min ?></td>
                    <td><?php echo $row2['afml_ldg_cycle'] ?></td>
                    <td><?php echo $row2['afml_fuel_rem'] ?></td>
                    <td><?php echo $row2['afml_fuel_uplift'] ?></td>
                    <td><?php echo $row2['afml_fuel_total'] ?></td>
                    <td><?php echo $row2['afml_receipt_no'] ?></td>
                    <td><?php echo $row2['afml_added_oil'] ?></td>
                    <td><?php echo $row2['afml_added_hyd'] ?></td>
                </tr>
            <?php } ?>
        </table>