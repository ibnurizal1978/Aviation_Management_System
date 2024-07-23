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

$sql  = "SELECT afml_page_no,aircraft_reg_code,aircraft_serial_number,afml_type,date_format(afml_date, '%d/%m/%Y') as afml_date,afml_date as afml_date2,date_format(afml_time_preflight, '%H:%i') as afml_time_preflight,date_format(afml_time_daily, '%H:%i') as afml_time_daily,afml_station_preflight,afml_station_daily,afml_lic_preflight,afml_lic_daily, afml_captain,afml_copilot,afml_engineer,user_signature,
brought_fwd_ac_hrs,brought_fwd_ac_ldg,brought_fwd_eng_1_hrs,brought_fwd_eng_1_ldg,brought_fwd_prop_hrs,
this_page_ac_hrs,this_page_ac_ldg,this_page_eng_1_hrs,this_page_eng_1_ldg,this_page_prop_hrs,
total_ac_hrs,total_ac_ldg,total_eng_1_hrs,total_eng_1_ldg,total_prop_hrs
FROM tbl_afml a INNER JOIN tbl_user b USING (user_id) WHERE afml_id = '".$ntf[1]."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);

/* hitung prev HRS dan LDG dari total HRS & LDG - current AFML */
$sql_master  = "SELECT aircraft_ac_total_hrs,aircraft_ac_total_ldg,aircraft_eng_1_total_hrs,aircraft_eng_1_total_ldg,aircraft_eng_2_total_hrs,aircraft_eng_2_total_ldg,aircraft_prop_total_hrs,aircraft_prop_total_ldg FROM tbl_aircraft_master WHERE aircraft_reg_code = '".$row['aircraft_reg_code']."' LIMIT 1";
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
$sql_engineer_notes = "SELECT afml_notes_engineer,component_change FROM tbl_afml_notes WHERE afml_page_no = '".$row['afml_page_no']."' AND engineer_user_id <>''";
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
  font-size:10px;
}  
</style>

<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">AFML DETAIL 
          <button type="button" class="btn btn-sm btn-circle btn-alt-success mr-5 mb-5" onClick="javascript:window.print()"><i class="si si-printer"></i></button>
        </h3>
      </div>

      <div class="block-content">
        <div class="card-body">
         <form id="form_simpan">
          <input type="hidden" name="afml_page_no" value="<?php echo $row['afml_page_no'] ?>">

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
                      <td>{sign}</td>
                      <td>{stamp}</td>
                    </tr>
                    <tr>
                      <td colspan="3" rowspan="3" align="center">I hereby certify that the aircraft has been maintained law applicable manuals & CASR, and determined to be in airworthy condition and safe for flight</td>
                    </tr>              
                  </tbody>
                </table>

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
                    <td rowspan="2"><b>RECEIPT<br/>NO</b></td>
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
                  $sql2 = "SELECT *,date_format(afml_block_on, '%H:%i') as afml_block_on, date_format(afml_block_off, '%H:%i') as afml_block_off, date_format(afml_to, '%H:%i') as afml_to, date_format(afml_ldg, '%H:%i') as afml_ldg FROM tbl_afml_detail WHERE afml_page_no = '".$row['afml_page_no']."'";
                  $h2   = mysqli_query($conn,$sql2);
                  while($row2 = mysqli_fetch_assoc($h2)) {
                    $afml_block_hrs = substr($row2['afml_block_hrs'],0,2);
                    $afml_block_min = substr($row2['afml_block_hrs'],3,2);
                    $afml_flt_hrs = substr($row2['afml_flt_hrs'],0,2);
                    $afml_flt_min = substr($row2['afml_flt_hrs'],3,2);
                  ?>  
                  <tr align="center">
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
                    <td colspan="9" align="center"><b>AIRCRAFT TIME</b></td>
                    <td colspan="2" align="center"><b>APU</b></td>
                    <td align="center"><b>ECTM Manual Entry (if required)</b></td>
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
                    <td><b>Hours</b></td>
                    <td><b>Cycles</b></td>
                    <td>Time: <?php echo $row['etcm_time'] ?>  UTC </td>
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
                  </tr>
                  <tr>
                    <td colspan="5"><b>DISCREPANCIES (Write NIL or A/C OK if No Defect)</b></td>
                    <td colspan="5"><b>CORRECTIVE ACTION</b></td>
                    <td><b>Sign, Stamp & Date</b></td>
                    <td>ITT/EGT: <?php echo $row['ectm_itt'] ?></td>
                  </tr>
                  <tr>
                    <td colspan="5" rowspan="8"><?php echo $row_pilot_notes['afml_notes_pilot'] ?><br/><br/><?php echo '<a href=../uploads/user-signature/'.$row['user_signature'].' target=_blank><i class="si si-paper-clip"></i></a>' ?></td>
                    <td colspan="5" rowspan="8"><?php echo $row_engineer_notes['afml_notes_engineer'] ?></td>
                    <td rowspan="8">&nbsp;</td>
                    <td>NG/NG2: <?php echo $row['ectm_ng'] ?></td>
                  </tr>
                  <tr>
                    <td>NP/NP1: <?php echo $row['ectm_np'] ?></td>
                  </tr>
                  <tr>
                    <td>F/F: <?php echo $row['ectm_ff'] ?></td>
                  </tr>
                  <tr>
                    <td>Oil Temp: <?php echo $row['ectm_oil_temp'] ?></td>
                  </tr>
                  <tr>
                    <td>Oil Press: <?php echo $row['ectm_oil_press'] ?></td>
                  </tr>
                  <tr>
                    <td>OAT: <?php echo $row['ectm_oat'] ?></td>
                  </tr>
                  <tr>
                    <td align="center">Last Compressor Wash C/O</td>
                  </tr>
                  <tr>
                    <td>Date: <?php echo $row['afml_date'] ?></td>
                  </tr>                                                   
                </table>
                <!--captain signature-->
                <table class="table table-sm table-vcenter table-bordered">
                  <tr>
                    <td colspan="6"><b>Captain Signature</td>
                    <td><b>PERIODIC INSPECTION</b></td>
                  </tr>
                  <tr>
                    <td colspan="6"><?php echo '<img width=70 src=../uploads/user-signature/'.$row['user_signature'].'>' ?></td>
                    <td>Last Insp C/O at: <br/>Type of Insp:</td>
                  </tr>
                  <tr>
                    <td><b>COMPONENT CHANGE (Description)</b></td>
                    <td><b>P/N On: </b></td>
                    <td><b>P/N Off: </b></td>
                    <td><b>S/N ON: </b></td>
                    <td><b>S/N Off: </b></td>
                    <td><b>LIC NO. & Sign</b></td>
                    <td><b>Next Inspection</b></td>
                  </tr>
                  <tr>
                    <td><?php echo $row_engineer_notes['component_change'] ?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Type Insp:<br/>Due at:</td>
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