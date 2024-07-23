<?php 
require_once 'header.php';
//require_once 'components.php';

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
$sql    = "SELECT user_id,full_name, lic_no FROM tbl_user WHERE user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."'";
$h      = mysqli_query($conn,$sql);
$row    = mysqli_fetch_assoc($h);

//hitung total hrs untuk range tanggal yang dipilih - sebagai captain
$sql_tot1   = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id) = '".$user_id."' AND $range_tgl AND a.client_id = '".$_SESSION['client_id']."'";
$h_tot1     = mysqli_query($conn, $sql_tot1);
$row_tot1   = mysqli_fetch_assoc($h_tot1);

//hitung total hrs untuk range tanggal yang dipilih - sebagai copilot
$sql_tot2   = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_copilot_user_id) = '".$user_id."' AND $range_tgl AND a.client_id = '".$_SESSION['client_id']."'";
$h_tot2     = mysqli_query($conn, $sql_tot2);
$row_tot2   = mysqli_fetch_assoc($h_tot2);

$total_block_hrs    = $row_tot1['total_block_hrs'] + $row_tot2['total_block_hrs'];
$total_flt_hrs      = $row_tot1['total_flt_hrs'] + $row_tot2['total_flt_hrs'];


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

/*--query jam A/C hours--*/
$s1 = "SELECT aircraft_reg_code, aircraft_ac_total_hrs FROM tbl_aircraft_master WHERE client_id = '".$_SESSION['client_id']."' order by aircraft_ac_total_hrs";
$h1 = mysqli_query($conn, $s1);
$source1    = "";
while($r1   = mysqli_fetch_assoc($h1)) {
    $afml_flt_hrs = minutesToHours($r1['aircraft_ac_total_hrs']);
    $source1 .= "['".$r1['aircraft_reg_code']."','".$afml_flt_hrs."','#b87333'],";
}
$source1    = substr($source1,0,-1);

/*--query LDG cycle--*/ 
$s2 = "SELECT aircraft_reg_code, sum(aircraft_ac_total_ldg) as total FROM tbl_aircraft_master WHERE client_id = '".$_SESSION['client_id']."' GROUP BY aircraft_reg_code ORDER BY total";
$h2 = mysqli_query($conn, $s2);
$source2    = "";
while($r2   = mysqli_fetch_assoc($h2)) {
    $source2 .= "['".$r2['aircraft_reg_code']."','".$r2['total']."','#ff660a'],";
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
        ['A/C', 'Hour', { role: 'style' }],
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
  google.charts.load('current', {'packages':['bar']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['A/C', 'LDG Cycle', { role: 'style' }],
        <?php echo $source2 ?>,
    ]);

    var options = {
      chart: {
        title: '',
        subtitle: '',
      },
      bars: 'vertical' // Required for Material Bar Charts.
    };

    var chart = new google.charts.Bar(document.getElementById('barchart_material2'));

    chart.draw(data, google.charts.Bar.convertOptions(options));
  }
</script>


<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">

        <div class="row invisible" data-toggle="appear">
            <!-- Row #2 -->
            <div class="col-md-12">
                <div class="block block-themed block-mode-loading-refresh">
                    <div class="block-header bg-gd-emerald">
                        <h3 class="block-title">
                            Total A/C Hours
                        </h3>
                    </div>
                    <div class="block-content block-content-full text-center">
                        <div class="pull-all">
                            <div id="barchart_material1" style="width: 90%; height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row invisible" data-toggle="appear">
            <div class="col-md-12">
                <div class="block block-themed block-mode-loading-refresh">
                    <div class="block-header bg-gd-emerald">
                        <h3 class="block-title">
                            LDG Cycle
                        </h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="pull-all">               
                            <div id="barchart_material2" style="width: 90%; height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Row #2 -->
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