<?php 
require_once 'header.php';
//require_once 'components.php';

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
?>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Block Hrs', 'Flt Hrs'],
          ['2014', 1000, 400, 200],
          ['2015', 1170, 460, 250],
          ['2016', 660, 1120, 300],
          ['2017', 1030, 540, 350]
        ]);

        var options = {
          chart: {
            title: 'Pilot Hours By Month',
            subtitle: '',
          },
          bars: 'vertical' // Required for Material Bar Charts.
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>

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
                    <form action="afml-pilot-hours.php" method="GET">
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
                                    <option value=""> -- Choose -- </option>
                                    <?php
                                    $sql  = "SELECT user_id,full_name FROM tbl_user WHERE department_id = 4 AND client_id = '".$_SESSION['client_id']."' ORDER BY full_name";
                                    echo $sql;
                                    $h    = mysqli_query($conn,$sql);
                                    while($row = mysqli_fetch_assoc($h)) {
                                    ?>
                                    <option value="<?php echo $row['user_id'] ?>"><?php echo $row['full_name'] ?></option>
                                <?php } ?>
                              </select>
                                <label for="material-select2">Crew</label>
                            </div>                                                       
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-block btn-alt-primary">
                                    <i class="si si-cloud-download mr-5"></i> Download
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
                    <h3 class="block-title">Summary</h3>
                </div>
                <div class="block block-rounded">
                    <div class="block-content">
                        <table class="table table-borderless table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">LIC No.</th>
                                    <th>Name</th>
                                    <th>From</th>
                                    <th class="text-right">This Month Hrs</th>
                                    <th class="text-right">Total Hrs</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
$sql1           = "SELECT * FROM tbl_afml_price WHERE jenis_operasi_id = 0";
$h1             = mysqli_query($conn, $sql1);
while($row1 = mysqli_fetch_assoc($h1)) {

    //link afml_detail
    $sql2       = "SELECT afml_detail_id, afml_route_from, afml_flt_hrs FROM tbl_afml_detail WHERE afml_detail_id = '".$row1['afml_detail_id']."' AND client_id = '".$_SESSION['client_id']."'";
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
}


exit();
?>
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