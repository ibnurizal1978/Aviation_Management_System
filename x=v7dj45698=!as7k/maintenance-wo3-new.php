<?php
require_once 'header.php';
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
        <h3 class="block-title">Create New Work Order</h3>
        <div class="block-options">
          <div class="block-options-item">
            <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                <i class="fa fa-filter"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="block-content">
        <div class="card-body">
          <form id="form_simpan">
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
                      Form: <input type="text" name="wo_form" />
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
                    <td>No</td><td><input type="text" name="wo_number" /></td>
                  </tr>
                  <tr>
                    <td>Date</td><td><?php echo date('d/m/Y') ?></td>
                  </tr>
                  <tr>
                    <td>A/C Reg</td><td>PK-SNS</td>
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
                    <td colspan="3">To: Engineer in Charge</td>
                  </tr>

                  <tr>
                    <td colspan="3">Description<br/>
                      <textarea name="wo_additional_work" style="width: 90%" rows="5"></textarea>
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
                <br/><br/>  
                <table width="100%" border="1" cellpadding="10">
                  <tr>
                    <td>            
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

