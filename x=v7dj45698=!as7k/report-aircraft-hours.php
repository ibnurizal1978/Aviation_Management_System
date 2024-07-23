<?php 
require_once 'header.php';
ini_set('display_errors',1);  error_reporting(E_ALL);
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
                    <form action="report-aircraft-hours.php" method="GET">
                        <input type="hidden" name="s" value="1" />
                        <div class="form-group mb-15"> 
                            <div class="form-material">
                                <select class="form-control" required name="bulan">
                                    <option value=""> -- Choose Month (Year <?php echo date('Y') ?>)-- </option>
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
                                <label for="material-select2">Select Month</label>
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
                        <table class="table table-borderless table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="text-right">Total Hrs</th>
                                    <th class="text-right">This Month Hrs</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql  = "SELECT aircraft_reg_code, aircraft_ac_total_hrs FROM tbl_aircraft_master ORDER BY aircraft_reg_code";
                                $h    = mysqli_query($conn,$sql);

                                if(@$_GET['s']==1) {
                                    $bulan = $_GET['bulan'];
                                }else{
                                    $bulan = date('m');
                                }
                                while($row = mysqli_fetch_assoc($h)) {

                                    $sql_tot = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE b.aircraft_reg_code = '".$row['aircraft_reg_code']."' AND month(a.afml_date) = '".$bulan."'  AND year(a.afml_date) = year(NOW()) AND a.client_id = '".$_SESSION['client_id']."'";
                                    $h_tot     = mysqli_query($conn, $sql_tot);
                                    $row_tot   = mysqli_fetch_assoc($h_tot);
                                    $this_mth_flt_hrs      = $row_tot['total_flt_hrs'];

                                    $sql_tot2 = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE b.aircraft_reg_code = '".$row['aircraft_reg_code']."' AND a.client_id = '".$_SESSION['client_id']."'";
                                    $h_tot2     = mysqli_query($conn, $sql_tot2);
                                    $row_tot2   = mysqli_fetch_assoc($h_tot2);

                                    $total_flt_hrs = $row_tot2['total_flt_hrs'];
                                ?>                                
                                <tr>
                                    <td><?php echo $row['aircraft_reg_code'] ?></td> 
                                    <td class="text-right">
                                        <span class="text-black"><?php echo minutesToHours($row['aircraft_ac_total_hrs']) ?></span>
                                    </td>                                    
                                    <td class="text-right">
                                        <span class="text-black"><?php echo minutesToHours($this_mth_flt_hrs) ?></a></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="report-aircraft-hours-detail.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_reg_code"]?>-<?php echo $bulan ?>-94dfvj!sdf-349ffuaw">Detail</a>
                                    </td>                                    
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

       

        <!--graphic-->
        <!--<div class="block table-responsive">
            <div class="block-content">
                <div class="row items-push-2x text-center invisible" data-toggle="appear">
                    <div class="col-6 col-md-6">
                        <div id="barchart_material" style="width: 500px; height: 500px;"></div>
                    </div>
                    <div class="col-6 col-md-6">
                        <span class="js-slc-pie2">1500,1350,1280,800</span>
                    </div>
                </div>
            </div>
        </div>-->
        <!--end graphic-->

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