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
                                    <th class="text-right">This Month Hrs</th>
                                    <th class="text-right">Total Hrs</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql  = "SELECT user_id,full_name, lic_no FROM tbl_user WHERE department_id = 4 AND user_active_status = 1 AND user_position LIKE '%pilot' AND client_id = '".$_SESSION['client_id']."'";
                                $h    = mysqli_query($conn,$sql);
                                while($row = mysqli_fetch_assoc($h)) {
                                
                                /*-------------------------------------------------------*/

                                    //hitung hrs untuk bulan ini - sebagai captain
                                    $sql_mth1   = "SELECT SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id) = '".$row['user_id']."' AND month(b.afml_date) = 01 AND year(b.afml_date) = 2020
                                     AND a.client_id = '".$_SESSION['client_id']."'";
                                    $h_mth1     = mysqli_query($conn, $sql_mth1);
                                    $row_mth1   = mysqli_fetch_assoc($h_mth1);

                                    //hitung hrs untuk bulan ini - sebagai copilot
                                    $sql_mth2   = "SELECT SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_copilot_user_id) = '".$row['user_id']."' AND month(b.afml_date) = 01 AND year(b.afml_date) = 2020
                                    AND a.client_id = '".$_SESSION['client_id']."'";
                                    $h_mth2     = mysqli_query($conn, $sql_mth2);
                                    $row_mth2   = mysqli_fetch_assoc($h_mth2);

                                    $this_mth_flt_hrs      = $row_mth1['total_flt_hrs'] + $row_mth2['total_flt_hrs'];

                                /*---------------------------------------------------------*/

                                    //hitung total hrs untuk TOTAL - sebagai captain
                                    $sql_tot1   = "SELECT SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id) = '".$row['user_id']."' AND a.client_id = '".$_SESSION['client_id']."'";
                                    $h_tot1     = mysqli_query($conn, $sql_tot1);
                                    $row_tot1   = mysqli_fetch_assoc($h_tot1);

                                    //hitung total hrs untuk  TOTAL - sebagai copilot
                                    $sql_tot2   = "SELECT SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_copilot_user_id) = '".$row['user_id']."' AND a.client_id = '".$_SESSION['client_id']."'";
                                    $h_tot2     = mysqli_query($conn, $sql_tot2);
                                    $row_tot2   = mysqli_fetch_assoc($h_tot2);

                                    $total_flt_hrs      = $row_tot1['total_flt_hrs'] + $row_tot2['total_flt_hrs'];

                                /*--------------------------------------------------------*/                                   
                                ?>                                
                                <tr>
                                    <td>
                                        <a class="font-w600" href="be_pages_ecom_order.html"><?php echo $row['user_id'] ?></a>
                                    </td>
                                    <td><?php echo $row['full_name'] ?></td> 
                                    <td class="text-right">
                                        <span class="text-black"><?php echo minutesToHours($this_mth_flt_hrs) ?></a></span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-black"><?php echo minutesToHours($total_flt_hrs) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-success">Detail</a>
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