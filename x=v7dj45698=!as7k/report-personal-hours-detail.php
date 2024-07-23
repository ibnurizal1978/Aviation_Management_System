<?php 
require_once 'header.php';
//require_once 'components.php';

if(@$date_from =='') {
    $ket = 'This Month';    
}else{
    $ket = $date_from.' to '.$date_to;
}

if (@$_REQUEST['s']=='1'){
    $date_from  = input_data(filter_var($_GET['date_from'],FILTER_SANITIZE_STRING));
    $date_to    = input_data(filter_var($_GET['date_to'],FILTER_SANITIZE_STRING));
    $user_id    = input_data(filter_var($_GET['user_id'],FILTER_SANITIZE_STRING));     
    $date_from_y   = substr($date_from,6,4);
    $date_from_m   = substr($date_from,3,2);
    $date_from_d   = substr($date_from,0,2);
    $date_from_f   = $date_from_y.'-'.$date_from_m.'-'.$date_from_d;

    $date_to_y   = substr($date_to,6,4);
    $date_to_m   = substr($date_to,3,2);
    $date_to_d   = substr($date_to,0,2);
    $date_to_f   = $date_to_y.'-'.$date_to_m.'-'.$date_to_d;     

    $range_tgl  = "(date(a.afml_date) BETWEEN '".$date_from_f."' AND '".$date_to_f."')";
}else{
    $range_tgl  = "month(a.afml_date) = month(NOW())  AND year(a.afml_date) = year(NOW())";
    $user_id    = $ntf[1];
}

//get adata user
$sql    = "SELECT user_id,full_name, department_id FROM tbl_user WHERE user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."'";
$h      = mysqli_query($conn,$sql);
$row    = mysqli_fetch_assoc($h);

//hitung total hrs untuk range tanggal yang dipilih - sebagai captain
$sql_tot1   = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id = '".$user_id."' OR b.afml_copilot_user_id = '".$user_id."') AND $range_tgl AND a.client_id = '".$_SESSION['client_id']."'";
$h_tot1     = mysqli_query($conn, $sql_tot1);
$row_tot1   = mysqli_fetch_assoc($h_tot1);

//hitung total hrs untuk range tanggal yang dipilih - sebagai copilot
/*$sql_tot2   = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_copilot_user_id) = '".$user_id."' AND $range_tgl AND a.client_id = '".$_SESSION['client_id']."'";
$h_tot2     = mysqli_query($conn, $sql_tot2);
$row_tot2   = mysqli_fetch_assoc($h_tot2);*/

//$total_block_hrs    = $row_tot1['total_block_hrs'] + $row_tot2['total_block_hrs'];
//$total_flt_hrs      = $row_tot1['total_flt_hrs'] + $row_tot2['total_flt_hrs'];

$total_block_hrs    = $row_tot1['total_block_hrs'];
$total_flt_hrs      = $row_tot1['total_flt_hrs'];

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
//jam terbang pesawat: SELECT a.aircraft_reg_code, sum(SEC_TO_TIME(afml_flt_hrs*60)) as afml_flt_hrs, SUBSTRING(SEC_TO_TIME(afml_flt_hrs*60),1,2) as jam, count(SUBSTRING(SEC_TO_TIME(afml_flt_hrs*60),1,2)) as jumlah FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE a.client_id = '1' GROUP BY a.aircraft_reg_code

/*--query jam berapa aja dia T/O di periode tsb--*/
$s1 = "SELECT SEC_TO_TIME(afml_to*60) as afml_to, SUBSTRING(SEC_TO_TIME(afml_to*60),1,2) as jam, count(SUBSTRING(SEC_TO_TIME(afml_to*60),1,2)) as jumlah FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id = '".$user_id."' OR b.afml_copilot_user_id = '".$user_id."') AND $range_tgl AND a.client_id = '1' GROUP BY jam order by jam";
$h1 = mysqli_query($conn, $s1);
$source1    = "";
while($r1   = mysqli_fetch_assoc($h1)) {
  $source1 .= "['".$r1['jam']."',".$r1['jumlah'].",'#b87333'],";
}
$source1    = substr($source1,0,-1);

/*--berapa lama dia terbang group by lamanya jam terbang di periode tsb--*/ 
$s2 = "SELECT SEC_TO_TIME(afml_flt_hrs*60) as afml_flt_hrs, SUBSTRING(SEC_TO_TIME(afml_flt_hrs*60),1,2) as jam, count(SUBSTRING(SEC_TO_TIME(afml_flt_hrs*60),1,2)) as jumlah  FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id = '".$user_id."' OR b.afml_copilot_user_id = '".$user_id."') AND $range_tgl AND a.client_id = '1' GROUP BY jam order by jam";
$h2 = mysqli_query($conn, $s2);
$source2    = "";
while($r2   = mysqli_fetch_assoc($h2)) {
  $source2 .= "['Fly for ".$r2['jam']." hours',".$r2['jumlah']."],";
}
$source2    = substr($source2,0,-1);
?>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!--chart jam berapa aja dia T/O di periode tsb -->    
<script type="text/javascript">
  google.charts.load('current', {'packages':['bar']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Hour', 'Total', { role: 'style' }],
        <?php echo $source1 ?>,
    ]);

    var options = {
      chart: {
        title: '',
        subtitle: '',
      },
      bars: 'vertical' // Required for Material Bar Charts.
    };

    var chart = new google.charts.Bar(document.getElementById('barchart_material1'));

    chart.draw(data, google.charts.Bar.convertOptions(options));
  }
</script>

<!--chart berapa lama dia terbang group by lamanya jam terbang di periode tsb-->
<script type="text/javascript">
  google.charts.load("current", {packages:["corechart"]});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Hour', 'Hour Time'],
      /*['Work',     11], --> format contohnya --*/
        <?php echo $source2 ?>
    ]);

    var options = {
      title: '',
      is3D: true,
    };

    var chart = new google.visualization.PieChart(document.getElementById('barchart_material2'));
    chart.draw(data, options);
  }
</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<!-- Side Overlay-->
<aside id="side-overlay">
    <!-- Side Overlay Scroll Container -->
    <div id="side-overlay-scroll">
        <!-- Side Header -->
        <div class="content-header content-header-fullrow">
            <div class="content-header-section align-parent">
                <button type="button" class="btn btn-circle btn-dual-secondary align-v-r" data-toggle="layout" data-action="side_overlay_close">
                    <i class="fa fa-times text-danger"></i>
                </button>

                <div class="content-header-item">
                    <a class="align-middle link-effect text-primary-dark font-w600" href="#">Filter</a>
                </div>
                <!-- END User Info -->
            </div>
        </div>
        <!-- END Side filter -->

        <!-- side kanan -->
        <div class="content-side">
            <!-- Profile -->
            <div class="block pull-r-l">
                <div class="block-content">
                    <form action="report-personal-hours-detail.php" method="GET">
                        <input type="hidden" name="s" value="1" />
                        <div class="form-group mb-15">
                            <div class="form-material">
                                <input type="text" class="form-control" name="date_from" id="date_from">
                                <label for="material-select2">From date (dd/mm/yyyy)</label>
                            </div>
                            <div class="form-material">
                                <input type="text" class="form-control" name="date_to" id="date_to">
                                <label for="material-select2">To date (dd/mm/yyyy)</label>
                            </div>  
                            <div class="form-material">
                                <select class="form-control" required name="user_id">
                                    <?php
                                    $sql1  = "SELECT user_id,full_name FROM tbl_user WHERE user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' ORDER BY full_name";
                                    $h1    = mysqli_query($conn,$sql1);
                                    while($row1 = mysqli_fetch_assoc($h1)) {
                                    ?>
                                    <option value="<?php echo $row1['user_id'] ?>"><?php echo $row1['full_name'] ?></option>
                                <?php } ?>
                              </select>
                                <label for="material-select2">Crew</label>
                            </div>                                                       
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-block btn-alt-primary">
                                    <i class="si si-cloud-download mr-5"></i> View
                                </button>
                            </div>
                        </div>
                    </form>
            </div>
            <!-- END filter -->
        </div>
        <!-- END Side filter -->
    </div>
    <!-- END Side Overlay Scroll Container -->
</aside>
<!-- END Side Overlay -->

<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">
 
        <div class="row gutters-tiny">
            <div class="col-xl-12">
                <div class="block-header bg-gd-lake">
                    <h3 class="block-title">Detail Flight Data For Crew: <b><?php echo $row['full_name'] ?></b> with period <b><?php echo $ket ?></b></h3>
                        <a href="report-personal-hours.php">Back</a>
                    <div class="block-options">
                        <div class="block-options-item">
                            <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                                <i class="fa fa-filter"></i>
                            </button>
                        </div>
                    </div>                    
                </div>
                <div class="block block-rounded">
                    <div class="block-content">
                        <table class="table table-sm table-vcenter">
                            <thead>
                                <tr>
                                    <th>Page No</th>
                                    <th>Date of Flight</th>
                                    <th>A/C Reg</th>
                                    <th>Flight</th>
                                    <th>Block</th>
                                    <th>Money</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php  
                            $sql    = "SELECT afml_detail_id, afml_id,afml_page_no,b.aircraft_reg_code, afml_block_hrs, afml_flt_hrs, date_format(a.afml_date, '%d/%m/%Y') as afml_date FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id = '".$user_id."' OR b.afml_copilot_user_id = '".$user_id."') AND $range_tgl AND a.client_id = '".$_SESSION['client_id']."'";                          
                            $h      = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_assoc($h)) {

                                if($_SESSION['department_id']==4) { //pilot
                                    $sql_price_pilot  = "SELECT sum(total_price_pilot) as total_price_pilot FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE b.afml_captain_user_id = '".$row['user_id']."' AND month(a.afml_date) = '".$bulan."'  AND year(a.afml_date) = year(NOW())";
                                    $h_price_pilot    = mysqli_query($conn, $sql_price_pilot);
                                    $row_price_pilot  = mysqli_fetch_assoc($h_price_pilot);

                                    $sql_price_copilot  = "SELECT sum(total_price_copilot) as total_price_copilot FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE b.afml_copilot_user_id = '".$row['user_id']."' AND month(a.afml_date) = '".$bulan."'  AND year(a.afml_date) = year(NOW())";
                                    $h_price_copilot    = mysqli_query($conn, $sql_price_copilot);
                                    $row_price_copilot  = mysqli_fetch_assoc($h_price_copilot);

                                    $total_price = $row_price_pilot['total_price_pilot'] + $row_price_copilot['total_price_copilot'];

                            }else{

                                $sql_price_pilot  = "SELECT sum(total_price_pilot) as total_price_pilot FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE afml_detail_id = '".$row['afml_detail_id']."' AND b.afml_engineer_on_board_user_id = '".$user_id."' AND $range_tgl";
                                $h_price_pilot    = mysqli_query($conn, $sql_price_pilot);
                                $row_price_pilot  = mysqli_fetch_assoc($h_price_pilot);
                                $total_price = $row_price_pilot['total_price_pilot'];                                
                            }
                             
                            ?>
                                <tr>
                                    <td><a href="afml-detail-view.php?act=29dvi59&ntf=29dvi59-<?php echo $row["afml_id"]?>-94dfvj!sdf-349ffuaw"><?php echo $row['afml_page_no'] ?></a></td>
                                    <td><?php echo $row['afml_date'] ?></td>
                                    <td><?php echo $row['aircraft_reg_code'] ?></td>
                                    <td><?php echo minutesToHours($row['afml_flt_hrs']) ?></td>
                                    <td>
                                        <?php 
                                        if($row['afml_block_hrs']<$row['afml_flt_hrs']) {
                                            echo '<b class=text-danger>'.minutesToHours($row['afml_block_hrs']).'<b>';
                                        }else{
                                            echo minutesToHours($row['afml_block_hrs']); 
                                        }?>
                                    </td>
                                    <td>
                                        <span class="text-black"><?php echo number_format($total_price,2,",",".") ?></span>
                                    </td>                                    
                                </tr>
                            <?php } ?>
                                <tr style="font-weight: bold">
                                    <td colspan="3">TOTAL</td>
                                    <td><?php echo minutesToHours($total_flt_hrs) ?></td>
                                    <td><?php echo minutesToHours($total_block_hrs) ?></td>
                                    <td>&nbsp;</td>
                                </tr>
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
<script type="text/javascript">
$(document).ready(function(){

  $("#date_from").attr("maxlength", 10);
  $("#date_from").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });

  $("#date_to").attr("maxlength", 10);
  $("#date_to").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });           

});    
</script>