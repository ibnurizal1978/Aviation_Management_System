<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
require_once "components.php";

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

//echo '2581:43 '.hoursToMinutes('2581:43');
//echo '<br/>936:03 '.hoursToMinutes('936:03');
//echo '<br/>';
//echo minutesToHours('84708');
$sql  = "SELECT afml_id,afml_page_no,aircraft_reg_code,aircraft_serial_number,afml_type,date_format(afml_date, '%d/%m/%Y') as afml_date,afml_date as afml_date2,date_format(afml_time_preflight, '%H:%i') as afml_time_preflight,date_format(afml_time_daily, '%H:%i') as afml_time_daily,afml_station_preflight,afml_station_daily,afml_lic_preflight,afml_lic_daily, afml_captain,afml_copilot,afml_engineer_on_board,user_signature,
brought_fwd_ac_hrs,brought_fwd_ac_ldg,brought_fwd_eng_1_hrs,brought_fwd_eng_1_ldg,brought_fwd_prop_hrs,
this_page_ac_hrs,this_page_ac_ldg,this_page_eng_1_hrs,this_page_eng_1_ldg,this_page_prop_hrs,
total_ac_hrs,total_ac_ldg,total_eng_1_hrs,total_eng_1_ldg,total_prop_hrs, afml_stamp_preflight,afml_stamp_daily,afml_inspector_stamp, afml_engineer_sign, afml_engineer_preflight_user_id, ectm_time,ectm_altitude,ectm_ias,ectm_tq,ectm_itt,ectm_ng,ectm_np,ectm_ff,ectm_oil_temp,ectm_oil_press,ectm_oat
FROM tbl_afml a INNER JOIN tbl_user b USING (user_id) WHERE afml_id = '".$ntf[1]."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);

/* hitung prev HRS dan LDG dari total HRS & LDG - current AFML */
$sql_master  = "SELECT aircraft_ac_total_hrs,aircraft_ac_total_ldg,aircraft_eng_1_total_hrs,aircraft_eng_1_total_ldg,aircraft_eng_2_total_hrs,aircraft_eng_2_total_ldg,aircraft_prop_total_hrs,aircraft_prop_total_ldg FROM tbl_aircraft_master WHERE aircraft_reg_code = '".$row['aircraft_reg_code']."' LIMIT 1";
$h_master    = mysqli_query($conn,$sql_master);
$row_master  = mysqli_fetch_assoc($h_master);

/*--- dapatkan total BLOCK HRS, FLT HRS dan LDG untuk AFML number ini ---*/
$sqla = "SELECT SUM(afml_block_hrs) AS total_block_hrs, SUM(afml_flt_hrs) AS total_flt_hrs, sum(afml_ldg_cycle) as total_ldg FROM tbl_afml_detail_new WHERE afml_page_no = '".$row['afml_page_no']."'";
$ha   = mysqli_query($conn,$sqla);
$rowa = mysqli_fetch_assoc($ha);

$aircraft_ac_total_hrs    = minutesToHours($row_master['aircraft_ac_total_hrs']);
$aircraft_ac_total_ldg    = $row_master['aircraft_ac_total_ldg'];
$aircraft_eng_1_total_hrs = minutesToHours($row_master['aircraft_eng_1_total_hrs']);
$aircraft_eng_1_total_ldg = $row_master['aircraft_eng_1_total_ldg'];
$aircraft_prop_total_hrs  = minutesToHours($row_master['aircraft_prop_total_hrs']);

$total_flt_hrs            = hoursToMinutes($rowa['total_flt_hrs']);

$previous_ac_total_hrs    = $row_master['aircraft_ac_total_hrs']-$total_flt_hrs;
$previous_ac_total_hrs    = minutesToHours($previous_ac_total_hrs);
$current_ac_total_ldg     = $rowa['total_ldg'];
$previous_ac_total_ldg    = $aircraft_ac_total_ldg-$current_ac_total_ldg;


$previous_eng_1_total_hrs = $row_master['aircraft_eng_1_total_hrs']-$total_flt_hrs;
$previous_eng_1_total_hrs = minutesToHours($previous_eng_1_total_hrs);
$current_eng_1_total_ldg  = $rowa['total_ldg'];
$previous_eng_1_total_ldg = $aircraft_eng_1_total_ldg-$current_ac_total_ldg;


$previous_prop_total_hrs  = $row_master['aircraft_prop_total_hrs']-$total_flt_hrs;
$previous_prop_total_hrs  = minutesToHours($previous_prop_total_hrs);


//get pilot comment
$sql_pilot_notes = "SELECT afml_notes_pilot FROM tbl_afml_notes WHERE afml_page_no = '".$row['afml_page_no']."' AND pilot_user_id <>''";
$h_pilot_notes   = mysqli_query($conn,$sql_pilot_notes);
$row_pilot_notes = mysqli_fetch_assoc($h_pilot_notes);

//get engineer comment
$sql_engineer_notes = "SELECT afml_notes_engineer FROM tbl_afml_notes WHERE afml_page_no = '".$row['afml_page_no']."' AND engineer_user_id <>''";
$h_engineer_notes   = mysqli_query($conn,$sql_engineer_notes);
$row_engineer_notes = mysqli_fetch_assoc($h_engineer_notes);

?>

<style type="text/css">
  body {
    font-size: 11pt;
  }
td {
  font-size:11px;
}  
div {
  font-size:11px;
}
</style>

<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title text-center">AIRCRAFT FLIGHT MAINTENANCE LOG</h3>
      </div>

      <div class="block-content">
        <div class="card-body">
          <h5 class="text-danger" style="text-align: center;">DETAIL NEW 2. SEBELUM SUBMIT DATA, PASTIKAN DATA SUDAH BENAR !!</h5> 

          <?php if(@$_GET['act']=='79dvi59g') { ?>
            <div class="alert alert-danger alert-dismissable" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p class="mb-0">Choose fuel location!</p>
            </div>
          <?php } ?>          
          <?php if(@$_GET['act']=='79dvi59') { ?>
            <div class="alert alert-danger alert-dismissable" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p class="mb-0">Duplicate Receipt Number!</p>
            </div>
          <?php } ?>
          <?php if(@$_GET['act']=='09dvi59') { ?>
            <div class="alert alert-danger alert-dismissable" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p class="mb-0">Yoo, maximum image size is 1MB and only file format PDF, GIF, JPG and PNG are allowed</p>
            </div>
          <?php } ?>
          <?php if(@$_GET['act']=='70dvi59') { ?>
            <div class="alert alert-danger alert-dismissable" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p class="mb-0">Type date (in format dd/mm/yyyy) of when did you fill the gasoline!</p>
            </div>
          <?php } ?>
          <?php if(@$_GET['act']=='99dvi59') { ?>
            <div class="alert alert-danger alert-dismissable" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p class="mb-0">Fill your corrective action bro...</p>
            </div>
          <?php } ?>
          <?php if(@$_GET['act']=='7k0dvi59') { ?>
            <div class="alert alert-danger alert-dismissable" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p class="mb-0">Please fill Receipt No & upload Fuel Bill</p>
            </div>
          <?php } ?>          
          <?php if(@$_GET['act']=='a29dvi59') { ?>
            <div class="alert alert-success alert-dismissable" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p class="mb-0">Data successfully updated</p>
            </div>
          <?php } ?>

          <div class="card-body table-responsive">
            <table class="table table-sm table-vcenter table-bordered">
              <tbody>
                <tr>
                  <td><b>DATE:</b> <?php echo $row['afml_date'] ?></td>
                  <td><b>A/C REG:</b> <?php echo $row['aircraft_reg_code'] ?><input type="hidden" name="aircraft_reg_code" value="<?php echo $row['aircraft_reg_code'] ?>" /></td>
                  <td><b>MSN:</b> <?php echo $row['aircraft_serial_number'] ?></td>
                  <td><b>TYPE:</b> <?php echo $row['afml_type'] ?></td>
                  <td><b>PAGE:</b> <?php echo $row['afml_page_no'] ?></td>
                </tr>
              </tbody>
            </table>

            <div class="row">
              <div class="col-md-3">

                <!--ini preflight untuk engineer-->
                <form id="form_simpan_preflight_engineer">
                  <table class="table table-sm table-vcenter table-bordered">
                    <tbody>                
                      <tr align="center">
                        <td colspan="3" width="25%"><b>CREWS</b></td>
                      </tr>
                      <tr>
                        <td colspan="3"><b>CAPT:</b> <?php echo $row['afml_captain'] ?></td>
                      </tr>
                      <tr>
                        <td colspan="3"><b>COPIL:</b> <?php echo $row['afml_copilot'] ?></td>
                      </tr>
                      <tr>
                        <td colspan="3"><b>ENG:</b> <?php echo $row['afml_engineer_on_board'] ?></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><b>PREFLIGHT</b></td>
                        <td><b>DAILY</b></td>
                      </tr>
                      <tr>
                        <td><b>TIME</b></td>
                        <td>
                          <?php if($_SESSION['department_id']==1 && $row['afml_engineer_preflight_user_id']==0) { ?>
                          <input type="text" class="form-control" name="afml_time_preflight" autocomplete="off" id="afml_time_preflight"  />
                          <?php }else{ echo $row['afml_time_preflight']; } ?>
                        </td>
                        <td>
                          <?php if($_SESSION['department_id']==1 && $row['afml_engineer_preflight_user_id']==0) { ?>
                          <input type="text" class="form-control" name="afml_time_daily" id="afml_time_daily" autocomplete="off" />
                          <?php }else{ echo $row['afml_time_daily']; } ?>
                        </td>
                      </tr>
                      <tr>
                        <td><b>STATION</b></td>
                        <td>
                          <?php if($_SESSION['department_id']==1 && $row['afml_engineer_preflight_user_id']==0) { ?>
                          <select class="form-control" required name="afml_station_preflight" style="padding-left: 5px; padding-right: 5px">
                            <option value="0"> -- None -- </option>
                            <?php
                            $sql1  = "SELECT iata_code FROM tbl_master_iata WHERE iata_code NOT IN ('','-') ORDER BY iata_code";
                            $h1    = mysqli_query($conn,$sql1);
                            while($row1 = mysqli_fetch_assoc($h1)) {
                            ?>
                            <option value="<?php echo $row1['iata_code'] ?>"><?php echo $row1['iata_code'] ?></option>
                            <?php } ?>
                          </select>                           
                          <?php }else{ echo $row['afml_station_preflight']; } ?>
                        </td>
                        <td>
                          <?php if($_SESSION['department_id']==1 && $row['afml_engineer_preflight_user_id']==0) { ?>
                          <select class="form-control" required name="afml_station_daily" style="padding-left: 5px; padding-right: 5px">
                            <option value="0"> -- None -- </option>
                            <?php
                            $sql1  = "SELECT iata_code FROM tbl_master_iata WHERE iata_code NOT IN ('','-') ORDER BY iata_code";
                            $h1    = mysqli_query($conn,$sql1);
                            while($row1 = mysqli_fetch_assoc($h1)) {
                            ?>
                            <option value="<?php echo $row1['iata_code'] ?>"><?php echo $row1['iata_code'] ?></option>
                            <?php } ?>
                          </select>                           
                          <?php }else{ echo $row['afml_station_daily']; } ?>
                        </td>
                      </tr>
                      <tr>
                        <td><b>LIC NO.</b></td>
                        <td>
                          <?php if($_SESSION['department_id']==1 && $row['afml_engineer_preflight_user_id']==0) { ?>
                            <input type="text" class="form-control" name="afml_lic_preflight" autocomplete="off" />
                          <?php }else{ echo $row['afml_lic_preflight']; } ?>
                        </td>
                        <td>
                          <?php if($_SESSION['department_id']==1 && $row['afml_engineer_preflight_user_id']==0) { ?>
                          <input type="text" class="form-control" name="afml_lic_daily" autocomplete="off" />
                          <?php }else{ echo $row['afml_lic_daily']; } ?>
                        </td>
                      </tr>
                      <tr>
                        <td><b>Sign & Stamp</b></td>
                        <td>
                          <?php if($_SESSION['department_id']==1 && $row['afml_engineer_preflight_user_id']==0) { ?>
                          <?php }else{ ?>
                          <img class="img-avatar" src="<?php echo $base_url ?>uploads/user-signature/<?php echo $row['afml_stamp_preflight'] ?>" alt="">
                          <?php } ?>                            
                        </td>
                        <td>
                          <?php if($_SESSION['department_id']==1 && $row['afml_engineer_preflight_user_id']==0) { ?>
                          <?php }else{ ?>
                          <img class="img-avatar" src="<?php echo $base_url ?>uploads/user-signature/<?php echo $row['afml_stamp_daily'] ?>" alt="">
                          <?php } ?>                           
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3" rowspan="3" align="center">I hereby certify that the aircraft has been maintained law applicable manuals & CASR, and determined to be in airworthy condition and safe for flight</td>
                      </tr>              
                    </tbody>
                  </table>
                  <?php if($_SESSION['department_id']==1 && $row['afml_engineer_preflight_user_id']==0) { ?>
                    <input type="hidden" name="afml_id" value="<?php echo $row['afml_id'] ?>">
                  <div id="results_engineer"></div><div id="button_engineer"></div>
                  <div class="clearfix"></div> 
                  <?php } ?>                 
                </form>
                <!--end preflight untuk engineer-->

              </div>
              <div class="col-md-9">
                <table class="table table-sm table-vcenter table-bordered">
                  <tr align="center">
                    <td colspan="2"><b>ROUTE</b></td>
                    <td colspan="4"><b>BLOCK</b></td>
                    <td rowspan="2"><b>T/O</b></td>
                    <td rowspan="2"><b>LDG</b></td>
                    <td colspan="2"><b>FLT</b></td>
                    <td rowspan="2"><b>LDG/CYCLE</b></td>
                    <td colspan="3"><b>FUEL</b></td>
                    <td rowspan="2"><b>RECEIPT NO</b></td>
                    <td colspan="2"><b>ADDED</b></td>
                    <td rowspan="2"><b>Action</b></td>                    
                  </tr>                      
                  <tr align="center">
                    <td><b>FROM</b></td>
                    <td><b>TO</b></td>
                    <td><b>OFF</b></td>
                    <td><b>ON</b></td>
                    <td><b>HRS</b></td>
                    <td><b>MINS</b></td>
                    <td><b>HRS</b></td>
                    <td><b>MINS</b></td>
                    <td><b>REM</b></td>
                    <td><b>UPLIFT</b></td>
                    <td><b>TOTAL</b></td>
                    <td><b>OIL</b></td>
                    <td><b>HYD</b></td>                    
                  </tr>
                  <?php
                  $sql2 = "SELECT * FROM tbl_afml_detail_new WHERE afml_page_no = '".$row['afml_page_no']."' ORDER BY afml_detail_id ASC";
                  $h2   = mysqli_query($conn,$sql2);
                  while($row2 = mysqli_fetch_assoc($h2)) {
                    $afml_block_hrs = $row2['afml_block_on'] - $row2['afml_block_off'];
                    if($afml_block_hrs<0) { $afml_block_hrs = $afml_block_hrs+1440; }

                    $afml_flt_hrs = $row2['afml_ldg'] - $row2['afml_to'];
                    if($afml_flt_hrs<0) { $afml_flt_hrs = $afml_flt_hrs+1440; }
                  ?>  
                  <tr>
                    <td><?php echo $row2['afml_route_from'] ?></td>
                    <td><?php echo $row2['afml_route_to'] ?></td>  
                    <td><?php echo minutesToHours($row2['afml_block_off']) ?></td>
                    <td><?php echo minutesToHours($row2['afml_block_on']) ?></td>
                    <td><?php echo substr(minutesToHours($afml_block_hrs),0,2) ?></td>
                    <td><?php echo substr(minutesToHours($afml_block_hrs),3,2) ?></td>
                    <td><?php echo minutesToHours($row2['afml_to']) ?></td>
                    <td><?php echo minutesToHours($row2['afml_ldg']) ?></td>
                    <td><?php echo substr(minutesToHours($afml_flt_hrs),0,2) ?></td>
                    <td><?php echo substr(minutesToHours($afml_flt_hrs),3,2) ?></td>
                    <td><?php echo $row2['afml_ldg_cycle'] ?></td>
                    <td><?php echo $row2['afml_fuel_rem'] ?></td>
                    <td><?php echo $row2['afml_fuel_uplift'] ?></td>
                    <td><?php echo $row2['afml_fuel_total'] ?></td>
                    <td><?php echo $row2['afml_receipt_no'] ?></td>
                    <td><?php echo $row2['afml_added_oil'] ?></td>
                    <td><?php echo $row2['afml_added_hyd'] ?></td>
                    <td><?php if ($row2['engineer_user_id']==0) { ?><a href="#" data-toggle="modal" data-target="#modal-compose<?php echo $row2['afml_detail_id'] ?>">+ Fuel</a>
                    <?php } ?>
                    <!-- Compose Modal -->
                      <div class="modal fade" id="modal-compose<?php echo $row2['afml_detail_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modal-compose" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-top" role="document">
                          <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                              <div class="block-header">
                                <h3 class="block-title">
                                  <i class="fa fa-pencil mr-5"></i> Add Fuel Info
                                </h3>
                                <div class="block-options">
                                  <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                      <i class="si si-close"></i>
                                  </button>
                                </div>
                              </div>
                              <div class="block-content">
                                <form method="POST" action="afml-detail-add-fuel.php" enctype="multipart/form-data">
                                  <input type="hidden" name="afml_id" value="<?php echo $row['afml_id'] ?>">
                                  <input type="hidden" name="afml_detail_id" value="<?php echo $row2['afml_detail_id'] ?>">
                                  <input type="hidden" name="afml_page_no" value="<?php echo $row2['afml_page_no'] ?>">
                                  <!--<div class="form-group row">
                                    <div class="col-4">
                                      <div class="form-material form-material-primary input-group">
                                        <input type="text" class="form-control"  name="afml_fuel_rem" />
                                        <label for="message-subject">REM</label>
                                      </div>
                                    </div>
                                    <div class="col-4">
                                      <div class="form-material form-material-primary input-group">
                                        <input type="text" class="form-control" name="afml_fuel_uplift" />
                                        <label for="message-subject">UPLIFT</label>
                                      </div>
                                    </div>
                                    <div class="col-4">
                                      <div class="form-material form-material-primary input-group">
                                        <input type="text" class="form-control"  name="afml_fuel_total" />
                                        <label for="message-subject">TOTAL</label>
                                      </div>
                                    </div>                                  
                                  </div>
                                  <br/>-->
                                  <div class="form-group row">
                                    <div class="col-6">
                                      <div class="form-material form-material-primary input-group">
                                        <input type="text" class="form-control" name="afml_added_oil" />
                                        <label for="message-subject">ADDED OIL</label>
                                      </div>
                                    </div>
                                    <div class="col-6">
                                      <div class="form-material form-material-primary input-group">
                                        <input type="text" class="form-control" name="afml_added_hyd" />
                                        <label for="message-subject">ADDED HYD</label>
                                      </div>
                                    </div>                                  
                                  </div>
                                  <br/>
                                  <div class="form-group row">
                                    <div class="col-6">
                                      <div class="form-material form-material-primary input-group">
                                        <input type="text" class="form-control"  name="afml_receipt_no" />
                                        <label class="col-12">RECEIPT NO</label>
                                      </div>
                                    </div>
                                    <div class="col-6">
                                      <div class="form-material form-material-primary input-group"><br/>
                                        <input type="text" class="form-control"  name="afml_fuel_date" id="afml_fuel_date" />
                                        <label class="col-12">Date(dd/mm/yyyy)</label>
                                      </div>
                                    </div>          
                                  </div>                            
                                  <br/>
                                  <div class="form-group row">
                                    <div class="col-4">
                                      <label class="col-12">Upload Bill (Format JPG, BMP, GIF, PNG)</label>
                                      <div class="col-12">
                                        <input type="file" name="upload_file" />
                                      </div>
                                    </div>
                                    <div class="col-4">
                                      <div class="form-material form-material-primary input-group">
                                        <select required name="afml_source" style="padding-left: 5px; padding-right: 5px">
                                          <option value=""> -- Choose -- </option>
                                          <option value="DRUM">DRUM</option>
                                          <option value="PERTAMINA">PERTAMINA</option>
                                        </select>
                                        <label class="col-12">Source From</label>
                                      </div>
                                    </div>                                     
                                    <div class="col-4">
                                      <div class="form-material form-material-primary input-group">
                                        <select required name="afml_fuel_location" style="padding-left: 5px; padding-right: 5px">
                                          <option value=""> -- Choose -- </option>
                                          <option value="<?php echo $row2['afml_route_from'] ?>"><?php echo $row2['afml_route_from'] ?></option>
                                          <option value="<?php echo $row2['afml_route_to'] ?>"><?php echo $row2['afml_route_to'] ?></option>
                                        </select>
                                        <label for="message-subject">Fuel Location</label>
                                      </div>
                                    </div>                              
                                  </div>
                                  <br/><br/>
                                  <input type="submit" class="btn btn-success mr-5 mb-5" value="Save" /> <a class="btn btn-info mr-5 mb-5 text-white" data-dismiss="modal"><i class="si si-close"></i> Close</a>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!--end modal-->
                    </td>                                        
                  </tr>
                  <?php } ?>
                  <tr style="font-weight: bold">
                    <td colspan="4" align="right"><b>TTL BLOCK TIME</b></td>
                    <td><?php echo substr(minutesToHours($rowa['total_block_hrs']),0,2) ?></td>
                    <td><?php echo substr(minutesToHours($rowa['total_block_hrs']),3,2) ?></td>
                    <td colspan="2" align="right">TTL FLT TIME & LDG</td>
                    <td><?php echo substr(minutesToHours($rowa['total_flt_hrs']),0,2) ?></td>
                    <td><?php echo substr(minutesToHours($rowa['total_flt_hrs']),3,2) ?></td>
                    <td><?php echo $rowa['total_ldg'] ?></td>
                    <td colspan="3"></td>
                  </tr>
                  <!--form add block on off -->
                  <?php if($_SESSION['department_id']==4) { ?>
                  <form id="form_simpan">
                  <input type="hidden" name="afml_page_no" value="<?php echo $row['afml_page_no'] ?>">
                  <input type="hidden" name="afml_date" value="<?php echo $row['afml_date2'] ?>">
                  <input type="hidden" name="aircraft_reg_code" value="<?php echo $row['aircraft_reg_code'] ?>">

                  <input type="hidden" name="brought_fwd_ac_hrs" value="<?php echo $row['brought_fwd_ac_hrs'] ?>">
                  <input type="hidden" name="brought_fwd_ac_ldg" value="<?php echo $row['brought_fwd_ac_ldg'] ?>">
                  <input type="hidden" name="brought_fwd_eng_1_hrs" value="<?php echo $row['brought_fwd_eng_1_hrs'] ?>">
                  <input type="hidden" name="brought_fwd_eng_1_ldg" value="<?php echo $row['brought_fwd_eng_1_ldg'] ?>">
                  <input type="hidden" name="brought_fwd_prop_hrs" value="<?php echo $row['brought_fwd_prop_hrs'] ?>">

                  <input type="hidden" name="this_page_ac_hrs" value="<?php echo $row['this_page_ac_hrs'] ?>">
                  <input type="hidden" name="this_page_ac_ldg" value="<?php echo $row['this_page_ac_ldg'] ?>">
                  <input type="hidden" name="this_page_eng_1_hrs" value="<?php echo $row['this_page_eng_1_hrs'] ?>">
                  <input type="hidden" name="this_page_eng_1_ldg" value="<?php echo $row['this_page_eng_1_ldg'] ?>">
                  <input type="hidden" name="this_page_prop_hrs" value="<?php echo $row['this_page_prop_hrs'] ?>">                   

                  <input type="hidden" name="total_ac_hrs" value="<?php echo $row['total_ac_hrs'] ?>">
                  <input type="hidden" name="total_ac_ldg" value="<?php echo $row['total_ac_ldg'] ?>">
                  <input type="hidden" name="total_eng_1_hrs" value="<?php echo $row['total_eng_1_hrs'] ?>">
                  <input type="hidden" name="total_eng_1_ldg" value="<?php echo $row['total_eng_1_ldg'] ?>">
                  <input type="hidden" name="total_prop_hrs" value="<?php echo $row['total_prop_hrs'] ?>">                  
                  <tr>
                    <td align="center">
                      <select required name="afml_route_from">
                        <option value=""> -- Choose -- </option>
                        <?php
                        $sql1  = "SELECT iata_code,icao_code FROM tbl_master_iata WHERE iata_code NOT IN ('','-') AND icao_code NOT IN ('','-') ORDER BY iata_code";
                        $h1    = mysqli_query($conn,$sql1);
                        while($row1 = mysqli_fetch_assoc($h1)) {
                        ?>
                        <option value="<?php echo $row1['iata_code'] ?>"><?php echo $row1['iata_code'].' / '.$row1['icao_code'] ?></option>
                        <?php } ?>
                      </select>
                    </td>
                    <td align="center">
                      <select required name="afml_route_to">
                        <option value=""> -- Choose -- </option>
                        <?php
                        $sql2  = "SELECT iata_code,icao_code FROM tbl_master_iata WHERE iata_code NOT IN ('','-') AND icao_code NOT IN ('','-') ORDER BY iata_code";
                        $h2    = mysqli_query($conn,$sql2);
                        while($row2 = mysqli_fetch_assoc($h2)) {
                        ?>
                        <option value="<?php echo $row2['iata_code'] ?>"><?php echo $row2['iata_code'].' / '.$row2['icao_code'] ?></option>
                        <?php } ?>
                      </select>
                    </td>                        
                    <td><input type="text" name="afml_block_off" id="afml_block_off" autocomplete="off" size="4" /></td>
                    <td><input type="text" name="afml_block_on" id="afml_block_on" autocomplete="off" size="4" /></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><input type="text" name="afml_to" id="afml_to" autocomplete="off" size="4" /></td>
                    <td><input type="text" name="afml_ldg" id="afml_ldg" autocomplete="off" size="4" /></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                      <select required name="afml_ldg_cycle">
                        <?php for($i=1;$i<31;$i++) { ?>
                          <option value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php } ?>
                      </select>
                    </td>
                    <td><input type="text" size="2"  name="afml_fuel_rem" /></td>
                    <td><input type="text" size="2"  name="afml_fuel_uplift" /></td>
                    <td><input type="text" size="2"  name="afml_fuel_total" /></td>                    
                  </tr>
                  <tr>
                    <td colspan="14">
                      <div id="results"></div><div id="button"></div>
                      <div class="clearfix"></div>
                    </td>
                  </tr>
                  </form>  
                  <?php } ?>                    
                </table>                                    
              </div>
            </div>
            

            <div class="row">
              <div class="col-md-12">
                <table border=0 width="100%">
                  <tr>
                    <td valign="top" width="80%">

                      <table class="table table-sm table-vcenter table-bordered">
                        <tr>
                          <td colspan="10">Aircraft Time</td>
                          <td colspan="2">APU</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td><b>A/F Total Hrs</b></td>
                          <td><b>Landing</b></td>
                          <td><b>Eng. #1 Hrs</b></td>
                          <td><b>Eng. #1 Cyc</b></td>
                          <td><b>Prop. #1 Hrs</b></td>
                          <td><b>Eng. #2 Hrs</b></td>
                          <td><b>Eng. #2 Cyc</b></td>
                          <td><b>Prop. #2 Hrs</b></td>
                          <td><b>Prop. #2 Hrs</b></td>
                          <td><b>Hours</b></td>
                          <td><b>Cycles</b></td>
                        </tr>
                        <tr>
                          <td><b>Brought Fwd</b></td>
                          <td><?php echo minutesToHours($row['brought_fwd_ac_hrs']) ?></td>
                          <td><?php echo $row['brought_fwd_ac_ldg'] ?></td>
                          <td><?php echo minutesToHours($row['brought_fwd_eng_1_hrs']) ?></td>
                          <td><?php echo $row['brought_fwd_eng_1_ldg'] ?></td>
                          <td><?php echo minutesToHours($row['brought_fwd_prop_hrs']) ?></td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                        </tr>
                        <tr>
                          <td><b>This Page</b></td>
                          <td><?php echo minutesToHours($rowa['total_flt_hrs']) ?></td>
                          <td><?php echo $rowa['total_ldg'] ?></td>
                          <td><?php echo minutesToHours($rowa['total_flt_hrs']) ?></td>
                          <td><?php echo $rowa['total_ldg'] ?></td>
                          <td><?php echo minutesToHours($rowa['total_flt_hrs']) ?></td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                        </tr>
                        <tr>                      
                          <td><b>Total</b></td>
                          <td><?php echo minutesToHours($row['brought_fwd_ac_hrs']+$rowa['total_flt_hrs']) ?></td>
                          <td><?php echo $row['brought_fwd_ac_ldg']+$rowa['total_ldg'] ?></td>
                          <td><?php echo minutesToHours($row['brought_fwd_eng_1_hrs']+$rowa['total_flt_hrs']) ?></td>
                          <td><?php echo $row['brought_fwd_eng_1_ldg']+$rowa['total_ldg'] ?></td>
                          <td><?php echo minutesToHours($row['brought_fwd_prop_hrs']+$rowa['total_flt_hrs']) ?></td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                        </tr>
                        <tr>                      
                          <td><b>Metric</b></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                        </tr>                  
                      </table>

                      <table class="table table-sm table-vcenter table-bordered">
                        <tr>
                          <td><b>DISCREPANCIES (Write NIL or A/C OK if No Defect)</b></td>
                          <td><b>CORRECTIVE ACTION</b></td>
                          <td><b>Sign, Stamp & Date</b></td>
                        </tr>
                        <tr>
                          <td><?php echo $row_pilot_notes['afml_notes_pilot'] ?></td>
                          <td><?php echo $row_engineer_notes['afml_notes_engineer'] ?></td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td><?php echo '<img width=70 src=../uploads/user-signature/'.$row['user_signature'].'>' ?></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>

                      <table class="table table-sm table-vcenter table-bordered">
                        <tr>
                          <td>
                            <b>COMPONENT CHANGE</b> 
                            <?php //if($_SESSION['department_id']==1) { ?>(<a href="afml-component-change.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_reg_code"]?>-<?php echo $row["afml_page_no"]?>-<?php echo $row['afml_id'] ?>-94dfvj!sdf-349ffuaw">Add New</a>) <?php //} ?>
                          </td>
                          <td><b>POS</b></td>
                          <td><b>P/N On</b></td>
                          <td><b>P/N Off</b></td>
                          <td><b>S/N On</b></td>
                          <td><b>S/N Off</b></td>
                          <td><b>LIC NO. & SIGN</b></td>
                        </tr>
                        <?php
                        $sql_c      = "SELECT position, description, old_part_number, old_serial_number, new_part_number, new_serial_number FROM tbl_aircraft_parts_history WHERE afml_page_no = '".$row['afml_page_no']."' order by created_date DESC";
                        $h_c        = mysqli_query($conn,$sql_c);
                        while($row_c = mysqli_fetch_assoc($h_c)) { 
                        ?>
                        <tr>
                          <td><?php echo $row_c['description'] ?></td>
                          <td><?php echo $row_c['position'] ?></td>
                          <td><?php echo $row_c['new_part_number'] ?></td>
                          <td><?php echo $row_c['old_part_number'] ?></td>
                          <td><?php echo $row_c['new_serial_number'] ?></td>
                          <td><?php echo $row_c['old_serial_number'] ?></td>
                          <td>&nbsp;</td>
                        </tr>
                        <?php } ?>
                      </table>

                    </td>
                    <td valign="top">

                      <table class="table table-sm table-vcenter table-bordered">
                        <tr><td colspan="2">ECTM Manual Entry (if required)</td></tr>
                        <tr><td width="35%">Time</td><td><?php echo $row['ectm_time'] ?></td></tr>
                        <tr><td>Altitude</td><td><?php echo $row['ectm_altitude'] ?></td></tr>
                        <tr><td>EQ/EPR</td><td><?php echo $row['ectm_ias'] ?></td></tr>
                        <tr><td>IFT/EGT</td><td><?php echo $row['ectm_itt'] ?></td></tr>
                        <tr><td>NG/NG2</td><td><?php echo $row['ectm_ng'] ?></td></tr>
                        <tr><td>NP/N1</td><td><?php echo $row['ectm_np'] ?></td></tr>
                        <tr><td>F/F</td><td><?php echo $row['ectm_ff'] ?></td></tr>
                        <tr><td>Oil Temp</td><td><?php echo $row['ectm_oil_temp'] ?></td></tr>
                        <tr><td>Oil Press</td><td><?php echo $row['ectm_oil_press'] ?></td></tr>
                        <tr><td>OAT</td><td><?php echo $row['ectm_oat'] ?></td></tr>
                        <tr><td colspan="2" align="center">Last Compresor Wash C/O</td></tr>
                        <tr><td>Date</td></tr>
                        <tr><td colspan="2" align="center">PERIODIC INSPECTION</td></tr>
                        <tr><td>Last Insp C/O at</td><td> Hrs</td></tr>
                        <tr><td>Type of Insp</td><td> Hrs</td></tr>
                        <tr><td colspan="2" align="center">Next Inspection</td></tr>
                        <tr><td>Type Insp</td><td> Hrs</td></tr>
                        <tr><td>Due at</td><td> Hrs</td></tr>
                      </table>

                    </td>
                  </tr>
                </table>

              </div>                
            </div>
          </div>
        </form> 
      </div>
    </div>
  </div>

<?php require_once 'footer.php' ?>

<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="afml.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
  $('#submit_data').click(function(){  
    $('#form_simpan').submit(); 
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>'); 
  });  
  $('#form_simpan').on('submit', function(event){
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>');    
    event.preventDefault();  
    $.ajax({  
      url:"afml-detail-add-hour.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="afml.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });  
});  

/* preflight */
$(document).ready(function(){
  $("#button_engineer").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button>');  
  $('#submit_data').click(function(){  
    $('#form_simpan_preflight_engineer').submit(); 
    $("#results_engineer").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button_engineer").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>');
  });  
  $('#form_simpan_preflight_engineer').on('submit', function(event){
    $("#results_engineer").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button_engineer").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>');    
    event.preventDefault();  
    $.ajax({  
      url:"afml-engineer-preflight-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results_engineer').html(data);  
        $('#submit_data').val('');
        $("#button_engineer").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button>');  
      }  
    });  
  });  
}); 

$(document).ready(function() {

  $("#afml_block_on").attr("maxlength", 5);
  $("#afml_block_on").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });

  $("#afml_block_off").attr("maxlength", 5);
  $("#afml_block_off").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });

  $("#afml_block_hrs").attr("maxlength", 2);
  $("#afml_block_hrs").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });

  $("#afml_block_min").attr("maxlength", 2);
  $("#afml_block_min").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });    

  $("#afml_to").attr("maxlength", 5);
  $("#afml_to").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });

  $("#afml_ldg").attr("maxlength", 5);
  $("#afml_ldg").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });

  $("#afml_flt_hrs").attr("maxlength", 2);
  $("#afml_flt_hrs").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });

  $("#afml_flt_min").attr("maxlength", 2);
  $("#afml_flt_min").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });

  $("#afml_time_preflight").attr("maxlength", 5);
  $("#afml_time_preflight").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });

  $("#afml_time_daily").attr("maxlength", 5);
  $("#afml_time_daily").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });

  $("#afml_fuel_date").attr("maxlength", 10);
  $("#afml_fuel_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });  
      
}); 
</script>