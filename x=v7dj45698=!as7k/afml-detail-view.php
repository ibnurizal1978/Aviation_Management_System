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
/*
echo '1406:17 '.hoursToMinutes('1406:17');
echo '<br/>';
echo minutesToHours('84708');
echo '<br/>';
*/
//echo '338:03 = '.hoursToMinutes('338:05');
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
$sqla = "SELECT SUM(afml_block_hrs) AS total_block_hrs, SUM(afml_flt_hrs) AS total_flt_hrs, sum(afml_ldg_cycle) as total_ldg FROM tbl_afml_detail WHERE afml_page_no = '".$row['afml_page_no']."'";
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
                        <td><?php echo $row['afml_time_preflight'] ?></td>
                        <td><?php echo $row['afml_time_daily'] ?></td>
                      </tr>
                      <tr>
                        <td><b>STATION</b></td>
                        <td><?php echo $row['afml_station_preflight'] ?></td>
                        <td><?php echo $row['afml_station_daily'] ?></td>
                      </tr>
                      <tr>
                        <td><b>LIC NO.</b></td>
                        <td><?php echo $row['afml_lic_preflight'] ?></td>
                        <td><?php echo $row['afml_lic_daily'] ?></td>
                      </tr>
                      <tr>
                        <td><b>Sign & Stamp</b></td>
                        <td>
                          <img class="img-avatar" src="<?php echo $base_url ?>uploads/user-signature/<?php echo $row['afml_stamp_preflight'] ?>" alt="">
                        </td>
                        <td>
                          <img class="img-avatar" src="<?php echo $base_url ?>uploads/user-signature/<?php echo $row['afml_stamp_daily'] ?>" alt="">
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3" rowspan="3" align="center">I hereby certify that the aircraft has been maintained law applicable manuals & CASR, and determined to be in airworthy condition and safe for flight</td>
                      </tr>              
                    </tbody>
                  </table>                
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
                    <td rowspan="2" width="5%"><b>LDG/CYCLE</b></td>
                    <td colspan="3"><b>FUEL</b></td>
                    <td rowspan="2"><b>RECEIPT NO</b></td>
                    <td colspan="2"><b>ADDED</b></td>                   
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
                  $sql2 = "SELECT * FROM tbl_afml_detail WHERE afml_page_no = '".$row['afml_page_no']."' ORDER BY afml_detail_id ASC";
                  $h2   = mysqli_query($conn,$sql2);
                  while($row2 = mysqli_fetch_assoc($h2)) {
                  ?>  
                  <tr>
                    <td><?php echo $row2['afml_route_from'] ?></td>
                    <td><?php echo $row2['afml_route_to'] ?></td>  
                    <td><?php echo minutesToHours($row2['afml_block_off']) ?></td>
                    <td><?php echo minutesToHours($row2['afml_block_on']) ?></td>
                    <td><?php echo substr(minutesToHours($row2['afml_block_hrs']),0,2) ?></td>
                    <td><?php echo substr(minutesToHours($row2['afml_block_hrs']),3,2) ?></td>
                    <td><?php echo minutesToHours($row2['afml_to']) ?></td>
                    <td><?php echo minutesToHours($row2['afml_ldg']) ?></td>
                    <td><?php echo substr(minutesToHours($row2['afml_flt_hrs']),0,2) ?></td>
                    <td><?php echo substr(minutesToHours($row2['afml_flt_hrs']),3,2) ?></td>
                    <td><?php echo $row2['afml_ldg_cycle'] ?></td>
                    <td><?php echo $row2['afml_fuel_rem'] ?></td>
                    <td><?php echo $row2['afml_fuel_uplift'] ?></td>
                    <td><?php echo $row2['afml_fuel_total'] ?></td>
                    <td><?php echo $row2['afml_receipt_no'] ?></td>
                    <td><?php echo $row2['afml_added_oil'] ?></td>
                    <td><?php echo $row2['afml_added_hyd'] ?></td>
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
                          <td><?php echo minutesToHours($row['this_page_ac_hrs']) ?></td>
                          <td><?php echo $row['this_page_ac_ldg'] ?></td>
                          <td><?php echo minutesToHours($row['this_page_eng_1_hrs']) ?></td>
                          <td><?php echo $row['this_page_eng_1_ldg'] ?></td>
                          <td><?php echo minutesToHours($row['this_page_prop_hrs']) ?></td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
                          <td style="background: #aaaaaa">&nbsp;</td>
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
                          <td><b>COMPONENT CHANGE (Description)</b></td>
                          <td><b>POS</b></td>
                          <td><b>P/N On</b></td>
                          <td><b>P/N Off</b></td>
                          <td><b>S/N On</b></td>
                          <td><b>S/N Off</b></td>
                          <td><b>LIC NO. & SIGN</b></td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
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
<button type="button" class="btn btn-sm btn-circle btn-alt-success mr-5 mb-5" onClick="javascript:window.print()">print</button>
<?php require_once 'footer.php' ?>