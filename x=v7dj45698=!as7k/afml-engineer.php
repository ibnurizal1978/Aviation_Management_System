  <?php
session_start();
//ini_set('display_errors',1);  error_reporting(E_ALL);
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
$sql  = "SELECT afml_id,afml_page_no,aircraft_reg_code,aircraft_serial_number,afml_type,date_format(afml_date, '%d/%m/%Y') as afml_date,afml_date as afml_date2,date_format(afml_time_preflight, '%H:%i') as afml_time_preflight,date_format(afml_time_daily, '%H:%i') as afml_time_daily,afml_station_preflight,afml_station_daily,afml_lic_preflight,afml_lic_daily, afml_captain,afml_copilot,afml_engineer,user_signature,
brought_fwd_ac_hrs,brought_fwd_ac_ldg,brought_fwd_eng_1_hrs,brought_fwd_eng_1_ldg,brought_fwd_prop_hrs,
this_page_ac_hrs,this_page_ac_ldg,this_page_eng_1_hrs,this_page_eng_1_ldg,this_page_prop_hrs,
total_ac_hrs,total_ac_ldg,total_eng_1_hrs,total_eng_1_ldg,total_prop_hrs,afml_stamp_preflight,afml_stamp_daily,afml_inspector_stamp, afml_engineer_sign
FROM tbl_afml a INNER JOIN tbl_user b USING (user_id) WHERE afml_id = '".$ntf[1]."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);

/* hitung prev HRS dan LDG dari total HRS & LDG - current AFML */
$sql_master  = "SELECT aircraft_master_id,aircraft_ac_total_hrs,aircraft_ac_total_ldg,aircraft_eng_1_total_hrs,aircraft_eng_1_total_ldg,aircraft_eng_2_total_hrs,aircraft_eng_2_total_ldg,aircraft_prop_total_hrs,aircraft_prop_total_ldg FROM tbl_aircraft_master WHERE aircraft_reg_code = '".$row['aircraft_reg_code']."' LIMIT 1";
$h_master    = mysqli_query($conn,$sql_master);
$row_master  = mysqli_fetch_assoc($h_master);

/*--- dapatkan total BLOCK HRS, FLT HRS dan LDG untuk AFML number ini ---*/
$sqla = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(afml_block_hrs))) AS total_block_hrs, SEC_TO_TIME(SUM(TIME_TO_SEC(afml_flt_hrs))) AS total_flt_hrs, sum(afml_ldg_cycle) as total_ldg FROM tbl_afml_detail WHERE afml_page_no = '".$row['afml_page_no']."'";
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
$sql_engineer_notes = "SELECT afml_notes_engineer, component_change FROM tbl_afml_notes WHERE afml_page_no = '".$row['afml_page_no']."' AND engineer_user_id <>''";
$h_engineer_notes   = mysqli_query($conn,$sql_engineer_notes);
$row_engineer_notes = mysqli_fetch_assoc($h_engineer_notes);

//total_hrs buat tampil di bawah
$start = strtotime($ttl_hrs);
$end = strtotime($ttl_flt_time);
$jumlah1 = $start + $end;
$total_hrs = date("H:i", $jumlah1);
$total_ldg  = $ttl_ldg + $row_ttl_ldg['total'];

//Total HRS
function AddTimeToStr($aElapsedTimes) {
  $totalHours = 0;
  $totalMinutes = 0;

  foreach($aElapsedTimes as $time) {
    $timeParts = explode(":", $time);
    $h = $timeParts[0];
    $m = $timeParts[1];
    $totalHours += $h;
    $totalMinutes += $m;
  }

  $additionalHours = floor($totalMinutes / 60);
  $minutes = $totalMinutes % 60;
  $hours = $totalHours + $additionalHours;

  $strMinutes = strval($minutes);
  if ($minutes < 10) {
      $strMinutes = "0" . $minutes;
  }

  $strHours = strval($hours);
  if ($hours < 10) {
      $strHours = "0" . $hours;
  }

  return($strHours . ":" . $strMinutes);
}
$total_hrs  = AddTimeToStr(array($ttl_hrs, $ttl_flt_time));
//echo 'total hrs prv: '.$ttl_hrs;
//echo 'total hrs saat ini: '.$ttl_flt_time;
//echo '<br/>total hrs: '.$ttl_hrs + $ttl_flt_time;
?>

<style type="text/css">
td {
  font-size:14px;
}  
</style>

<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">AFML DETAIL</h3>
      </div>

      <div class="block-content">
        <div class="card-body">
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
          <?php if(@$_GET['act']=='a29dvi59') { ?>
            <div class="alert alert-success alert-dismissable" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p class="mb-0">Data successfully updated</p>
            </div>
          <?php } ?>                                       
          <input type="hidden" name="afml_page_no" value="<?php echo $row['afml_page_no'] ?>">

          <div class="card-body table-responsive">
            <table class="table table-sm table-vcenter table-bordered">
              <tbody>
                <tr>
                  <td><b>DATE:</b> <?php echo $row['afml_date'] ?></td>
                  <td><b>A/C REG:</b> <?php echo $row['aircraft_reg_code'] ?><input type="hidden" name="aircraft_reg_code" value="<?php echo $row['aircraft_reg_code'] ?>" /></td>
                  <td><b>MSN:</b> <?php echo $row['aircraft_serial_number'] ?></td>
                  <td><b>TYPE:</b> <?php echo $row['afml_type'] ?></td>
                  <td><b>PAGE:</b> <input type="text" name="afml_page_no" size="7" value="<?php echo $row['afml_page_no'] ?>" /></td>
                </tr>
              </tbody>
            </table>

            <div class="row">
              <div class="col-md-3">
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
                      <td colspan="3"><b>ENG:</b> <?php echo $row['afml_engineer'] ?></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><b>PREFLIGHT</b></td>
                      <td><b>DAILY</b></td>
                    </tr>
                    <tr>
                      <td><b>TIME</b></td>
                      <td>
                        <?php if($row['afml_time_preflight']=='00:00') { ?>
                        <input type="text" class="form-control" name="afml_time_preflight" autocomplete="off" id="afml_time_preflight"  />
                        <?php }else{ echo $row['afml_time_preflight']; } ?>
                      </td>
                      <td>
                        <?php if($row['afml_time_daily']=='00:00') { ?>
                        <input type="text" class="form-control" name="afml_time_daily" id="afml_time_daily" autocomplete="off" />
                        <?php }else{ echo $row['afml_time_daily']; } ?>
                      </td>
                    </tr>
                    <tr>
                      <td><b>STATION</b></td>
                      <td>
                        <?php if($row['afml_station_preflight']=='') { ?>
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
                        <?php if($row['afml_station_daily']=='') { ?>
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
                        <?php if($row['afml_lic_preflight']==0) { ?>
                          <input type="text" class="form-control" name="afml_lic_preflight" autocomplete="off" />
                        <?php }else{ echo $row['afml_lic_preflight']; } ?>
                      </td>
                      <td>
                        <?php if($row['afml_lic_daily']==0) { ?>
                        <input type="text" class="form-control" name="afml_lic_daily" autocomplete="off" />
                        <?php }else{ echo $row['afml_lic_daily']; } ?>
                      </td>
                    </tr>
                    <tr>
                      <td><b>Sign & Stamp</b></td>
                      <td>
                        <?php if($row['afml_stamp_preflight']=='') { ?>
                        {stamp}
                        <?php }else{ ?>
                        <img class="img-avatar" src="<?php echo $base_url ?>uploads/user-signature/<?php echo $row['afml_stamp_preflight'] ?>" alt="">
                        <?php } ?>                       
                      </td>
                      <td>
                        <?php if($row['afml_stamp_daily']=='') { ?>
                        {stamp}
                        <?php }else{ ?>
                        <img class="img-avatar" src="<?php echo $base_url ?>uploads/user-signature/<?php echo $row['afml_stamp_daily'] ?>" alt="">
                        <?php } ?>                         
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3" rowspan="3" align="center">I hereby certify that the aircraft has been maintained law applicable manuals & CASR, and determined to be in airworthy condition and safe for flight
                    <?php if($row['afml_lic_daily']==0) { ?>
                      <br/>
                      <input type="hidden" name="afml_id" value="<?php echo $row['afml_id'] ?>">
                      <div id="results_engineer"></div><div id="button_engineer"></div>
                      <div class="clearfix"></div>
                      </td>
                    </tr>
                    <?php } ?>             
                  </tbody>
                </table>
                </form>

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
                  $sql2 = "SELECT *,date_format(afml_block_on, '%H:%i') as afml_block_on, date_format(afml_block_off, '%H:%i') as afml_block_off, date_format(afml_to, '%H:%i') as afml_to, date_format(afml_ldg, '%H:%i') as afml_ldg FROM tbl_afml_detail WHERE afml_page_no = '".$row['afml_page_no']."'";
                  $h2   = mysqli_query($conn,$sql2);
                  while($row2 = mysqli_fetch_assoc($h2)) {
                    $afml_block_hrs = substr($row2['afml_block_hrs'],0,2);
                    $afml_block_min = substr($row2['afml_block_hrs'],3,2);
                    $afml_flt_hrs = substr($row2['afml_flt_hrs'],0,2);
                    $afml_flt_min = substr($row2['afml_flt_hrs'],3,2);
                  ?>  
                  <tr>
                    <td><?php echo $row2['afml_route_from'] ?></td>
                    <td><?php echo $row2['afml_route_to'] ?></td>  
                    <td><?php echo $row2['afml_block_off'] ?></td>
                    <td><?php echo $row2['afml_block_on'] ?></td>
                    <td><?php echo $afml_block_hrs ?></td>
                    <td><?php echo $afml_block_min ?></td>
                    <td><?php echo $row2['afml_to'] ?></td>
                    <td><?php echo $row2['afml_ldg'] ?></td>
                    <td><?php echo $afml_flt_hrs ?></td>
                    <td><?php echo $afml_flt_min ?></td>
                    <td><?php echo $row2['afml_ldg_cycle'] ?></td>
                    <td><?php echo $row2['afml_fuel_rem'] ?></td>
                    <td><?php echo $row2['afml_fuel_uplift'] ?></td>
                    <td><?php echo $row2['afml_fuel_total'] ?></td>
                    <td><?php echo $row2['afml_receipt_no'] ?></td>
                    <td><?php echo $row2['afml_added_oil'] ?></td>
                    <td><?php echo $row2['afml_added_hyd'] ?></td>
                    <td><?php if ($row2['engineer_user_id']==0) { ?><a class="btn btn-sm btn-rounded btn-success float-right" data-toggle="modal" data-target="#modal-compose<?php echo $row2['afml_detail_id'] ?>">Add Fuel</a>
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
                                <form method="POST" action="afml-engineer-add.php" enctype="multipart/form-data">
                                  <input type="hidden" name="afml_id" value="<?php echo $row['afml_id'] ?>">
                                  <input type="hidden" name="afml_detail_id" value="<?php echo $row2['afml_detail_id'] ?>">
                                  <input type="hidden" name="afml_page_no" value="<?php echo $row2['afml_page_no'] ?>">
                                  <div class="form-group row">
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
                                  <br/>
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
                                        <label for="message-subject">RECEIPT NO</label>
                                      </div>
                                    </div>
                                    <div class="col-6">
                                      <div class="form-material form-material-primary input-group">
                                        <input type="text" class="form-control"  name="afml_fuel_date" id="afml_fuel_date" />
                                        <label for="message-subject">Fuel Date (dd/mm/yyyy)</label>
                                      </div>
                                    </div>                               
                                  </div>                            
                                  <br/>
                                  <div class="form-group row">
                                    <div class="col-6">
                                      <label class="col-12">Upload Biill (Format JPG, BMP, GIF, PNG)</label>
                                      <div class="col-12">
                                        <input type="file" name="upload_file" />
                                      </div>
                                    </div>
                                    <div class="col-6">
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

                    </td>
                  </tr>
                  <?php } ?>
                  <tr style="font-weight: bold">
                    <td colspan="4" align="right"><b>TTL BLOCK TIME</b></td>
                    <td><?php echo substr($row_ttl_block_time['total'],0,2) ?></td>
                    <td><?php echo substr($row_ttl_block_time['total'],3,2) ?></td>
                    <td colspan="2" align="right">TTL FLT TIME & LDG</td>
                    <td><?php echo $ttl_flt_hrs ?></td>
                    <td><?php echo $ttl_flt_min ?></td>
                    <td><?php echo $row_ttl_ldg['total'] ?></td>
                    <td colspan="7" style="background: #aaaaaa">&nbsp;</td>
                  </tr>
                </table>                                       
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-sm table-vcenter table-bordered">
                  <tr>
                    <td>&nbsp;</td>
                    <td><b>A/F Total Hrs</b></td>
                    <td><b>Landing</b></td>
                    <td><b>Eng. #1 Hrs</b></td>
                    <td><b>Eng. #1 Cyc</b></td>
                    <td><b>Prop. #1 Hrs</b></td>
                    <td><b>Eng. #1 Hrs</b></td>
                    <td><b>Eng. #1 Cyc</b></td>
                    <td><b>Prop. #1 Hrs</b></td>
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
                  </tr>
                  <tr>
                    <td><b>This Page</b></td>
                    <td><?php echo minutesToHours($row['this_page_ac_hrs']) ?></td>
                    <td><?php echo $row['this_page_ac_ldg'] ?></td>
                    <td><?php echo minutesToHours($row['this_page_eng_1_hrs']) ?></td>
                    <td><?php echo $row['this_page_eng_1_ldg'] ?></td>
                    <td><?php echo minutesToHours($row['this_page_prop_hrs']) ?></td>
                    <td style="background: #aaaaaa">&nbsp;</td>
                    <td style="background: #aaaaaa">&nbsp;</td>
                    <td style="background: #aaaaaa">&nbsp;</td>
                  </tr>
                  <tr>                      
                    <td><b>Total</b></td>
                    <td><?php echo minutesToHours($row['total_ac_hrs']) ?></td>
                    <td><?php echo $row['total_ac_ldg'] ?></td>
                    <td><?php echo minutesToHours($row['total_eng_1_hrs']) ?></td>
                    <td><?php echo $row['total_eng_1_ldg'] ?></td>
                    <td><?php echo minutesToHours($row['total_prop_hrs']) ?></td>
                    <td style="background: #aaaaaa">&nbsp;</td>
                    <td style="background: #aaaaaa">&nbsp;</td>
                    <td style="background: #aaaaaa">&nbsp;</td>
                  </tr>
                </table>
                <table class="table table-sm table-vcenter table-bordered">
                  <tr>
                    <td><b>DISCREPANCIES (Write NIL or A/C OK if No Defect)</b></td>
                    <td><b>CORRECTIVE ACTION</b></td>
                  </tr>
                  <tr>
                    <td><?php echo $row_pilot_notes['afml_notes_pilot'] ?></td>
                    <td>
                      <?php if($row_engineer_notes['afml_notes_engineer']=='') { ?>
                      <form id="form_simpan2">
                        <input type="hidden" name="afml_id" value="<?php echo $row['afml_id'] ?>">
                        <input type="hidden" name="afml_page_no" value="<?php echo $row['afml_page_no'] ?>">
                        <div class="form-group row">
                          <div class="col-6">
                            <div class="form-material form-material-primary input-group">
                              <input type="text" class="form-control"  name="afml_notes_engineer" />
                              <label for="message-subject">Your Corrective Action</label>
                            </div>
                          </div>
                          <div class="col-6">
                              <label for="message-subject">Compontent Change</label>
                              <br/>
                              <a class="btn btn-sm btn-rounded btn-success" href="afml-component-change.php?act=29dvi59&ntf=29dvi59-<?php echo $row_master['aircraft_master_id'] ?>-<?php echo $row['afml_page_no'] ?>-94dfvj!sdf-349ffuaw">click here to change component</a>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-12">
                            <div class="col-12"> 
                              <label class="col-12">Upload Proof (Format JPG, BMP, GIF, PNG)</label>
                              <div class="col-12">
                                <input type="file" name="upload_file" />
                              </div>
                            </div>                                         
                        </div>
                        <br/><br/>
                        <div id="results2"></div><div id="button2"></div>
                        <div class="clearfix"></div>
                      </form>
                    <?php }else{
                      echo $row_engineer_notes['afml_notes_engineer']; } ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3"><b>COMPONENT CHANGE (Description)</b></td>
                  </tr>
                  <tr>
                    <td colspan="3"><?php echo $row_engineer_notes['component_change'] ?></td>
                  </tr>
                </table>                  
              </div>                
            </div>
          </div>
      </div>
    </div>
  </div>

<?php require_once 'footer.php' ?>

<script>  
/*$(document).ready(function(){
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
      url:"afml-detail-uadd.php",  
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
});*/  

$(document).ready(function(){
  $("#button2").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data2"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" data-dismiss="modal"><i class="si si-close"></i>Close</a>');  
  $('#submit_data2').click(function(){  
    $('#form_simpan2').submit(); 
    $("#results2").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button2").html('<button type="submit" class="btn btn-success pull-right" id="submit_data2">Loading...</button>'); 
  });  
  $('#form_simpan2').on('submit', function(event){
    $("#results2").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button2").html('<button type="submit" class="btn btn-success pull-right" id="submit_data2">Loading...</button>');    
    event.preventDefault();  
    $.ajax({  
      url:"afml-engineer-comment-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results2').html(data);  
        $('#submit_data2').val('');
        $("#button2").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data2"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" data-dismiss="modal"><i class="si si-close"></i>Close</a>');  
      }  
    });  
  });  
});

//buat LIC, PREFLIGHT
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
      
});

$(document).ready(function() {
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