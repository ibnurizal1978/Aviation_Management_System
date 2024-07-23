<?php
require_once 'header.php';

$sql  = "SELECT wo_trx_id,wo_number,aircraft_reg_code FROM tbl_maintenance_wo a INNER JOIN tbl_aircraft_master b USING (aircraft_master_id) WHERE wo_trx_id = '".$_GET['ntf']."' LIMIT 1";
$h    = mysqli_query($conn, $sql);
$row  = mysqli_fetch_assoc($h);
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

    <?php if(@$_GET['q']=='834urnjks') { ?>
      <div class="alert alert-danger alert-dismissable" role="alert">
        <p class="mb-0">Duplicate WO Number</p>
      </div>
    <?php } ?>

    <?php if(@$_GET['q']=='wiofaakms') { ?>
      <div class="alert alert-danger alert-dismissable" role="alert">
        <p class="mb-0">Please fill Subject and WO Number</p>
      </div>
    <?php } ?>

    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Create New Work Order</h3>
      </div>
      <div class="block-content">
        <div class="card-body">
          <form action="maintenance-wo1-add.php" method="POST">
            <input type="hidden" name="wo_trx_id" value="<?php echo $row['wo_trx_id'] ?>">
            <input type="hidden" name="wo_number" value="<?php echo $row['wo_number'] ?>">
            <div class="row">
              <div class="col-md-12">
                <table width="100%" border="1" cellpadding="10">
                  <tr>
                    <td width="10%">
                      <img src="../assets/img/logo.jpg">
                    </td>
                    <td align="center">
                      <b>PT. SMART CAKRAWALA AVIATION</b>
                      <h3>WORK ORDER</h3>
                      Form: SCA/MTC/030
                      <br/><br/>
                    </td>
                  </tr>
                </table>
                <table width="100%" border="1" cellpadding="10">
                  <tr>
                    <td rowspan="4" width="50%">Subject:<br/>
                      <textarea name="wo_subject" style="width: 90%" rows="2"></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td>No</td><td><?php echo $row['wo_number'] ?></td>
                  </tr>
                  <tr>
                    <td>Date</td><td><?php echo date('d/m/Y') ?></td>
                  </tr>
                  <tr>
                    <td>A/C Reg</td><td><?php echo $row['aircraft_reg_code'] ?></td>
                  </tr>

                  <tr>
                    <td rowspan="4" width="50%">Reference:<br/>
                      <textarea name="wo_reference" style="width: 90%" rows="2"></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td>Prepared By</td><td>TS</td>
                  </tr>
                  <tr>
                    <td>Checked By</td><td>CI</td>
                  </tr>
                  <tr>
                    <td>Approved By</td><td>TM</td>
                  </tr>

                  <tr>
                    <td colspan="3">To: <input type="text" size="50" name="wo_engineer_in_charge"></td>
                  </tr>

                  <tr>
                    <td colspan="3">Description<br/>
                      <textarea name="wo_description" style="width: 90%" rows="5"></textarea>
                    </td>
                  </tr> 

                  <tr>
                    <td colspan="3">Additional Work<br/>
                      <textarea name="wo_additional_work" style="width: 90%" rows="5"></textarea>
                    </td>
                  </tr> 
                </table>

                <table width="100%" border="1" cellpadding="10">
                  <tr>
                    <td valign="top" width="33%">Compliance Statement</td>
                    <td valign="top" width="33%">Sign & Date Company<br/>Lic. No.:<br/><br/><br/><br/><br/>(Engineer In Charge)</td>
                    <td valign="top">Signature<br/><br/><br/><br/><br/><br/>(Technical Manager)</td>
                  </tr>
                </table>
                <br/><br/><br/>&nbsp;&nbsp;&nbsp;Appendix B - Form: SCA/MTC/030 <br/><br/><br/><br/>
                <table width="100%" border="0" cellpadding="10">
                  <tr>
                    <td> 
                    <input type="submit" class="btn btn-success mr-5 mb-5" id="submit_data" onclick="return confirm('Are you sure this quantity is correct?');" value="Save and Next">          
                      <div id="results"></div><div id="button"></div>
                      <div class="clearfix"></div>
                    </td>
                  </tr>
                </table>                                 
          </form>

        </div>
      </div>
    </div>
  <!-- END Small Table -->

  <!-- END Page Content -->
  </div>
</main>
<?php require_once 'footer.php' ?>