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
                    <form action="report-personal-hours.php" method="GET">
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
                                    <th class="text-right">Money (IDR)</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql  = "SELECT user_id,full_name, department_id FROM tbl_user WHERE user_id = '".$_SESSION['user_id']."' AND user_active_status = 1 AND client_id = '".$_SESSION['client_id']."' ORDER BY full_name";
                                $h    = mysqli_query($conn,$sql);

                                if(@$_GET['s']==1) {
                                    $bulan = $_GET['bulan'];
                                }else{
                                    $bulan = date('m');
                                }
                                while($row = mysqli_fetch_assoc($h)) {

                                    if($row['department_id']==4) { //pilot
                                        $sql_tot = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id = '".$row['user_id']."' OR b.afml_copilot_user_id = '".$row['user_id']."') AND month(a.afml_date) = '".$bulan."'  AND year(a.afml_date) = year(NOW()) AND a.client_id = '".$_SESSION['client_id']."'";
                                        $h_tot     = mysqli_query($conn, $sql_tot);
                                        $row_tot   = mysqli_fetch_assoc($h_tot);
                                        $this_mth_flt_hrs      = $row_tot['total_flt_hrs'];

                                        $sql_tot2 = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id = '".$row['user_id']."' OR b.afml_copilot_user_id = '".$row['user_id']."') AND a.client_id = '".$_SESSION['client_id']."'";
                                        $h_tot2     = mysqli_query($conn, $sql_tot2);
                                        $row_tot2   = mysqli_fetch_assoc($h_tot2);

                                        $total_flt_hrs = $row_tot2['total_flt_hrs'];

                                        $sql_price_pilot  = "SELECT sum(total_price_pilot) as total_price_pilot FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE b.afml_captain_user_id = '".$row['user_id']."' AND month(a.afml_date) = '".$bulan."'  AND year(a.afml_date) = year(NOW())";
                                        $h_price_pilot    = mysqli_query($conn, $sql_price_pilot);
                                        $row_price_pilot  = mysqli_fetch_assoc($h_price_pilot);

                                        $sql_price_copilot  = "SELECT sum(total_price_copilot) as total_price_copilot FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE b.afml_copilot_user_id = '".$row['user_id']."' AND month(a.afml_date) = '".$bulan."'  AND year(a.afml_date) = year(NOW())";
                                        $h_price_copilot    = mysqli_query($conn, $sql_price_copilot);
                                        $row_price_copilot  = mysqli_fetch_assoc($h_price_copilot);

                                        $total_price = $row_price_pilot['total_price_pilot'] + $row_price_copilot['total_price_copilot'];
                                    
                                    }else{
                                    
                                        $sql_tot = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE b.afml_engineer_on_board_user_id = '".$row['user_id']."' AND month(a.afml_date) = '".$bulan."'  AND year(a.afml_date) = year(NOW()) AND a.client_id = '".$_SESSION['client_id']."'";
                                        $h_tot     = mysqli_query($conn, $sql_tot);
                                        $row_tot   = mysqli_fetch_assoc($h_tot);
                                        $this_mth_flt_hrs      = $row_tot['total_flt_hrs'];

                                        $sql_tot2 = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE b.afml_engineer_on_board_user_id = '".$row['user_id']."' AND a.client_id = '".$_SESSION['client_id']."'";
                                        $h_tot2     = mysqli_query($conn, $sql_tot2);
                                        $row_tot2   = mysqli_fetch_assoc($h_tot2);

                                        $total_flt_hrs = $row_tot2['total_flt_hrs'];

                                        $sql_price_pilot  = "SELECT sum(total_price_eob) as total_price FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE b.afml_engineer_on_board_user_id = '".$row['user_id']."' AND month(a.afml_date) = '".$bulan."'  AND year(a.afml_date) = year(NOW())";
                                        $h_price_pilot    = mysqli_query($conn, $sql_price_pilot);
                                        $row_price_pilot  = mysqli_fetch_assoc($h_price_pilot);

                                        $total_price = $row_price_pilot['total_price'];                                        
                                    }
                                    
                                ?>                                
                                <tr>
                                    <td><?php echo $row['full_name'] ?></td> 
                                    <td class="text-right">
                                        <span class="text-black"><?php echo minutesToHours($total_flt_hrs) ?></span>
                                    </td>                                    
                                    <td class="text-right">
                                        <span class="text-black"><?php echo minutesToHours($this_mth_flt_hrs) ?></a></span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-black"><?php echo number_format($total_price,2,",",".") ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="report-personal-hours-detail.php?act=29dvi59&ntf=29dvi59-<?php echo $row["user_id"]?>-94dfvj!sdf-349ffuaw">Detail</a>
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