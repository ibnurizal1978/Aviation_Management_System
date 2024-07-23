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
<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">

    <?php if(@$ntf[0]==1) { ?>
      <div class="alert alert-danger alert-dismissable" role="alert">
          <p class="mb-0">Please fill form</p>
      </div>
    <?php } ?> 

    <?php if(@$ntf[0]==2) { ?>
      <div class="alert alert-danger alert-dismissable" role="alert">
          <p class="mb-0">Duplicate AFML Page Number</p>
      </div>
    <?php } ?>         

    <?php if(@$ntf[0]==999) { ?>
      <div class="alert alert-success alert-dismissable" role="alert">
          <p class="mb-0">Yeayy. Success!</p>
      </div>
    <?php } ?>

    <!-- Small Table -->
    <!--begin list data-->
    <?php
    $sql = "SELECT *,date_format(a.afml_log_date, '%d/%m/%Y on %H:%i') as afml_log_date FROM tbl_afml_log a INNER JOIN tbl_user b ON a.user_id = b.user_id WHERE afml_id = '".$ntf[1]."' AND a.client_id = '".$_SESSION['client_id']."' ORDER BY afml_log_date DESC";
    $h = mysqli_query($conn, $sql);
    if(mysqli_num_rows($h)==0) {
    ?>
      <div class="alert alert-info ks-solid text-center" role="alert">Oops, no data found</div>
    <?php } ?>    
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Log Data for AFML | <a href="afml-change.php">Go to AFML List</a></h3>
            </div>
            <div class="block-content">
                <table border="1" width="100%" style="font-size: 10px; text-align: center;">
                    <thead>
                        <tr>
                          <th rowspan="2">Modified Date</th>
                          <th rowspan="2">Modified By</th>
                          <th colspan="2">Page No.</th>
                          <th colspan="2">Date</th>
                          <th colspan="2">A/C Reg</th>
                          <th colspan="2">A/C SN</th>
                          <th colspan="2">Captain</th>
                          <th colspan="2">Copilot</th>
                          <th colspan="2">Engineer</th>
                          <th colspan="2">ECTM Time</th>
                          <th colspan="2">Alt</th>
                          <th colspan="2">IAS</th>
                          <th colspan="2">TQ</th>
                          <th colspan="2">ITT</th>
                          <th colspan="2">NG</th>
                          <th colspan="2">NP</th>
                          <th colspan="2">FF</th>
                          <th colspan="2">Oil Temp</th>
                          <th colspan="2">Oil Press</th>
                          <th colspan="2">OAT</th>
                        </tr>
                        <tr>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Olg</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Old</th>
                            <th>New</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($h)) { ?>
                        <tr>
                            <td><?php echo $row['afml_log_date'] ?></td>
                            <td><?php echo $row['full_name'] ?></td>
                            <td><?php echo $row['afml_page_no_old'] ?></td>
                            <td><?php echo $row['afml_page_no_new'] ?></td>
                            <td><?php echo $row['afml_date_old'] ?></td>
                            <td><?php echo $row['afml_date_new'] ?></td>
                            <td><?php echo $row['aircraft_reg_code_old'] ?></td>
                            <td><?php echo $row['aircraft_reg_code_new'] ?></td>
                            <td><?php echo $row['aircraft_serial_number_old'] ?></td>
                            <td><?php echo $row['aircraft_serial_number_new'] ?></td>
                            <td><?php echo $row['afml_captain_user_id_old'] ?></td>
                            <td><?php echo $row['afml_captain_user_id_new'] ?></td>
                            <td><?php echo $row['afml_copilot_user_id_old'] ?></td>
                            <td><?php echo $row['afml_copilot_user_id_new'] ?></td>
                            <td><?php echo $row['afml_engineer_on_board_user_id_old'] ?></td>
                            <td><?php echo $row['afml_engineer_on_board_user_id_new'] ?></td>
                            <td><?php echo $row['ectm_time_old'] ?></td>
                            <td><?php echo $row['ectm_time_new'] ?></td>
                            <td><?php echo $row['ectm_altitude_old'] ?></td>
                            <td><?php echo $row['ectm_altitude_new'] ?></td>
                            <td><?php echo $row['ectm_ias_old'] ?></td>
                            <td><?php echo $row['ectm_ias_new'] ?></td>
                            <td><?php echo $row['ectm_tq_old'] ?></td>
                            <td><?php echo $row['ectm_tq_new'] ?></td>
                            <td><?php echo $row['ectm_itt_old'] ?></td>
                            <td><?php echo $row['ectm_itt_new'] ?></td>
                            <td><?php echo $row['ectm_ng_old'] ?></td>
                            <td><?php echo $row['ectm_ng_new'] ?></td>
                            <td><?php echo $row['ectm_np_old'] ?></td>
                            <td><?php echo $row['ectm_np_new'] ?></td>
                            <td><?php echo $row['ectm_ff_old'] ?></td>
                            <td><?php echo $row['ectm_ff_new'] ?></td>
                            <td><?php echo $row['ectm_oil_temp_old'] ?></td>
                            <td><?php echo $row['ectm_oil_temp_new'] ?></td>
                            <td><?php echo $row['ectm_oil_press_old'] ?></td>
                            <td><?php echo $row['ectm_oil_press_new'] ?></td>
                            <td><?php echo $row['ectm_oat_old'] ?></td>
                            <td><?php echo $row['ectm_oat_new'] ?></td>
                        </tr>
                        <?php } mysqli_free_result($h); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br/>
    <?php
    $sql = "SELECT *,date_format(a.created_date, '%d/%m/%Y on %H:%i') as created_date FROM tbl_afml_detail_log a INNER JOIN tbl_user b ON a.user_id = b.user_id WHERE afml_page_no = '".$ntf[2]."' AND a.client_id = '".$_SESSION['client_id']."' ORDER BY created_date DESC";
      $h = mysqli_query($conn, $sql);
    if(mysqli_num_rows($h)==0) {
    ?>
      <div class="alert alert-info ks-solid text-center" role="alert">Oops, no data found</div>
    <?php } ?>    
        <div class="block table-responsive">
          <div class="block-header block-header-default">
              <h3 class="block-title">Log Data for Hours Detail | <a href="afml-change-hour.php?act=29dvi59&ntf=29dvi59-<?php echo $ntf[1]?>-<?php echo $ntf[2]?>-94dfvj!sdf-349ffuaw">Go to AFML Detail</a></h3>
          </div>
          <div class="block-content">
            <table border="1" width="100%" style="font-size: 10px; text-align: center;">
                <thead>
                    <tr>
                      <th rowspan="2">ID</th>
                      <th rowspan="2">Modified Date</th>
                      <th rowspan="2">Modified By</th>                      
                      <th colspan="2">Route From</th>
                      <th colspan="2">Route To</th>
                      <th colspan="2">Block On</th>
                      <th colspan="2">Block Off</th>
                      <th colspan="2">Block Hrs</th>
                      <th colspan="2">T/O</th>
                      <th colspan="2">LDG</th>
                      <th colspan="2">Flt Hrs</th>
                      <th colspan="2">Ldg Cycle</th>
                      <th colspan="2">Rem</th>
                      <th colspan="2">Uplift</th>
                      <th colspan="2">Total</th>
                      <th colspan="2">Receipt No.</th>
                      <th colspan="2">Added Oil</th>
                      <th colspan="2">Added Hyd</th>
                    </tr>
                    <tr>
                        <th>Old</th>
                        <th>New</th>
                        <th>Old</th>
                        <th>New</th>
                        <th>Old</th>
                        <th>New</th>
                        <th>Old</th>
                        <th>New</th>
                        <th>Old</th>
                        <th>New</th>
                        <th>Old</th>
                        <th>New</th>
                        <th>Old</th>
                        <th>New</th>
                        <th>Old</th>
                        <th>New</th>
                        <th>Old</th>
                        <th>New</th>
                        <th>Old</th>
                        <th>New</th>
                        <th>Old</th>
                        <th>New</th>
                        <th>Old</th>
                        <th>New</th>
                        <th>Olg</th>
                        <th>New</th>
                        <th>Old</th>
                        <th>New</th>
                        <th>Old</th>
                        <th>New</th>
                    </tr>
                </thead>
                <tbody>
                  <?php while ($row = mysqli_fetch_assoc($h)) { ?>
                  <tr>
                    <td><?php echo $row['afml_detail_id'] ?></td>
                    <td><?php echo $row['created_date'] ?></td>
                    <td><?php echo $row['full_name'] ?></td>
                    <td><?php echo $row['afml_route_from_old'] ?></td>
                    <td><?php echo $row['afml_route_from_new'] ?></td>
                    <td><?php echo $row['afml_route_to_old'] ?></td>
                    <td><?php echo $row['afml_route_to_new'] ?></td>
                    <td><?php echo minutesToHours($row['afml_block_on_old']) ?></td>
                    <td><?php echo minutesToHours($row['afml_block_on_new']) ?></td>
                    <td><?php echo minutesToHours($row['afml_block_off_old']) ?></td>
                    <td><?php echo minutesToHours($row['afml_block_off_new']) ?></td>
                    <td><?php echo minutesToHours($row['afml_block_hrs_old']) ?></td>
                    <td><?php echo minutesToHours($row['afml_block_hrs_new']) ?></td>
                    <td><?php echo minutesToHours($row['afml_to_old']) ?></td>
                    <td><?php echo minutesToHours($row['afml_to_new']) ?></td>
                    <td><?php echo minutesToHours($row['afml_ldg_old']) ?></td>
                    <td><?php echo minutesToHours($row['afml_ldg_new']) ?></td>
                    <td><?php echo minutesToHours($row['afml_flt_hrs_old']) ?></td>
                    <td><?php echo minutesToHours($row['afml_flt_hrs_new']) ?></td>
                    <td><?php echo $row['afml_ldg_cycle_old'] ?></td>
                    <td><?php echo $row['afml_ldg_cycle_new'] ?></td>
                    <td><?php echo $row['afml_fuel_rem_old'] ?></td>
                    <td><?php echo $row['afml_fuel_rem_new'] ?></td>
                    <td><?php echo $row['afml_fuel_uplift_old'] ?></td>
                    <td><?php echo $row['afml_fuel_uplift_new'] ?></td>
                    <td><?php echo $row['afml_fuel_total_old'] ?></td>
                    <td><?php echo $row['afml_fuel_total_new'] ?></td>
                    <td><?php echo $row['afml_receipt_no_old'] ?></td>
                    <td><?php echo $row['afml_receipt_no_new'] ?></td>
                    <td><?php echo $row['afml_added_oil_old'] ?></td>
                    <td><?php echo $row['afml_added_oil_new'] ?></td>
                    <td><?php echo $row['afml_added_hyd_old'] ?></td>
                    <td><?php echo $row['afml_added_hyd_new'] ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
