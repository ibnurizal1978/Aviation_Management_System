<?php 
require_once 'header.php';
//require_once 'components.php';
?>

<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">

        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Add Training/Education</h3>
            </div>
            <div class="block-content">            
                <form id="form_simpan">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="bmd-label-floating">From Year <br/>(YYYY format)</label>
                                <input type="text" class="form-control" id="year_from" name="year_from" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="bmd-label-floating">To Year<br/> (YYYY format)</label>
                                <input type="text" class="form-control" id="year_to" name="year_to" />
                            </div>
                        </div>             
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="bmd-label-floating">Training/Education</label>
                                <textarea class="form-control" name="description" rows=3 /></textarea>
                            </div>
                        </div>
                    </div>                    
                    <div id="results"></div><div id="button"></div>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>

        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">My Training & Education</h3>
            </div>
            <div class="block-content">
                <table class="table table-sm table-vcenter">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Detail</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sql2 = "SELECT training_id,year_from,year_to,description FROM tbl_user_cv_training WHERE user_id = '".$_SESSION['user_id']."' AND client_id = '".$_SESSION['client_id']."' ORDER BY year_from DESC";
                        $h2   = mysqli_query($conn,$sql2);
                        while ($row2 = mysqli_fetch_assoc($h2)) { 
                        ?>
                        <tr>
                            <td width="20%">
                                <?php 
                                echo $row2['year_from'];
                                if($row2['year_to']<>'0000') 
                                    { 
                                        echo '-'.$row2['year_to']; 
                                    } 
                                ?></td>
                            <td><?php echo nl2br($row2['description']); ?></td>                            
                            <td class="text-center">
                                <div class="btn-group">
                                    <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete" href="user-training-delete.php?act=29dvi59&ntf=29dvi59-<?php echo $row2["training_id"]?>-94dfvj!sdf-349ffuaw" onclick="return confirm('Are you sure you want to delete this item?');">
                                        <i class="si si-close"></i>
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
      url:"user-training-add.php",  
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

$(document).ready(function() {
  $("#year_from").attr("maxlength", 4);
  $("#year_from").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });

  $("#year_to").attr("maxlength", 4);
  $("#year_to").keyup(function(){
      if ($(this).val().length == 2){
          $(this).val($(this).val() + "");
      }else if ($(this).val().length == 5){
          $(this).val($(this).val() + "/");
      }
  });  
});
</script>