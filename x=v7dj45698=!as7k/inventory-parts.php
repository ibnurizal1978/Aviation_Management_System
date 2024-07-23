<?php 
require_once 'header.php';
//require_once 'components.php';
?>

<!--separator credit limit-->
<script type='text/javascript'>
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
<!--end separator credit limit--> 

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
                    <form action="inventory-parts.php" method="post">
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
                    <form action="inventory-parts.php" method="post">
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

      <?php if(@$ntf[0]=="r827ao") {?>
        <div class="alert alert-danger ks-solid text-center" role="alert">Please fill all forms</div>
      <?php } ?> 
      <?php if(@$ntf[0]=="dpk739a") {?>
        <div class="alert alert-danger ks-solid text-center" role="alert">Duplicate serial number</div>
      <?php } ?>
      <?php if(@$ntf[0]=="r1029wkw") {?>
        <div class="alert alert-success ks-solid text-center" role="alert">Data successfully updated</div>
      <?php } ?>
      <?php if(@$ntf[0]=="r1029wkwedt") {?>
        <div class="alert alert-success ks-solid text-center" role="alert">Data successfully updated</div>
      <?php } ?>
      <?php if($ntf[0]=="phr827ao") {?>
          <div class="alert alert-danger ks-solid text-center" role="alert">Please choose photo from your file</div>
      <?php } ?> 
      <?php if($ntf[0]=="phs1s34plod") {?>
          <div class="alert alert-danger ks-solid text-center" role="alert">Maximum file size is 1 MB</div>
      <?php } ?>
      <?php if($ntf[0]=="phty3f1l3") {?>
          <div class="alert alert-success ks-solid text-center" role="alert">Maximum file size is 1 MB</div>
      <?php } ?>
      <?php if($ntf[0]=="phr1029wkwedt") {?>
          <div class="alert alert-success ks-solid text-center" role="alert">Photo successfully added</div>
      <?php } ?>
      <?php if(@$ntf[0]=="29dvi59") {?>
          <div class="alert alert-success ks-solid text-center" role="alert">Photo successfully deleted</div>
      <?php } ?> 


    <!-- Small Table -->
    <!--begin list data-->
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
        $sql = "SELECT parts_id,parts_name,parts_stock,parts_number, serial_number, parts_price FROM tbl_parts a WHERE (parts_name LIKE '%$txt_search%' OR parts_number LIKE '%$txt_search%') AND a.client_id = '".$_SESSION['client_id']."' ORDER BY parts_name ASC LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='1'){
        $sql = "SELECT parts_id,parts_name,parts_stock,parts_number, serial_number, parts_price FROM tbl_parts a WHERE a.client_id = '".$_SESSION['client_id']."' ORDER by parts_name LIMIT $offset, $dataPerPage";      
      }else{
        $sql = "SELECT parts_id,parts_name,parts_stock,parts_number, serial_number, parts_price FROM tbl_parts a WHERE a.client_id = '".$_SESSION['client_id']."' ORDER BY parts_name ASC LIMIT $offset, $dataPerPage";
      }
      $rs_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($rs_result)==0) {
      ?>
        <div class="alert alert-info ks-solid text-center" role="alert">Oops, no data found</div>
    <?php } ?>    
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Parts Data</h3>
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
                            <th width="50%">Part Name</th>
                            <th width="15%">P/N</th>
                            <!--<th width="15%">S/N</th>-->
                            <th width="10%">Qty</th>
                            <th width="10%">Price (USD)</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                        <tr>
                            <td><?php echo $row['parts_name'] ?></td>
                            <td><?php echo $row['parts_number'] ?></td>
                            <!--<td><?php echo $row['serial_number'] ?></td>-->
                            <td><?php echo $row['parts_stock'] ?></td>
                            <td><?php echo '$ '.number_format($row['parts_price'],2,",",".") ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a data-toggle="modal" data-target="#edit-<?php echo $row['parts_id'] ?>" class="btn btn-sm btn-secondary">edit</a>
                              </div>

                      <!-- .modal -->                     
                      <form ui-jp="parsley" method="POST" action="inventory-parts-edit01.php">
                        <input type="hidden" name="parts_id" value="<?php echo $row['parts_id'] ?>">
                        <div id="edit-<?php echo $row['parts_id'] ?>" class="modal fade" data-backdrop="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title"><?php echo $row['parts_name'] ?></h5>
                              </div>
                              <div class="modal-body">
                              <!--begin isi modal-->             
                                <div class="box">
                                  <div class="box-body">
                                    <div class="form-group">
                                      <label>Part Name <font class="red">(required)</font></label>
                                      <input type="text" class="form-control" required name="parts_name" value="<?php echo $row['parts_name'] ?>">
                                    </div>

                                    <div class="form-group">
                                      <label>Part Number </label>
                                      <input type="text" class="form-control" name="parts_number" value="<?php echo $row['parts_number'] ?>">
                                    </div>

                                    <!--<div class="form-group">
                                      <label>Serial Number </label>
                                      <input type="number" class="form-control" name="serial_number" value="<?php echo $row['serial_number'] ?>">
                                    </div>-->

                                    <div class="form-group">
                                      <label>Qty </label>
                                      <input type="number" class="form-control" name="parts_stock" value="<?php echo $row['parts_stock'] ?>">
                                    </div>

                                    <div class="form-group">
                                      <label>Price </label>
                                      <input type="text" class="form-control" name="parts_price" value="<?php echo $row['parts_price'] ?>" onkeyup = "javascript:this.value=Comma(this.value);">
                                    </div>

                                    <div class="form-group">
                                      <label>Location</label>
                                      <select class="form-control" name="parts_rack_location_id">
                                        <?php
                                        $sql  = "SELECT * FROM tbl_parts_rack_location WHERE client_id = '".$_SESSION['client_id']."' ORDER BY parts_rack_location_name";
                                        $h1    = mysqli_query($conn,$sql);
                                        while($row1 = mysqli_fetch_assoc($h1)) {
                                          if($row1['parts_rack_location_id']==$row['parts_rack_location_id']) {
                                        ?> 
                                        <option value="<?php echo $row1['parts_rack_location_id'] ?>" selected="selected"><?php echo $row1['parts_rack_location_name'] ?></option>
                                          <?php }else{ ?>
                                        <option value="<?php echo $row1['parts_rack_location_id'] ?>"><?php echo $row1['parts_rack_location_name'] ?></option>
                                        <?php }} ?>
                                      </select>
                                    </div>

                                  </div>
                                </div>
                                <!--end isi modal-->
                              </div>
                              <div class="modal-footer">
                                <input type="submit" class="btn btn-success mr-5 mb-5" value="Edit" /> <button type="button" class="btn btn-default mr-5 mb-5" data-dismiss="modal">Close</button>
                              </div>
                            </div><!-- /.modal-content -->
                          </div>
                        </div>
                      </form>
                      <!-- / .modal -->

                              <!-- .modal photo -->
                              <form ui-jp="parsley" enctype='multipart/form-data' method="POST" action="inventory-parts-photo-add.php">
                                <input type="hidden" name="parts_id" value="<?php echo $row['parts_id'] ?>">
                                <div id="<?php echo $row['parts_id'] ?>" class="modal fade" data-backdrop="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title">Upload Photo</h5>
                                      </div>
                                      <div class="modal-body text-center p-lg">
                                        <!--begin isi modal-->
                                        <div class="box">
                                          <br/><br/>
                                          <label><b>Upload your photo for parts: <?php echo $row['parts_name'] ?></b></label>
                                          <br/><br/>
                                          <input type="file" name="photo_file" id="photo_file" />
                                        </div>
                                        <br/><br/>
                                        <div class="box">
                                          <h5>Existing Photo</h5>
                                          <?php
                                          $sql_photo  = "SELECT * FROM tbl_parts_photo WHERE parts_id = '".$row['parts_id']."'";
                                          $h_photo    = mysqli_query($conn,$sql_photo);
                                          ?>
                                          <div class="row">
                                            <?php 
                                            while($row_photo = mysqli_fetch_assoc($h_photo)) { ?>
                                              <div class="col-xs-6 col-sm-4 col-md-3">
                                                <div class="box p-a-xs">
                                                  <a href="#"><img src="<?php echo $row_photo['parts_photo'] ?>" height="100"></a>
                                                  <div class="p-a-sm">
                                                    <div class="text-ellipsis"><a href="inventory-parts-photo-delete.php?del=29dvi59&ntf=8tgv6i59-<?php echo $row_photo["parts_photo_id"]?>-g!rocmst00856kmk6-349ffuaw" class="btn btn-sm view-danger">delete</a></div>
                                                  </div>
                                                </div>
                                              </div>
                                            <?php } ?>
                                          </div>
                                        </div>
                                        <!--end isi modal-->
                                      </div>
                                      <div class="modal-footer">
                                        <input type="submit" class="btn btn-success mr-5 mb-5" value="Upload" /> <button type="button" class="btn btn-default mr-5 mb-5" data-dismiss="modal">Close</button>
                                      </div>
                                    </div><!-- /.modal-content -->
                                  </div>
                                </div>
                              </form>
                              <!-- / .modal -->
                            </td>
                        </tr>
                        <?php } mysqli_free_result($rs_result); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Small Table -->

        <?php
        // COUNT TOTAL NUMBER OF ROWS IN TABLE
        if(@$_REQUEST['s']=='1091vdf8ame151') {           
          $sql = "SELECT count(parts_id) as jumData FROM tbl_parts a   WHERE a.client_id = '".$_SESSION['client_id']."' AND (parts_name LIKE '%$txt_search%' OR parts_number LIKE '%$txt_search%')";
        }elseif(@$_REQUEST['s']=='1') {           
          $sql = "SELECT count(parts_id) as jumData FROM tbl_parts a WHERE a.client_id = '".$_SESSION['client_id']."' ORDER by parts_rack_location_name";
        }else{            
          $sql = "SELECT count(parts_id) as jumData FROM tbl_parts a WHERE a.client_id = '".$_SESSION['client_id']."'";
        }          

        $hasil  = mysqli_query($conn,$sql);
        $data     = mysqli_fetch_assoc($hasil);
        $jumData = $data['jumData'];
        $jumPage = ceil($jumData/$dataPerPage);

        if ($noPage > 1) echo  "<a class=hal href='".$_SERVER['PHP_SELF']."?s=".@$_REQUEST['s']."&client_id=".@$_REQUEST['client_id']."&txt_search=".@$_REQUEST['txt_search']."&page=".($noPage-1)."'><i class=\"fa fa-angle-left\"></i></a>";

        for($page = 1; $page <= $jumPage; $page++)
        {
            if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage))
            {
                if ((@$showPage == 1) && ($page != 2))  echo "...";
                if ((@$showPage != ($jumPage - 1)) && ($page == $jumPage))  echo "...";
                if ($page == $noPage) echo " <b>".$page."</b> ";
                else echo " <a class=hal href='".$_SERVER['PHP_SELF']."?s=".@$_REQUEST['s']."&client_id=".@$_REQUEST['client_id']."&txt_search=".@$_REQUEST['txt_search']."&page=".$page."'>".$page."</a> ";
                @$showPage = $page;
            }
        }

        if ($noPage < $jumPage) echo "<a class=hal href='".$_SERVER['PHP_SELF']."?s=".@$_REQUEST['s']."&client_id=".@$_REQUEST['client_id']."&txt_search=".@$_REQUEST['txt_search']."&page=".($noPage+1)."'><i class=\"fa fa-angle-right\"></i></a>";
        mysqli_free_result($hasil); 
        ?>

    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>
