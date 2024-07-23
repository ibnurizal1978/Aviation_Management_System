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
//grab afml detail
$sql_afml       = "SELECT afml_detail_id,afml_captain_user_id, afml_copilot_user_id, afml_engineer_on_board_user_id, afml_page_no, afml_route_from, afml_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no)  WHERE a.client_id = '".$_SESSION['client_id']."' AND jenis_operasi_id = 0 ORDER by a.afml_detail_id"; 
    // month(a.afml_date) = month(NOW())  AND year(a.afml_date) = year(NOW()) AND
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
    $sql_pilot_hours= "SELECT price,start_date, end_date FROM tbl_jenis_operasi_user a INNER JOIN tbl_jenis_operasi_iata b USING (jenis_operasi_id) WHERE a.user_id = '".$r_pilot['user_id']."' AND b.master_iata_id = '".$r_ops['master_iata_id']."'";
    $h_pilot_hours  = mysqli_query($conn, $sql_pilot_hours);
    $r_pilot_hours  = mysqli_fetch_assoc($h_pilot_hours);

    $price_pilot_per_minute = round(($r_pilot_hours['price']/60),2);
    $total_price_pilot = round(($price_pilot_per_minute * $r_afml['afml_flt_hrs']),2);

    $s_update_pilot = "UPDATE tbl_afml_detail SET jenis_operasi_id = '".$r_ops['jenis_operasi_id']."', jenis_operasi_name = '".$r_ops['jenis_operasi_name']."', price_pilot_per_hour = '".$r_pilot_hours['price']."', total_price_pilot = '".$total_price_pilot."' WHERE jenis_operasi_id = 0 AND afml_detail_id = '".$r_afml['afml_detail_id']."' ";    
    mysqli_query($conn, $s_update_pilot);
    /*------END link pilot ke route_from dan jenis operasinya------*/

    /*------link copilot ke route_from dan jenis operasinya------*/
    $sql_copilot_hours= "SELECT price,start_date, end_date FROM tbl_jenis_operasi_user a INNER JOIN tbl_jenis_operasi_iata b USING (jenis_operasi_id) WHERE a.user_id = '".$r_copilot['user_id']."' AND b.master_iata_id = '".$r_ops['master_iata_id']."'";
    $h_copilot_hours  = mysqli_query($conn, $sql_copilot_hours);
    $r_copilot_hours  = mysqli_fetch_assoc($h_copilot_hours);

    $price_copilot_per_minute = round(($r_copilot_hours['price']/60),2);
    $total_price_copilot = round(($price_copilot_per_minute * $r_afml['afml_flt_hrs']),2);

    $s_update_copilot = "UPDATE tbl_afml_detail SET jenis_operasi_id = '".$r_ops['jenis_operasi_id']."', jenis_operasi_name = '".$r_ops['jenis_operasi_name']."', price_copilot_per_hour = '".$r_copilot_hours['price']."', total_price_copilot = '".$total_price_copilot."' WHERE jenis_operasi_id = 0 AND afml_detail_id = '".$r_afml['afml_detail_id']."' ";    
    mysqli_query($conn, $s_update_pilot);
    /*------END link copilot ke route_from dan jenis operasinya------*/


    /*------link eob ke route_from dan jenis operasinya------*/
    $sql_eob_hours= "SELECT price,start_date, end_date FROM tbl_jenis_operasi_user a INNER JOIN tbl_jenis_operasi_iata b USING (jenis_operasi_id) WHERE a.user_id = '".$r_eob['user_id']."' AND b.master_iata_id = '".$r_ops['master_iata_id']."'";
    $h_eob_hours  = mysqli_query($conn, $sql_eob_hours);
    $r_eob_hours  = mysqli_fetch_assoc($h_eob_hours);

    $price_eob_per_minute = round(($r_eob_hours['price']/60),2);
    $total_price_eob = round(($price_eob_per_minute * $r_afml['afml_flt_hrs']),2);

    $s_update_eob = "UPDATE tbl_afml_detail SET jenis_operasi_id = '".$r_ops['jenis_operasi_id']."', jenis_operasi_name = '".$r_ops['jenis_operasi_name']."', price_eob_per_hour = '".$r_eob_hours['price']."', total_price_eob = '".$total_price_eob."' WHERE jenis_operasi_id = 0 AND afml_detail_id = '".$r_afml['afml_detail_id']."' ";    
    mysqli_query($conn, $s_update_eob);

    /*------END link eob ke route_from dan jenis operasinya------*/
    echo '<b>Pilot:</b> '.$r_pilot['full_name'].', <b>Copilot:</b> '.$r_copilot['full_name'].', <b>EOB:</b> '.$r_eob['full_name'].'<br/>';
    echo $s_update_pilot.'<br/>';
    echo $s_update_copilot.'<br/>';
    echo $s_update_eob.'<br/>';

    echo '<hr/>';
    //echo '<b>AFML ID:</b> '.$r_afml['afml_detail_id'].'<b> Page:</b> '.$r_afml['afml_page_no'].'. <b>Name:</b> '.$r_pilot['full_name'].'<br/>';
    //echo '<b>From:</b> '.$r_afml['afml_route_from'].' ('.$afml_flt_hrs.' / '.$r_afml['afml_flt_hrs'].'). <b>Masuk kategori:</b> '.$r_ops['jenis_operasi_name'].'. <b>Uang terbang:</b> Rp. '.$r_pilot_hours['price'].' (per jam: Rp. '.number_format($price_pilot_per_minute,0,",",".").') <br/><hr/>';

}
exit();
//cari full name
$sql  = "SELECT user_id,full_name, lic_no FROM tbl_user WHERE (user_id = '".$row_tot['afml_captain_user_id']."' OR user_id = '".$row_tot['afml_copilot_user_id']."') AND user_active_status = 1 AND client_id = '".$_SESSION['client_id']."'";
$h    = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($h);

//cari mapping iata dan jenis operasi
$sql_ij = "SELECT master_iata_id FROM tbl_master_iata a  INNER JOIN tbl_jenis_operasi_iata b USING (jenis_operasi_id)  WHERE (a.iata_code = '".$row_tot['afml_route_from']."' OR a.icao_code = '".$row_tot['afml_route_from']."')";

$h_ij   = mysqli_query($conn, $sql_ij);
$row_ij   = mysqli_fetch_assoc($h_ij);                                
?>                                
                                <tr>
                                    <td>
                                        <a class="font-w600" href="be_pages_ecom_order.html"><?php //echo $row['lic_no'] ?></a>
                                    </td>
                                    <td><?php //echo $row['full_name'] ?></td> 
                                    <td><?php //echo $row['afml_route_from'] ?></td>
                                    <td class="text-right">
                                        <span class="text-black"><?php //echo minutesToHours($this_mth_flt_hrs) ?></a></span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-black"><?php // echo minutesToHours($total_flt_hrs) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="report-pilot-hours-detail.php?act=29dvi59&ntf=29dvi59-<?php //echo $row["user_id"]?>-94dfvj!sdf-349ffuaw">Detail</a>
                                    </td>                                    
                                </tr>
                            <?php //} ?>
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