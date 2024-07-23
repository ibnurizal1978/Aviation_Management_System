<?php 
require_once 'header.php';
//require_once 'components.php';
$sql  = "SELECT project_master_id,project_master_name FROM tbl_project_master WHERE project_master_id = '".$ntf[1]."' LIMIT 1";
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
            <div class="block pull-t pull-r-l">
                <div class="block-content block-content-full block-content-sm bg-body-light">
                    <form action="petty-cash.php" method="post">
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
            </div>
            <!-- END Search -->

            <!-- Profile -->
            <div class="block pull-r-l">
                <div class="block-header bg-body-light">
                    <h3 class="block-title">
                        <i class="fa fa-fw fa-pencil font-size-default mr-5"></i>Sort by
                    </h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                    </div>
                </div>
                <div class="block-content">
                    <form action="petty-cash.php" method="post">
                        <div class="form-group mb-15">
                            <div class="form-material floating">
                                <select class="form-control" id="material-select2" name="s">
                                    <option></option><!-- Empty value for demostrating material select box -->
                                    <option value="1">By Name A-Z</option>
                                </select>
                                <label for="material-select2">Please Select</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-block btn-alt-primary">
                                    <i class="fa fa-refresh mr-5"></i> View
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
                <h3 class="block-title">Fund This Project: <?php echo $row['project_master_name'] ?></h3>
                <div class="block-options">
                    <div class="block-options-item">
                        <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                            <i class="fa fa-filter"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="block-content">
                <table class="table table-sm table-vcenter">
                    <thead>
                        <tr>
                            <th>PIC Name</th>
                            <th width="25%" style="text-align:right">Current Amount</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sql2 = "SELECT a.user_id,full_name,deposit,project_master_id FROM tbl_user a INNER JOIN tbl_project_engineer_team b ON a.user_id = b.engineer_user_id WHERE project_master_id = '".$row['project_master_id']."'";
                        $h2   = mysqli_query($conn,$sql2);
                        while ($row2 = mysqli_fetch_assoc($h2)) { 
                        ?>
                        <tr>
                            <td><?php echo $row2['full_name'] ?></td>
                            <td width="25%" align="right">
                                <?php 
                                if($row2['deposit']<0) {
                                echo '<font class=text-danger>IDR '.number_format($row2['deposit'],0,",",".").'</font>';
                                }else{
                                echo 'IDR '.number_format($row2['deposit'],0,",","."); 
                                } 
                                ?>
                            </td>                            
                            <td class="text-center">
                                <div class="btn-group">
                                    <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View" href="petty-cash-detail.php?act=29dvi59&ntf=29dvi59-<?php echo $row2["project_master_id"]?>-<?php echo $row2["user_id"]?>-94dfvj!sdf-349ffuaw">
                                        <i class="fa fa-stack-exchange"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } mysqli_free_result($h2); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Small Table -->


        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Add Deposit</h3>
            </div>
            <div class="block-content">            
                <form id="form_simpan">
                    <input type="hidden" name="project_master_id" value="<?php echo $row['project_master_id'] ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Choose PIC</label>
                                <select class="form-control" data-style="select-with-transition" required name="engineer_user_id" style="padding-left: 5px; padding-right: 5px">
                                    <option value=""> -- None -- </option>
                                    <?php
                                    $sql  = "SELECT engineer_user_id,full_name,deposit FROM tbl_user a INNER JOIN tbl_project_engineer_team b ON a.user_id = b.engineer_user_id WHERE project_master_id = '".$row['project_master_id']."'";
                                    $h    = mysqli_query($conn,$sql);
                                    while($row1 = mysqli_fetch_assoc($h)) {
                                    ?>
                                    <option value="<?php echo $row1['engineer_user_id'] ?>"><?php echo $row1['full_name'] ?></option> 
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Amount (type number only)</label>
                                <input type="text" class="form-control" name="deposit" onkeyup = "javascript:this.value=Comma(this.value);" autocomplete="off" />
                            </div>
                        </div>             
                    </div>
                    <div id="results"></div><div id="button"></div>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>

    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>

<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="petty-cash.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
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
      url:"petty-cash-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Save</button> <a class="btn btn-info mr-5 mb-5" href="petty-cash.php"><i class="si si-arrow-left mr-5"></i>Back</a>');  
      }  
    });  
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