<?php 
require_once 'header.php';
$aircraft_master_id   = input_data(filter_var($_REQUEST['aircraft_master_id'],FILTER_SANITIZE_STRING));

$sql  = "SELECT *,date_format(aircraft_master_last_inspection, '%d/%m/%Y') as aircraft_master_last_inspection FROM tbl_aircraft_master a INNER JOIN tbl_aircraft_type b USING (aircraft_type_id) WHERE aircraft_master_id = '".$aircraft_master_id."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);

//a/c
$ac_hrs = substr($row['aircraft_total_hrs'], 0,-3);
$ac_hrs = str_replace(':', '.', $ac_hrs);
$ac_ldg = $row['aircraft_total_ldg'];

//eng
$eng_hrs = substr($row['aircraft_eng_1_total_hrs'], 0,-3);
$eng_hrs = str_replace(':', '.', $eng_hrs);
$eng_ldg = $row['aircraft_eng_1_total_ldg'];

//prop
$prop_hrs = substr($row['aircraft_prop_total_hrs'], 0,-3);
$prop_hrs = str_replace(':', '.', $prop_hrs);

if ($_POST['projection_type']<>'') {
    if($_POST['projection_type']=='MOS') {
        $ac_hrs_proj    = $_POST['due_value'] * 20;
        $ac_hrs_target  = $ac_hrs + $ac_hrs_proj;
        $ac_ldg_proj    = $_POST['due_value'] * 10;
        $ac_ldg_target  = $ac_ldg + $ac_ldg_proj;

        $eng_hrs_proj    = $_POST['due_value'] * 200;
        $eng_hrs_target  = $eng_hrs + $eng_hrs_proj;
        $eng_ldg_proj    = $_POST['due_value'] * 100;
        $eng_ldg_target  = $eng_ldg + $eng_ldg_proj;

        $prop_hrs_proj    = $_POST['due_value'] * 2000;
        $prop_hrs_target  = $prop_hrs + $prop_hrs_proj; 

        $mos     = $_POST['due_value'];
        $oldDate = date('Y-m-d');
        $tanggal = date("d/m/Y", strtotime("last day of +".$mos." months"));  
        //$tanggal = date('Y-m-d', strtotime($oldDate. " + {$_POST['due_value']} days"));          
    }

    if($_POST['projection_type']=='DAYS') {
        $ac_hrs_proj    = $_POST['due_value'] * 50;
        $ac_hrs_target  = $ac_hrs + $ac_hrs_proj;
        $ac_ldg_proj    = $_POST['due_value'] * 30;
        $ac_ldg_target  = $ac_ldg + $ac_ldg_proj;

        $eng_hrs_proj    = $_POST['due_value'] * 500;
        $eng_hrs_target  = $eng_hrs + $eng_hrs_proj;
        $eng_ldg_proj    = $_POST['due_value'] * 300;
        $eng_ldg_target  = $eng_ldg + $eng_ldg_proj;

        $prop_hrs_proj    = $_POST['due_value'] * 5000;
        $prop_hrs_target  = $prop_hrs + $prop_hrs_proj; 

        $mos     = $_POST['due_value'];
        $oldDate = date('Y-m-d');
        $date = new DateTime('+'.$mos.' day');
        $tanggal = $date->format('d/m/Y');                      
    }
            
}

if($_POST['sub_f']==1) {
    $ac_ldg_proj    = $_POST['ac_ldg_proj'];
    $ac_ldg_target  = $ac_ldg + $ac_ldg_proj;

    $eng_ldg_proj    = $_POST['due_value'] * 300;
    $eng_ldg_target  = $eng_ldg + $eng_ldg_proj;

    $mos     = $_POST['ac_hrs_target'];
    $oldDate = date('Y-m-d');
    $date = new DateTime('+'.$mos.' day');
    $tanggal = $date->format('d/m/Y');                      
}

?>
<script type="text/javascript">
function update_ac_hrs()
{
    var ac_hrs_proj = $("#ac_hrs_proj").val();
    var total = parseFloat(<?php echo '+'.$ac_hrs ?>) + +ac_hrs_proj;
    $("#ac_hrs_target").val(total);
    $("#eng_hrs_target").val(total);
    $("#prop_hrs_target").val(total);
    $("#eng_hrs_proj").val(ac_hrs_proj);
    $("#prop_hrs_proj").val(ac_hrs_proj);
}

function update_ac_ldg()
{
    var ac_ldg_proj = $("#ac_ldg_proj").val();
    var total = parseFloat(<?php echo '+'.$row['aircraft_total_ldg'] ?>) + +ac_ldg_proj;
    $("#ac_ldg_target").val(total);
    $("#eng_ldg_target").val(total);
    $("#eng_ldg_proj").val(ac_ldg_proj);
}

function update_eng_ldg()
{
    var eng_ldg_proj = $("#eng_ldg_proj").val();
    var total = parseFloat(<?php echo '+'.$row['aircraft_eng_1_total_ldg'] ?>) + +eng_ldg_proj;
    $("#ac_ldg_target").val(total);
    $("#eng_ldg_target").val(total);
    $("#ac_ldg_proj").val(eng_ldg_proj);
}
</script>

<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">    
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Maintenance Due</h3>
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
                    <form method="post" action="maintenance2.php">
                        <input type="hidden" name="aircraft_master_id" value="<?php echo $aircraft_master_id ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Aircraft: <?php echo $row['aircraft_reg_code'] ?></label>
                                    <br/>
                                    <a href="maintenance.php">Choose another aircraft</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="bmd-label-floating">View projection in</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">                            
                                    <input type="text" class="form-control" name="due_value" autocomplete="off" id="realisasi" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">                           
                                    <select required name="projection_type" onchange="this.form.submit()">
                                        <option value="">--Choose--</option>
                                            <option value="MOS">MOS</option>
                                            <option value="DAYS">DAY</option>
                                    </select> 
                                    &nbsp;
                                    <b>Selected value: <?php echo $_POST['due_value'].' '.$_POST['projection_type'] ?></b>                                   
                                </div>
                            </div> 
                        </div>
                    </form>
                    <form method="post" action="maintenance-detail.php"> 
                        <?php
                        $tgl_y   = substr($tanggal,6,4);
                        $tgl_m   = substr($tanggal,3,2);
                        $tgl_d   = substr($tanggal,0,2);
                        $tgl_f   = $tgl_y.'-'.$tgl_m.'-'.$tgl_d;
                        ?>
                        <input type="hidden" name="tanggal_detail" value="<?php echo $tgl_f ?>">                                          
                        <input type="hidden" name="due_value2" value="<?php echo $_POST['due_value'] ?>">
                        <input type="hidden" name="projection_type2" value="<?php echo $_POST['projection_type'] ?>">
                        <input type="hidden" name="aircraft_master_id" value="<?php echo $_POST['aircraft_master_id'] ?>">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">                                     
                                    <b>Target: <?php echo $tanggal ?></b><br/>
                                    <label class="bmd-label-floating text-danger">Last: <?php echo $row['aircraft_master_last_inspection'] ?></label>
                                </div>
                            </div>
                        </div>

                        <table class="table table-sm table-vcenter">
                            <thead>
                                <tr>
                                    <th width="20%">&nbsp;</th>
                                    <th>Total</th>
                                    <th>Projection</th>
                                    <th>Target</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4"><br/><b>A/C</b></td>
                                </tr>                        
                                <tr>
                                    <td>HRS</td>
                                    <td><?php echo $ac_hrs ?></td>
                                    <td><input type="text" value="<?php echo $ac_hrs_proj ?>" id="ac_hrs_proj" onChange="update_ac_hrs()" /></td>
                                    <td><input type="text" name="ac_hrs_target" value="<?php echo $ac_hrs_target ?>" id="ac_hrs_target" /></td>
                                </tr>
                                <tr>
                                    <td>AFL</td>
                                    <td><?php echo $row['aircraft_total_ldg'] ?></td>
                                    <td><input type="text" name="ac_ldg_proj" value="<?php echo $ac_ldg_proj ?>" id="ac_ldg_proj" onChange="update_ac_ldg()" /></td>
                                    <td><input type="text" name="ac_ldg_target" value="<?php echo $ac_ldg_target ?>" id="ac_ldg_target" /></td>
                                </tr>                        
                                <tr>
                                    <td colspan="4"><br/><b>Engine No. 1</b></td>
                                </tr>                        
                                <tr>
                                    <td>HRS</td>
                                    <td><?php echo $eng_hrs ?></td>
                                    <td><input type="text" value="<?php echo $eng_hrs_proj ?>" id="eng_hrs_proj" /></td>
                                    <td><input type="text" name="eng_hrs_target" value="<?php echo $eng_hrs_target ?>" id="eng_hrs_target" /></td>
                                </tr>
                                <tr>
                                    <td>ENC</td>
                                    <td><?php echo $row['aircraft_eng_1_total_ldg'] ?></td>
                                    <td><input type="text" value="<?php echo $eng_ldg_proj ?>" id="eng_ldg_proj" onChange="update_eng_ldg()"/></td>
                                    <td><input type="text" name="eng_ldg_target" value="<?php echo $eng_ldg_target ?>" id="eng_ldg_target" /></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><br/><b>Prop</b></td>
                                </tr>                        
                                <tr>
                                    <td>HRS</td>
                                    <td><?php echo $prop_hrs ?></td>
                                    <td><input type="text" value="<?php echo $prop_hrs_proj ?>" id="prop_hrs_proj" /></td>
                                    <td><input type="text" name="prop_hrs_target" value="<?php echo $prop_hrs_target ?>" id="prop_hrs_target" /></td>
                                </tr>                        
                            </tbody>
                        </table>
                
                        <input type="submit" class="btn btn-success mr-5 mb-5" value="View" />
                        <!--<div id="results"></div><div id="button"></div>
                        <div class="clearfix"></div>-->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-eye mr-5"></i>View</button>');  
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
      url:"maintenance-detail.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-eye mr-5"></i>View</button>');  
      }  
    });  
  });  
});
</script>