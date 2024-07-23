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
echo '05:31 = '.hoursToMinutes('05:31');
*/
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
$sql_engineer_notes = "SELECT afml_notes_engineer FROM tbl_afml_notes WHERE afml_page_no = '".$row['afml_page_no']."' AND engineer_user_id <>''";
$h_engineer_notes   = mysqli_query($conn,$sql_engineer_notes);
$row_engineer_notes = mysqli_fetch_assoc($h_engineer_notes);

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
                  </tr>
                  <?php } ?>
                  <tr style="font-weight: bold">
                    <td colspan="4" align="right"><b>TTL BLOCK TIME</b></td>
                    <td><?php echo substr($rowa['total_block_hrs'],0,2) ?></td>
                    <td><?php echo substr($rowa['total_block_hrs'],3,2) ?></td>
                    <td colspan="2" align="right">TTL FLT TIME & LDG</td>
                    <td><?php echo substr($rowa['total_flt_hrs'],0,2) ?></td>
                    <td><?php echo substr($rowa['total_flt_hrs'],3,2) ?></td>
                    <td><?php echo $rowa['total_ldg'] ?></td>
                  </tr>
                  <tr>
                    <td align="center">
                      <select class="btn-primary" required name="afml_route_from">
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
                      <select class="btn-primary" required name="afml_route_to">
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
                      <select class="btn-primary" data-style="select-with-transition" required name="afml_ldg_cycle" style="padding-left: 5px; padding-right: 5px">
                        <?php for($i=1;$i<11;$i++) { ?>
                          <option value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php } ?>
                      </select>
                    </td>
                  </tr>
                </table> 
                <div id="results"></div><div id="button"></div>
                <div class="clearfix"></div>                                       
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
                    <td><b>Eng. #2 Hrs</b></td>
                    <td><b>Eng. #2 Cyc</b></td>
                    <td><b>Prop. #2 Hrs</b></td>
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
      url:"afml-pilot-add.php",  
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
</script>