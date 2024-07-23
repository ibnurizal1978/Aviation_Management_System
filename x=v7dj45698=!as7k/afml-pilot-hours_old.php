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

$date_from  = input_data(filter_var($_GET['date_from'],FILTER_SANITIZE_STRING));
$date_to    = input_data(filter_var($_GET['date_to'],FILTER_SANITIZE_STRING));
$user_id    = input_data(filter_var($_GET['user_id'],FILTER_SANITIZE_STRING));
//date
$date_from_y   = substr($date_from,6,4);
$date_from_m   = substr($date_from,3,2);
$date_from_d   = substr($date_from,0,2);
$date_from_f   = $date_from_y.'-'.$date_from_m.'-'.$date_from_d;

$date_to_y   = substr($date_to,6,4);
$date_to_m   = substr($date_to,3,2);
$date_to_d   = substr($date_to,0,2);
$date_to_f   = $date_to_y.'-'.$date_to_m.'-'.$date_to_d;    

if (@$_REQUEST['s']=='1'){

    $sql_name   = "SELECT full_name,lic_no FROM tbl_user WHERE user_id = '".$user_id."' LIMIT 1";
    $h_name     = mysqli_query($conn,$sql_name);
    $row_name   = mysqli_fetch_assoc($h_name);           

    //hitung total hrs untuk range tanggal yang dipilih - sebagai captain
    $sql_tot1   = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id) = '".$user_id."' AND (date(a.afml_date) BETWEEN '".$date_from_f."' AND '".$date_to_f."') AND a.client_id = '".$_SESSION['client_id']."'";
    $h_tot1     = mysqli_query($conn, $sql_tot1);
    $row_tot1   = mysqli_fetch_assoc($h_tot1);

    //hitung total hrs untuk range tanggal yang dipilih - sebagai copilot
    $sql_tot2   = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_copilot_user_id) = '".$user_id."' AND (date(a.afml_date) BETWEEN '".$date_from_f."' AND '".$date_to_f."') AND a.client_id = '".$_SESSION['client_id']."'";
    $h_tot2     = mysqli_query($conn, $sql_tot2);
    $row_tot2   = mysqli_fetch_assoc($h_tot2);

    $total_block_hrs    = $row_tot1['total_block_hrs'] + $row_tot2['total_block_hrs'];
    $total_flt_hrs      = $row_tot1['total_flt_hrs'] + $row_tot2['total_flt_hrs'];

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
 
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Flight Hours</h3>
                <div class="block-options">
                    <div class="block-options-item">
                        <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                            <i class="fa fa-filter"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="block-content">
                Name: <?php echo $row_name['full_name'] ?><br/>
                LIC No.: <?php echo $row_name['lic_no'] ?><br/>
                Period: <?php echo $date_from.' to '.$date_to ?><br/>
                <a href="afml-pilot-hours-download.php?act=29dvi59&ntf=29dvi59-<?php echo $user_id ?>-<?php echo $date_from ?>-<?php echo $date_to ?>-94dfvj!sdf-349ffuaw">Download to MS Excel</a>
                <br/><br/> 
                <table class="table table-sm table-vcenter">
                    <thead>
                        <tr>
                            <th>Page No</th>
                            <th>Date of Flight</th>
                            <th>A/C Reg</th>
                            <th>Flight</th>
                            <th>Block</th>
                            <th>Route</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php  
                    $sql    = "SELECT afml_id,afml_page_no,b.aircraft_reg_code,SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs, a.afml_route_from, a.afml_route_to, date_format(a.afml_date, '%d/%m/%Y') as afml_date, full_name FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) INNER JOIN tbl_user c ON b.afml_captain_user_id = c.user_id WHERE (b.afml_captain_user_id = '".$user_id."') AND (date(a.afml_date) BETWEEN '".$date_from_f."' AND '".$date_to_f."') AND a.client_id = '".$_SESSION['client_id']."' GROUP BY date(a.afml_date)";
                    $h      = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($h)) {
                    ?>
                        <tr>
                            <td><a href="afml-detail-view.php?act=29dvi59&ntf=29dvi59-<?php echo $row["afml_id"]?>-94dfvj!sdf-349ffuaw"><?php echo $row['afml_page_no'] ?></a></td>
                            <td><?php echo $row['afml_date'] ?></td>
                            <td><?php echo $row['aircraft_reg_code'] ?></td>
                            <td><?php echo minutesToHours($row['total_flt_hrs']) ?></td>
                            <td><?php echo minutesToHours($row['total_block_hrs']) ?></td>
                            <td><?php echo $row['afml_route_from'].' - '.$row['afml_route_to'] ?></td>
                        </tr>
                    <?php
                    }
                    $sql2 = "SELECT afml_id,afml_page_no,b.aircraft_reg_code,SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs, a.afml_route_from, a.afml_route_to, date_format(a.afml_date, '%d/%m/%Y') as afml_date, full_name FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) INNER JOIN tbl_user c ON b.afml_copilot_user_id = c.user_id WHERE (b.afml_copilot_user_id = '".$user_id."') AND (date(a.afml_date) BETWEEN '".$date_from_f."' AND '".$date_to_f."') AND a.client_id = '".$_SESSION['client_id']."' GROUP BY date(a.afml_date)";
                    $h2      = mysqli_query($conn, $sql2);
                    while($row2 = mysqli_fetch_assoc($h2)) {
                    ?>
                        <tr>
                            <td><a href="afml-detail-view.php?act=29dvi59&ntf=29dvi59-<?php echo $row2["afml_id"]?>-94dfvj!sdf-349ffuaw"><?php echo $row2['afml_page_no'] ?></a></td>
                            <td><?php echo $row2['afml_date'] ?></td>
                            <td><?php echo $row2['aircraft_reg_code'] ?></td>
                            <td><?php echo minutesToHours($row2['total_flt_hrs']) ?></td>
                            <td><?php echo minutesToHours($row2['total_block_hrs']) ?></td>
                            <td><?php echo $row['afml_route_from'].' - '.$row2['afml_route_to'] ?></td>
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
        <!-- END Small Table -->

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