<?php 
require_once 'header.php';
if($ntf[1]<>'') {
  $aircraft_master_id = $ntf[1];
}else{
  $aircraft_master_id = $_REQUEST['aircraft_master_id'];
}
$sql  = "SELECT *,date_format(manufacture_date, '%d/%m/%Y') as manufacture_date FROM tbl_aircraft_master a INNER JOIN tbl_aircraft_type b USING (aircraft_type_id) WHERE aircraft_master_id = '".$aircraft_master_id."' LIMIT 1";

$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
$aircraft_master_id = $row['aircraft_master_id'];
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
                    <form action="aircraft-parts.php" method="post">
                        <input type="hidden" name="s" value="1091vdf8ame151">
                        <input type="hidden" name="aircraft_master_id" value="<?php echo $row['aircraft_master_id'] ?>">
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
                    <form action="aircraft-parts.php" method="post">
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
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Upload New Components for <?php echo $row['aircraft_reg_code'] ?></h3>
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
            <input type="hidden" name="aircraft_master_id" value="<?php echo $row['aircraft_master_id'] ?>">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="col-12">Format allowed: XLS. Uploaded file will overwrite existing component data</label>
                  <div class="col-12">
                    <input type="file" name="upload_file" id="upload_file">
                  </div>
                </div>
              </div>
            </div>
            <div id="results"></div><div id="button"></div>
            <div class="clearfix"></div>
          </form>
        </div>
      </div>
    </div>
    <!-- END Small Table -->

    <!--certificate-->
    <div class="block table-responsive">
      <div class="block-header block-header-default">
          <h3 class="block-title">Components List</h3>
      </div>
      <div class="block-content">
        <?php
        @$page = @$_REQUEST['page'];
        $dataPerPage = 10;
        if(isset($_GET['page']))
        {
            $noPage = $_GET['page'];
        }
        else $noPage = 1;
        @$offset = ($noPage - 1) * $dataPerPage;
        //for total count data
        if(@$_REQUEST['s']=='1091vdf8ame151') {
          $txt_search   = input_data(filter_var($_REQUEST['txt_search'],FILTER_SANITIZE_STRING));
          $aircraft_master_id   = input_data(filter_var($_POST['aircraft_master_id'],FILTER_SANITIZE_STRING));
          $sql = "SELECT *,date_format(installed_date, '%d/%m/%Y') as installed_date FROM tbl_aircraft_parts WHERE (description LIKE '%$txt_search%' OR part_number LIKE '%$txt_search%' OR serial_number LIKE '%$txt_search%') AND aircraft_master_id ='".$aircraft_master_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT $offset, $dataPerPage";     
        }else{
          $sql = "SELECT *,date_format(installed_date, '%d/%m/%Y') as installed_date FROM tbl_aircraft_parts WHERE aircraft_master_id ='".$aircraft_master_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT $offset, $dataPerPage";
        }
        $rs_result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($rs_result)==0) {
        ?>
          <p class="text-center text-danger" role="alert">Oops, no data found</p>
        <?php  
        //exit(); 
        }
        ?>
        <table class="table table-sm table-vcenter">
          <thead>
            <tr>
              <th>Item No</th>
              <th>Position</th>
              <th>Description</th>
              <th>Part No.</th>
              <th>Serial No.</th>
              <th>MTH</th>
              <th>HRS</th>
              <th>LDG</th>
              <th>Installed Date</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
              <tr>
                <td><?php echo $row['item_number'] ?></td>
                <td><?php echo $row['position'] ?></td>
                <td><?php echo $row['description'] ?></td>
                <td><?php echo $row['part_number'] ?></td>
                <td><?php echo $row['serial_number'] ?></td>
                <td><?php echo $row['mth'] ?></td>
                <td><?php echo $row['hrs'] ?></td>
                <td><?php echo $row['ldg'] ?></td>
                <td><?php echo $row['installed_date'] ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a class="btn btn-sm btn-secondary" href="aircraft-parts-detail.php?act=29dvi59&page=<?php echo $_REQUEST['page'] ?>&ntf=29dvi59-<?php echo $row["aircraft_parts_id"]?>-94dfvj!sdf-349ffuaw"><i class="fa fa-pencil"></i>
                    </a>
                  </div>
                </td>
              </tr>           
              <?php } mysqli_free_result($rs_result);  ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php
    // COUNT TOTAL NUMBER OF ROWS IN TABLE
    if(@$_REQUEST['s']=='1091vdf8ame151') {           
      $sql = "SELECT count(aircraft_master_id) as jumData FROM tbl_aircraft_parts WHERE client_id = '".$_SESSION['client_id']."' AND (description LIKE '%$txt_search%' OR part_number LIKE '%$txt_search%' OR serial_number LIKE '%$txt_search%') AND aircraft_master_id ='".$aircraft_master_id."'";
    }else{            
      $sql = "SELECT count(aircraft_master_id) as jumData FROM tbl_aircraft_parts WHERE client_id = '".$_SESSION['client_id']."' AND aircraft_master_id ='".$aircraft_master_id."'";
    }          

    $hasil  = mysqli_query($conn,$sql);
    $data     = mysqli_fetch_assoc($hasil);
    $jumData = $data['jumData'];
    $jumPage = ceil($jumData/$dataPerPage);

    if ($noPage > 1) echo  "<a class=hal href='".$_SERVER['PHP_SELF']."?s=".@$_REQUEST['s']."&aircraft_master_id=".@$aircraft_master_id."&client_id=".@$_REQUEST['client_id']."&txt_search=".@$_REQUEST['txt_search']."&page=".($noPage-1)."'><i class=\"fa fa-angle-left\"></i></a>";

    for($page = 1; $page <= $jumPage; $page++)
    {
        if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage))
        {
            if ((@$showPage == 1) && ($page != 2))  echo "...";
            if ((@$showPage != ($jumPage - 1)) && ($page == $jumPage))  echo "...";
            if ($page == $noPage) echo " <b>".$page."</b> ";
            else echo " <a class=hal href='".$_SERVER['PHP_SELF']."?s=".@$_REQUEST['s']."&aircraft_master_id=".@$aircraft_master_id."&client_id=".@$_REQUEST['client_id']."&txt_search=".@$_REQUEST['txt_search']."&page=".$page."'>".$page."</a> ";
            @$showPage = $page;
        }
    }

    if ($noPage < $jumPage) echo "<a class=hal href='".$_SERVER['PHP_SELF']."?s=".@$_REQUEST['s']."&aircraft_master_id=".@$aircraft_master_id."&client_id=".@$_REQUEST['client_id']."&txt_search=".@$_REQUEST['txt_search']."&page=".($noPage+1)."'><i class=\"fa fa-angle-right\"></i></a>";
    mysqli_free_result($hasil); 
    ?>
    <!-- END Page Content -->
  </div>
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
<script>  
$(document).ready(function(){  
  $('#upload_file').change(function(){  
    $('#form_simpan').submit(); 
    $("#results").html('<img width=50 src=<?php echo $base_url ?>assets/img/loading.gif> Uploading...'); 
  });  
  $('#form_simpan').on('submit', function(event){  
    event.preventDefault();  
    $.ajax({  
      url:"aircraft-parts-upload.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#upload_file').val('');  
      }  
    });  
  });  
}); 
</script>