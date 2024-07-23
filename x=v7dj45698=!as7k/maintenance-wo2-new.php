<?php
require_once 'header.php';

$sql  = "SELECT aircraft_reg_code,aircraft_serial_number,wo_subject,a.wo_number,aircraft_reg_code,date_format(a.created_date, '%d/%m/%Y') as created_date, engine_serial_number, prop_serial_number FROM tbl_maintenance_wo a INNER JOIN tbl_wo USING (wo_trx_id) INNER JOIN tbl_aircraft_master b USING (aircraft_master_id) WHERE wo_trx_id = '".$_GET['ntf']."' LIMIT 1";
echo $sql;
$h    = mysqli_query($conn, $sql);
$row  = mysqli_fetch_assoc($h);
$ac_type = explode('-', $row['aircraft_serial_number']);
?>

<script type="text/javascript">
(function (global) {

    if(typeof (global) === "undefined")
    {
        throw new Error("window is undefined");
    }

    var _hash = "!";
    var noBackPlease = function () {
        global.location.href += "#";

        // making sure we have the fruit available for juice....
        // 50 milliseconds for just once do not cost much (^__^)
        global.setTimeout(function () {
            global.location.href += "!";
        }, 50);
    };
    
    // Earlier we had setInerval here....
    global.onhashchange = function () {
        if (global.location.hash !== _hash) {
            global.location.hash = _hash;
        }
    };

    global.onload = function () {
        
        noBackPlease();

        // disables backspace on page except on input fields and textarea..
        document.body.onkeydown = function (e) {
            var elm = e.target.nodeName.toLowerCase();
            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                e.preventDefault();
            }
            // stopping event bubbling up the DOM tree..
            e.stopPropagation();
        };
        
    };

})(window);    
</script>

<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">AIRCRAFT CHECK WORK SUMMARY</h3>
      </div>
      <div class="block-content">
        <div class="card-body">
          <form id="form_simpan">
            <div class="row">
              <div class="col-md-12">
                <table width="100%" border="1" cellpadding="10">
                  <tr>
                    <td align="center">
                      <h3>AIRCRAFT CHECK WORK SUMMARY</h3>
                      Form: SCA/MTC/051
                      <br/><br/>
                    </td>
                  </tr>
                </table>
                <table width="100%" border="1" cellpadding="10">
                  <tr bgcolor="#d8eafe">
                    <td>DATE OF ISSUED</td>
                    <td>JO/WO#</td>
                    <td>TYPE OF MAINTENANCE</td>
                    <td>DATE OF ACCOMPLISHED</td>
                  </tr>
                  <tr>
                    <td><?php echo $row['created_date'] ?></td>
                    <td><?php echo $row['wo_number'] ?></td>
                    <td><?php echo $row['wo_subject'] ?></td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
                <br/>
                <table width="100%" border="1" cellpadding="10">
                  <tr bgcolor="#d8eafe">
                    <td>A/C Type</td>
                    <td>Mfg. Serial Number</td>
                    <td>A/C Registration</td>
                  </tr>
                  <tr>
                    <td><?php echo $ac_type[0] ?></td>
                    <td><?php echo $row['aircraft_serial_number'] ?></td>
                    <td><?php echo $row['aircraft_reg_code'] ?></td>
                  </tr>
                </table>
                <br/>
                <table width="100%" border="1" cellpadding="10">
                  <tr bgcolor="#d8eafe">
                    <td colspan="4" align="center">AIRCRAFT DATA</td>
                  </tr>
                  <tr bgcolor="#d8eafe">
                    <td>Subject</td>
                    <td>Pos #</td>
                    <td>Serial Number</td>
                    <td>TTSN/TCSN</td>
                  </tr>
                  <tr>
                    <td bgcolor="#d8eafe">Engine</td>
                    <td>#1</td>
                    <td><?php echo $row['engine_serial_number'] ?></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td bgcolor="#d8eafe">Engine</td>
                    <td>#2</td>
                    <td>-</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td bgcolor="#d8eafe">Propeller/Rotor</td>
                    <td>#1</td>
                    <td><?php echo $row['prop_serial_number'] ?></td>
                    <td></td>
                  </tr>                  
                  <tr>
                    <td bgcolor="#d8eafe">Propeller/Rotor</td>
                    <td>#2</td>
                    <td>-</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td bgcolor="#d8eafe">Landing Gear</td>
                    <td>NLG</td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td bgcolor="#d8eafe">Landing Gear</td>
                    <td>LH MLG</td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td bgcolor="#d8eafe">Landing Gear</td>
                    <td>RH MLG</td>
                    <td></td>
                    <td></td>
                  </tr>
                </table>
                <br/>
                <table width="100%" border="1" cellpadding="10">
                  <tr bgcolor="#d8eafe">
                    <td colspan="4" align="center">PACKAGE COVERED</td>
                  </tr>
                  <tr bgcolor="#d8eafe">
                    <td width="3%">No.</td>
                    <td width="30%">Subject</td>
                    <td width="10%">Qty</td>
                    <td>Remark</td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>Non-Routine Card</td>
                    <td><input type="text" name="p1" placeholder="0" size="2" /></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>Inspection Card</td>
                    <td><input type="text" name="p2" placeholder="0" size="2" /></td>
                    <td></td>
                  </tr>
                  <tr>                    
                    <td>3</td>
                    <td>Work Order</td>
                    <td><input type="text" name="p3" placeholder="0" size="2" /></td>
                    <td></td>
                  </tr>
                  <tr>                    
                    <td>4</td>
                    <td>Summary Inspection List</td>
                    <td><input type="text" name="p4" placeholder="0" size="2" /></td>
                    <td></td>
                  </tr>
                  <tr>                    
                    <td>5</td>
                    <td>Material and Tool List</td>
                    <td><input type="text" name="p5" placeholder="0" size="2" /></td>
                    <td></td>
                  </tr>
                  <tr>                    
                    <td>6</td>
                    <td>Escalation Order</td>
                    <td><input type="text" name="p6" placeholder="0" size="2" /></td>
                    <td></td>
                  </tr>
                  <tr>                    
                    <td>7</td>
                    <td>CRS (SMI/Unscheduled Maintenance)</td>
                    <td><input type="text" name="p7" placeholder="0" size="2" /></td>
                    <td></td>
                  </tr>
                </table>                    
                <br/>
                <table width="100%" border="1" cellpadding="10">
                  <tr bgcolor="#d8eafe">
                    <td colspan="6" align="center">INSPECTION CARD (IC) LIST (Finding during maintenance)</td>
                  </tr>
                  <tr bgcolor="#d8eafe" align="center">
                    <td rowspan="2">No.</td>
                    <td rowspan="2">Taskcard Ref</td>
                    <td rowspan="2">Subject</td>
                    <td colspan="2" align="center">Status</td>
                    <td rowspan="2">Name / Sign & Stamp</td>
                  </tr>
                  <tr bgcolor="#d8eafe" align="center">
                    <td>Open</td>
                    <td>Close</td>
                  </tr>
                  <tr>
                    <td>IC-001</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>                
                  <tr>
                    <td>IC-002</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr> 
                  <tr>
                    <td>IC-003</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr> 
                  <tr>
                    <td>IC-004</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr> 
                  <tr>
                    <td>IC-005</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </table>
                <br/>
                <table width="100%">
                  <tr>
                    <td width="25%">Prepared by:<br/>Technical Support<p>&nbsp;</p><p>&nbsp;</p>...................
                    </td>
                    <td width="25%">Checked by:<br/>Chief Maintenance<p>&nbsp;</p><p>&nbsp;</p>...................
                    </td>
                    <td width="25%">Verified by:<br/>Chief Inspector<p>&nbsp;</p><p>&nbsp;</p>...................
                    </td>
                    <td>Approved by:<br/>Technical Manager<p>&nbsp;</p><p>&nbsp;</p>...................
                    </td>
                  </tr>
                </table>
              </div>
            </div>
            <br/><br/>
            <input type="submit" class="btn btn-success mr-5 mb-5" id="submit_data" onclick="return confirm('Are you sure this quantity is correct?');" value="Save and Next"><a class="btn btn-danger mr-5 mb-5" href="maintenance-wo1-new.php?q=wroienjfsldm&ntf=<?php echo $wo_number ?>">Back</a>
          </form>
        </div>
      </div>
    </div>
  <!-- END Small Table -->

  <!-- END Page Content -->
  </div>
</main>
<?php require_once 'footer.php' ?>

<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save and Next</button>');  
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
      url:"safety-finding-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save and Next</button>');  
      }  
    });  
  });  
});  

$(document).ready(function() {

  $("#safety_finding_target_date").attr("maxlength", 10);
  $("#safety_finding_target_date").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });
      
}); 
</script>

