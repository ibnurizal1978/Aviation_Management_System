<?php
require_once 'header.php';

$sql  = "SELECT *,date_format(flight_plan_date, '%d/%m/%Y') as flight_plan_date FROM tbl_flight_plan WHERE flight_plan_id = '".$ntf[1]."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
?>

<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
    <div class="content">
      <!--table-->   
      <div class="block table-responsive">
        <div class="block-header block-header-default">
          <h3 class="block-title">Create New Flight Plan</h3>
        </div>
        <div class="block-content">       
          <div class="card-body table-responsive">
            <table class="table table-sm table-vcenter table-bordered" style="border:1px solid #000000">
              <tr>
                <td>&nbsp;</td>
                <td><b>OPERATIONAL FLIGHT PLAN</b></td>
                <td style="background: #E6E6E6"><b>Fuel Requirements</b></td>
                <td style="background: #E6E6E6"><b>FLT 1</b></td>
                <td style="background: #E6E6E6"><b>FLT 2</b></td>
                <td style="background: #E6E6E6"><b>FLT 3</b></td>
                <td style="background: #E6E6E6"><b>FLT 4</b></td>
                <td style="background: #E6E6E6" rowspan="2"><b>Flight 1<br/>ETD</b></td>
                <td rowspan="2"><?php echo $row['etd_flt_1'] ?></td>
              </tr>
              <tr>
                <td style="background: #E6E6E6"><b>Date</b></td>
                <td><?php echo $row['flight_plan_date'] ?></td>
                <td style="background: #E6E6E6">TAXI</td>
                <td><?php echo $row['taxi_flt_1'] ?></td>
                <td><?php echo $row['taxi_flt_2'] ?></td>
                <td><?php echo $row['taxi_flt_3'] ?></td>
                <td><?php echo $row['taxi_flt_4'] ?></td>
              </tr>
              <tr>
                <td style="background: #E6E6E6"><b>Captain</b></td>
                <td><?php echo $row['flight_plan_captain'] ?></td>
                <td style="background: #E6E6E6">TRIP</td>
                <td style="background: #E6E6E6">&nbsp;</td>
                <td style="background: #E6E6E6">&nbsp;</td>
                <td style="background: #E6E6E6">&nbsp;</td>
                <td style="background: #E6E6E6">&nbsp;</td>
                <td style="background: #E6E6E6" rowspan="2"><b>Flight 2<br/>ETD</b></td>
                <td rowspan="2"><?php echo $row['etd_flt_2'] ?></td>
              </tr>
              <tr>
                <td style="background: #E6E6E6"><b>FO</b></td>
                <td><?php echo $row['flight_plan_copilot'] ?></td>
                <td style="background: #E6E6E6">ALTERNATE</td>
                <td style="background: #E6E6E6">&nbsp;</td>
                <td style="background: #E6E6E6">&nbsp;</td>
                <td style="background: #E6E6E6">&nbsp;</td>
                <td style="background: #E6E6E6">&nbsp;</td>
              </tr>
              <tr>
                <td style="background: #E6E6E6"><b>Flight #</b></td>
                <td><?php echo $row['flight_plan_number'] ?></td>
                <td style="background: #E6E6E6">HOLDING</td>
                <td><?php echo $row['holding_flt_1'] ?></td>
                <td><?php echo $row['holding_flt_2'] ?></td>
                <td><?php echo $row['holding_flt_3'] ?></td>
                <td><?php echo $row['holding_flt_4'] ?></td>
                <td style="background: #E6E6E6" rowspan="2"><b>Flight 3<br/>ETD</b></td>
                <td rowspan="2"><?php echo $row['etd_flt_3'] ?></td>
              </tr>
              <tr>
                <td style="background: #E6E6E6"><b>PK-</b></td>
                <td><?php echo $row['aircraft_reg_code'] ?></td>
                <td style="background: #E6E6E6">CONTINGENCY</td>
                <td><?php echo $row['contingency_flt_1'] ?></td>
                <td><?php echo $row['contingency_flt_2'] ?></td>
                <td><?php echo $row['contingency_flt_3'] ?></td>
                <td><?php echo $row['contingency_flt_4'] ?></td>
              </tr>
              <tr>
                <td style="background: #E6E6E6"><b>Type</b></td>
                <td><?php echo $row['flight_plan_type'] ?></td>
                <td style="background: #E6E6E6">MIN DISPATCH FUEL &raquo;&raquo;</td>
                <td style="background: #E6E6E6">&nbsp;</td>
                <td style="background: #E6E6E6">&nbsp;</td>
                <td style="background: #E6E6E6">&nbsp;</td>
                <td style="background: #E6E6E6">&nbsp;</td>
                <td style="background: #E6E6E6" rowspan="2"><b>Flight 4<br/>ETD</b></td>
                <td rowspan="2"><?php echo $row['etd_flt_4'] ?></td>
              </tr>
              <tr>
                <td style="background: #E6E6E6"><b>Rules</b></td>
                <td><?php echo $row['flight_plan_rules'] ?></td>
                <td style="background: #E6E6E6"><b>FUEL ON BOARD &raquo;&raquo;</b></td>
                <td><?php echo $row['fob_flt_1'] ?></td>
                <td><?php echo $row['fob_flt_2'] ?></td>
                <td><?php echo $row['fob_flt_3'] ?></td>
                <td><?php echo $row['fob_flt_4'] ?></td>
              </tr>
            </table>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">&nbsp;</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="block table-responsive">
        <form id="form_simpan">
          <input type="hidden" name="flight_plan_id" value="<?php echo $row['flight_plan_id'] ?>">
          <table class="table text-center table-sm table-vcenter table-bordered" style="border-color:1px solid #000000" border="1" bordercolor="#000000">
            <tr>
              <td><label class="bmd-label-floating">Flight #</label></td>
              <td>
                <select required name="flight_no">
                  <option value=""> -- Choose -- </option>
                  <option value="1">1</option>
                  <option value="1">2</option>
                  <option value="1">3</option>
                  <option value="1">4</option>
                </select>
              </td>
              <td>
                <label class="bmd-label-floating">WIN DIR & SPD &raquo;&raquo;</label></td>
              <td>
                <input type="text" name="win_dir" autocomplete="off" size="4" />
              </td>
              <td><label class="bmd-label-floating">KNOTS</label></td>
              <td>
                <input type="text" name="knots" autocomplete="off" size="4" />
              </td>
              <td style="background: #E6E6E6">&nbsp;</td>
              <td><label class="bmd-label-floating">ATD &raquo;&raquo;</label></td>
              <td>
                <input type="text" name="atd" id="atd" autocomplete="off" size="4" />
              </td>
              <td style="background: #E6E6E6"><label class="bmd-label-floating">ETA &raquo;&raquo;</label></td>
              <td style="background: #E6E6E6">&nbsp;</td>
            </tr>
            <tr>
              <td style="background: #E6E6E6">Waypoints FROM</td>
              <td style="background: #E6E6E6">ALT</td>
              <td style="background: #E6E6E6">DIST</td>
              <td style="background: #E6E6E6" rowspan="2">TAS</td>
              <td style="background: #E6E6E6" rowspan="2">GS</td>
              <td style="background: #E6E6E6">ETE</td>
              <td style="background: #E6E6E6" rowspan="2">ETA</td>
              <td style="background: #E6E6E6" rowspan="2">ATA</td>
              <td style="background: #E6E6E6" rowspan="2">LEG FUEL</td>
              <td style="background: #E6E6E6">FUEL ACC</td>
              <td style="background: #E6E6E6" rowspan="2">ACT FUEL REM</td>
            </tr>
            <tr>
              <td style="background: #E6E6E6">WAYPOINTS TO</td>
              <td style="background: #E6E6E6">TRK</td>
              <td style="background: #E6E6E6">ACC</td>
              <td style="background: #E6E6E6">ACC</td>
              <td style="background: #E6E6E6">FUEL REM</td>
            </tr>
            <tr>
              <td><input type="text" name="waypoints_from" autocomplete="off" size="10" /></td>
              <td><input type="text" name="alt" autocomplete="off" size="6" /></td>
              <td><input type="text" name="dist" autocomplete="off" size="6" /></td>
              <td rowspan="2"><input type="text" name="tas" autocomplete="off" size="6" /></td>
              <td style="background: #E6E6E6">&nbsp;</td>
              <td style="background: #E6E6E6">&nbsp;</td>
              <td style="background: #E6E6E6">&nbsp;</td>
              <td rowspan="2"><input type="text" name="ata" id="ata" autocomplete="off" size="4" /></td>
              <td style="background: #E6E6E6">&nbsp;</td>
              <td style="background: #E6E6E6">&nbsp;</td>
              <td rowspan="2"><input type="text" name="act_fuel_rem" autocomplete="off" size="4" /></td>
            </tr>
            <tr>
              <td><input type="text" name="waypoints_to" autocomplete="off" size="10" /></td>
              <td><input type="text" name="trk" autocomplete="off" size="6" /></td>
              <td style="background: #E6E6E6">&nbsp;</td>
              <td style="background: #E6E6E6">&nbsp;</td>
              <td style="background: #E6E6E6">&nbsp;</td>
              <td style="background: #E6E6E6">&nbsp;</td>
              <td style="background: #E6E6E6">&nbsp;</td>
              <td style="background: #E6E6E6">&nbsp;</td>
            </tr>            
          </table>
          <div id="results"></div><div id="button"></div>
          <div class="clearfix"></div>
        </form>
      </div>
    </div>
  </div>
  <!-- END Page Content -->
</main>
<?php require_once 'footer.php' ?>

<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="flight-plan.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
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
      url:"flight-plan-update.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="flight-plan.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
  });  
});  

$(document).ready(function() {

  $("#atd").attr("maxlength", 5);
  $("#atd").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  });  

  $("#ata").attr("maxlength", 5);
  $("#ata").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + ":");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "");
      }
  }); 

}); 
</script>

