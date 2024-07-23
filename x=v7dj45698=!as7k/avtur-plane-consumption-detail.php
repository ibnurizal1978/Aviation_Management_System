<?php 
require_once 'header.php';
$aircraft_reg_code = $ntf[1].'-'.$ntf[2];
$sql  = "SELECT *,date_format(manufacture_date, '%d/%m/%Y') as manufacture_date FROM tbl_aircraft_master a INNER JOIN tbl_aircraft_type b USING (aircraft_type_id) WHERE aircraft_reg_code = '".$aircraft_reg_code."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
?>

<!-- Side Overlay-->
<aside id="side-overlay">
    <!-- Side Overlay Scroll Container -->
    <div id="side-overlay-scroll">
        <!-- Side Header -->
        <div class="content-header content-header-fullrow">
            <div class="content-header-section align-parent">
                <button type="button" class="btn btn-circle btn-dual-secondary align-v-r" data-toggle="layout" data-action="side_overlay_close">
                    <i class="fa fa-times text-danger"></i>
                </button>

                <div class="content-header-item">
                    <a class="align-middle link-effect text-primary-dark font-w600" href="#">Filter</a>
                </div>
                <!-- END User Info -->
            </div>
        </div>
        <!-- END Side filter -->

        <!-- side kanan -->
        <div class="content-side">
            <!-- Search -->
            <!--<div class="block pull-t pull-r-l">
                <div class="block-content block-content-full block-content-sm bg-body-light">
                    <form action="avtur-deposit-new.php" method="post">
                        <input type="hidden" name="s" value="1091vdf8ame151">
                        <div class="input-group">
                            <input type="text" class="form-control" id="side-overlay-search" name="txt_search" placeholder="Search..">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-secondary px-10">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>-->
            <!-- END Search -->

            <!-- Profile -->
            <div class="block pull-r-l">
                <div class="block-header bg-body-light">
                    <h3 class="block-title">
                        <i class="fa fa-fw fa-pencil font-size-default mr-5"></i>Download by Period
                    </h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                    </div>
                </div>
                <div class="block-content">
                    <form action="avtur-deposit-download.php" method="post">
                        <input type="hidden" name="x" value="1304" />
                        <div class="form-group mb-15">
                            <div class="form-material">
                                <input type="text" class="form-control" name="download_from" id="download_from">
                                <label for="material-select2">From date (dd/mm/yyyy)</label>
                            </div>
                            <div class="form-material">
                                <input type="text" class="form-control" name="download_to" id="download_to">
                                <label for="material-select2">To date (dd/mm/yyyy)</label>
                            </div>                            
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-block btn-alt-primary">
                                    <i class="si si-cloud-download mr-5"></i> Download
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="block pull-r-l">
                <div class="block-header bg-body-light">
                    <h3 class="block-title">
                        <i class="fa fa-fw fa-pencil font-size-default mr-5"></i>Download by Month
                    </h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                    </div>
                </div>
                <div class="block-content">
                    <form action="avtur-deposit-download.php" method="post">
                        <input type="hidden" name="x" value="1305" />
                        <div class="form-group mb-15">
                            <div class="form-material">
                                <select class="form-control" name="month">
                                    <option value="01">Jan</option>
                                    <option value="02">Feb</option>
                                    <option value="03">Mar</option>
                                    <option value="04">Apr</option>
                                    <option value="05">May</option>
                                    <option value="06">Jun</option>
                                    <option value="07">Jul</option>
                                    <option value="08">Aug</option>
                                    <option value="09">Sep</option>
                                    <option value="10">Oct</option>
                                    <option value="11">Nov</option>
                                    <option value="12">Des</option>
                                </select>
                                <select class="form-control" name="year">
                                    <?php for($i=2018;$i<date('Y')+1;$i++) { ?>
                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                    <?php } ?>
                                </select>
                            </div>                            
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-block btn-alt-primary">
                                    <i class="si si-cloud-download mr-5"></i> Download
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>            
            <!-- END filter -->
        </div>
        <!-- END Side filter -->
    </div>
    <!-- END Side Overlay Scroll Container -->
</aside>
<!-- END Side Overlay -->

<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Aircraft: <?php echo $aircraft_reg_code ?></h3>
                <div class="block-options">
                    <div class="block-options-item">
                        <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                            <i class="fa fa-filter"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="block-content">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="bmd-label-floating">Registration No.<label class="text-danger">*</label></label>
                      <input type="text" class="form-control" value="<?php echo $row['aircraft_reg_no'] ?>" />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="bmd-label-floating">Registration Code<label class="text-danger">*</label></label>
                      <input type="text" class="form-control" value="<?php echo $row['aircraft_reg_code'] ?>" />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="bmd-label-floating">Aircraft Type<label class="text-danger">*</label></label>
                      <input type="text" class="form-control" value="<?php echo $row['aircraft_type'] ?>" />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="bmd-label-floating">Category</label>
                      <input type="text" class="form-control" value="<?php echo $row['aircraft_type_name'] ?>" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="bmd-label-floating">Aircraft Serial Number</label>
                      <input type="text" class="form-control" value="<?php echo $row['aircraft_serial_number'] ?>" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="bmd-label-floating">Engine Part Number</label>
                      <input type="text" class="form-control" value="<?php echo $row['engine_part_number'] ?>" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="bmd-label-floating">Engine Serial Number</label>
                      <input type="text" class="form-control" value="<?php echo $row['engine_serial_number'] ?>" />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="bmd-label-floating">Prop Part Number</label>
                      <input type="text" class="form-control" value="<?php echo $row['prop_part_number'] ?>" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="bmd-label-floating">Prop Serial Number</label>
                      <input type="text" class="form-control"  value="<?php echo $row['prop_serial_number'] ?>" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="bmd-label-floating">Manufacture Year</label>
                      <input type="text" class="form-control" value="<?php echo $row['manufacture_date'] ?>" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="bmd-label-floating">Delivery Year</label>
                      <input type="text" class="form-control" value="<?php echo $row['delivery_date'] ?>" />  
                    </div>
                  </div>                                
                </div>
            </div>
        </div>
    </div>

    <div class="block-content">        
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Detail Aircraft Consumption (in Ltr)</h3>
                <div class="block-options">
                    <div class="block-options-item">
                        <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                            <i class="fa fa-filter"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="block-content">
                <table class="table table-sm table-bordered">
                    <tr style="font-weight: bold">
                        <td>Aircraft</td>
                        <td>Total Consumption</td>
                        <td>Last Fill</td>
                        <td>AFML</td>
                    </tr>
                    <?php
                    $sql = "SELECT afml_id,afml_amount as total,aircraft_reg_code, date_format(afml_fuel_date, '%d/%m/%Y') as afml_fuel_date FROM tbl_avtur_afml WHERE client_id = '".$_SESSION['client_id']."' AND afml_amount <> 0 AND aircraft_reg_code = '".$aircraft_reg_code."' ORDER BY afml_fuel_date DESC";
                    $h   = mysqli_query($conn,$sql);
                    while($row = mysqli_fetch_assoc($h)) {
                    ?>
                    <tr>
                        <td><?php echo $row['aircraft_reg_code'] ?></td>
                        <td><?php echo $row['total'] ?></td>
                        <td><?php echo $row['afml_fuel_date'] ?></td>
                        <td><a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Detail" href="afml-detail.php?act=29dvi59&ntf=29dvi59-<?php echo $row["afml_id"]?>-94dfvj!sdf-349ffuaw"><i class="si si-eye"></i></td> 
                    </tr>
                    <?php } ?>
                </table>          
            </div>
        </div>
        <!-- END Small Table -->        
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>

<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button>');  
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
      url:"avtur-deposit-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button>');  
      }  
    });  
  });

  $("#download_from").attr("maxlength", 11);
  $("#download_from").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });

  $("#download_to").attr("maxlength", 11);
  $("#download_to").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "/");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });        
    
});

function Comma(Num)
 {
       Num += '';
       Num = Num.replace(/,/g, '');
       x = Num.split('.');
       x1 = x[0];
       x2 = x.length > 1 ? '.' + x[1] : '';

         var rgx = /(\d)((\d{3}?)+)$/;

       while (rgx.test(x1))
       x1 = x1.replace(rgx, '$1' + ',' + '$2');    
       return x1 + x2;              
 } 
</script>