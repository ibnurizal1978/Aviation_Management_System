<?php 
session_start();
//ini_set('display_errors',1);  error_reporting(E_ALL);
$db_server        = '127.0.0.1';
$db_user          = 'root';
$db_password      = 'Database@123';
$db_name          = 'db_aviation';
$conn 			  = new mysqli($db_server,$db_user,$db_password,$db_name) or die (mysqli_error());

date_default_timezone_set('Asia/Jakarta');
//$tanggal = date('Y-m-d', mktime(0,0,0,date('m'),date('d')-1,date('Y')));
//$tanggal = date('Y-m-d');
?>
<h3>Update Flight Incentive</h3>
<form action="cron-update-afml-price-form.php" method="POST">
    <input type="hidden" name="t" value="1">
    <select name="bulan">
        <option value=""> -- Choose Month-- </option>
        <option value="01">Jan</option>
        <option value="02">Feb</option>
        <option value="03">Mar</option>
        <option value="04">Apr</option>
        <option value="05">May</option>
        <option value="06">Jun</option>
        <option value="07">Jul</option>
        <option value="08">Aug</option>
        <option value="09">Sep</option>
        <option value="10">Oct</option>
        <option value="11">Nov</option>
        <option value="12">Des</option>
    </select>

    <select name="tahun">
        <option value=""> -- Choose Year-- </option>
        <?php for($i=2020;$i<2050;$i++) { ?>
        <option value="<?php echo $i ?>"><?php echo $i ?></option>
        <?php } ?>    
    </select>

    <input type="submit" value="GO">
</form>

<?php
if($_POST['t'] == 1) {
//grab afml detail
$sql_afml       = "SELECT afml_detail_id,afml_captain_user_id, afml_copilot_user_id, afml_engineer_on_board_user_id, afml_page_no, afml_route_from, afml_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no)  WHERE month(a.afml_date) = '".$_POST['bulan']."' AND year(a.afml_date) = '".$_POST['tahun']."'"; 
} 
/*  a.afml_date = '".$tanggal."'  ORDER by a.afml_detail_id";  */
$h_afml         = mysqli_query($conn, $sql_afml);
while($r_afml   = mysqli_fetch_assoc($h_afml)) {

    //link ke pilot ID
    $sql_pilot  = "SELECT user_id, full_name FROM tbl_user WHERE user_id = '".$r_afml['afml_captain_user_id']."'";
    $h_pilot    = mysqli_query($conn, $sql_pilot);
    $r_pilot    = mysqli_fetch_assoc($h_pilot);

    //link ke copilot ID
    $sql_copilot= "SELECT user_id, full_name FROM tbl_user WHERE user_id = '".$r_afml['afml_copilot_user_id']."'";
    $h_copilot  = mysqli_query($conn, $sql_copilot);
    $r_copilot  = mysqli_fetch_assoc($h_copilot);

    //link ke EOB ID
    $sql_eob    = "SELECT user_id, full_name FROM tbl_user WHERE user_id = '".$r_afml['afml_engineer_on_board_user_id']."'";
    $h_eob      = mysqli_query($conn, $sql_eob);
    $r_eob      = mysqli_fetch_assoc($h_eob);    

    //link ke jenis_operasi_id, dia masuk kategori apa
    $sql_ops    = "SELECT * FROM tbl_master_iata INNER JOIN tbl_jenis_operasi_iata USING (master_iata_id)  INNER JOIN tbl_jenis_operasi USING (jenis_operasi_id)  WHERE (iata_code = '".$r_afml['afml_route_from']."' OR icao_code = '".$r_afml['afml_route_from']."')";
    $h_ops      = mysqli_query($conn, $sql_ops);
    $r_ops      = mysqli_fetch_assoc($h_ops);

    /*------link pilot ke route_from dan jenis operasinya------*/
    $sql_pilot_hours= "SELECT price,start_date, end_date FROM tbl_jenis_operasi_user a INNER JOIN tbl_jenis_operasi_iata b USING (jenis_operasi_id) WHERE a.user_id = '".$r_pilot['user_id']."' AND b.master_iata_id = '".$r_ops['master_iata_id']."' AND price <>0 ORDER BY a.id DESC LIMIT 1";
    $h_pilot_hours  = mysqli_query($conn, $sql_pilot_hours);
    $r_pilot_hours  = mysqli_fetch_assoc($h_pilot_hours);

    $price_pilot_per_minute = round(($r_pilot_hours['price']/60),2);
    $total_price_pilot = round(($price_pilot_per_minute * $r_afml['afml_flt_hrs']),2);

    $s_update_pilot = "UPDATE tbl_afml_detail SET jenis_operasi_id = '".$r_ops['jenis_operasi_id']."', jenis_operasi_name = '".$r_ops['jenis_operasi_name']."', price_pilot_per_hour = '".$r_pilot_hours['price']."', total_price_pilot = '".$total_price_pilot."' WHERE afml_detail_id = '".$r_afml['afml_detail_id']."' ";  
    mysqli_query($conn, $s_update_pilot);
    /*------END link pilot ke route_from dan jenis operasinya------*/

    /*------link copilot ke route_from dan jenis operasinya------*/
    $sql_copilot_hours= "SELECT price,start_date, end_date FROM tbl_jenis_operasi_user a INNER JOIN tbl_jenis_operasi_iata b USING (jenis_operasi_id) WHERE a.user_id = '".$r_copilot['user_id']."' AND b.master_iata_id = '".$r_ops['master_iata_id']."' AND price <>0 ORDER BY a.id DESC LIMIT 1";
    $h_copilot_hours  = mysqli_query($conn, $sql_copilot_hours);
    $r_copilot_hours  = mysqli_fetch_assoc($h_copilot_hours);

    $price_copilot_per_minute = round(($r_copilot_hours['price']/60),2);
    $total_price_copilot = round(($price_copilot_per_minute * $r_afml['afml_flt_hrs']),2);

    $s_update_copilot = "UPDATE tbl_afml_detail SET jenis_operasi_id = '".$r_ops['jenis_operasi_id']."', jenis_operasi_name = '".$r_ops['jenis_operasi_name']."', price_copilot_per_hour = '".$r_copilot_hours['price']."', total_price_copilot = '".$total_price_copilot."' WHERE afml_detail_id = '".$r_afml['afml_detail_id']."' ";    
    mysqli_query($conn, $s_update_copilot);
    /*------END link copilot ke route_from dan jenis operasinya------*/


    /*------link eob ke route_from dan jenis operasinya------*/
    $sql_eob_hours= "SELECT price,start_date, end_date FROM tbl_jenis_operasi_user a INNER JOIN tbl_jenis_operasi_iata b USING (jenis_operasi_id) WHERE a.user_id = '".$r_eob['user_id']."' AND b.master_iata_id = '".$r_ops['master_iata_id']."' AND price <>0 ORDER BY a.id DESC LIMIT 1";
    $h_eob_hours  = mysqli_query($conn, $sql_eob_hours);
    $r_eob_hours  = mysqli_fetch_assoc($h_eob_hours);

    $price_eob_per_minute = round(($r_eob_hours['price']/60),2);
    $total_price_eob = round(($price_eob_per_minute * $r_afml['afml_flt_hrs']),2);

    $s_update_eob = "UPDATE tbl_afml_detail SET jenis_operasi_id = '".$r_ops['jenis_operasi_id']."', jenis_operasi_name = '".$r_ops['jenis_operasi_name']."', price_eob_per_hour = '".$r_eob_hours['price']."', total_price_eob = '".$total_price_eob."' WHERE afml_detail_id = '".$r_afml['afml_detail_id']."' "; 
    //echo $s_update_eob.'<br/>';   
    mysqli_query($conn, $s_update_eob);

    /*------END link eob ke route_from dan jenis operasinya------*/
    //echo '<b>Pilot:</b> '.$r_pilot['full_name'].' ('.$r_pilot['user_id'].'), <b>Copilot:</b> '.$r_copilot['full_name'].', <b>EOB:</b> '.$r_eob['full_name'].'<br/>';
    //echo $s_update_pilot.'<br/>';
    //echo $s_update_copilot.'<br/>';
    //echo $s_update_eob.'<br/>';

    //echo '<hr/>';
    echo '<b>AFML ID:</b> '.$r_afml['afml_detail_id'].'<b> Page:</b> '.$r_afml['afml_page_no'].'. <b>Name:</b> '.$r_pilot['full_name'].'<br/>';
    echo '<b>From:</b> '.$r_afml['afml_route_from'].'. <b>Masuk kategori:</b> '.$r_ops['jenis_operasi_name'].'. <b>Uang terbang:</b> Rp. '.$r_pilot_hours['price'].' (per jam: Rp. '.number_format($price_pilot_per_minute,0,",",".").') <br/><hr/>';

}
exit();

/*ini versi aslinya. yang update ke table tbl_afml_price

$sql1           = "SELECT * FROM tbl_afml_price WHERE jenis_operasi_id = 0";
$h1             = mysqli_query($conn, $sql1);
while($row1 = mysqli_fetch_assoc($h1)) {

    //link afml_detail
    $sql2       = "SELECT afml_detail_id, afml_route_from, afml_flt_hrs FROM tbl_afml_detail WHERE afml_detail_id = '".$row1['afml_detail_id']."'";
    $h2         = mysqli_query($conn, $sql2);
    $row2       = mysqli_fetch_assoc($h2);

    //link ke jenis_operasi_id, dia masuk kategori apa
    $sql_ops    = "SELECT * FROM tbl_master_iata INNER JOIN tbl_jenis_operasi_iata USING (master_iata_id)  INNER JOIN tbl_jenis_operasi USING (jenis_operasi_id)  WHERE (iata_code = '".$row2['afml_route_from']."' OR icao_code = '".$row2['afml_route_from']."')";
    $h_ops      = mysqli_query($conn, $sql_ops);
    $r_ops      = mysqli_fetch_assoc($h_ops);

    //link pilot ke route_from dan jenis operasinya
    $sql_pilot_hours= "SELECT price,start_date, end_date FROM tbl_jenis_operasi_user a INNER JOIN tbl_jenis_operasi_iata b USING (jenis_operasi_id) WHERE a.user_id = '".$row1['user_id']."' AND b.master_iata_id = '".$r_ops['master_iata_id']."' and end_date = '0000-00-00'";
    $h_pilot_hours  = mysqli_query($conn, $sql_pilot_hours);
    $r_pilot_hours  = mysqli_fetch_assoc($h_pilot_hours);

    $uang_per_menit = round(($r_pilot_hours['price']/60),2);
    $total_uang_terbang = round(($uang_per_menit * $row2['afml_flt_hrs']),2);

    echo 'user_id: '.$row1['user_id'].' - page no: '.$row1['afml_page_no'].' - detail id: '.$row1['afml_detail_id'].' - from: '.$row2['afml_route_from'].' - hrs: '.$row2['afml_flt_hrs'];
    echo '<br/>';
    echo '<b>Masuk kategori:</b> '.$r_ops['jenis_operasi_name'].'. <b>Uang terbang:</b> Rp. '.$r_pilot_hours['price'].' (per menit: Rp. '.number_format($uang_per_menit,0,",",".").') <br/>';
    echo '<b>TOTAL UANG TERBANG: '.$total_uang_terbang.'</b>';

    $sql3       = "UPDATE tbl_afml_price SET jenis_operasi_id = '".$r_ops['jenis_operasi_id']."', jenis_operasi_name = '".$r_ops['jenis_operasi_name']."', price_per_hour = '".$r_pilot_hours['price']."', total_price = '".$total_uang_terbang."' WHERE id = '".$row1['id']."'";
    mysqli_query($conn, $sql3);
    echo '<br/>'.$sql3.'<br/><hr/>';
}*/
?>