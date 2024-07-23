<?php 
require_once 'header.php';
//require_once 'components.php';
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
                    <form action="document.php" method="post">
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
                    <form action="document.php" method="post">
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
            $sql = "SELECT document_content_id,document_name,date_format(created_date, '%d/%m/%Y') as created_date FROM tbl_document_content WHERE client_id = '".$_SESSION['client_id']."' AND (document_name LIKE '%$txt_search%' OR document_files_tag LIKE '%$txt_search%') LIMIT $offset, $dataPerPage";     
        }elseif (@$_REQUEST['s']=='k'){           
            $sql = "SELECT document_content_id,document_name,date_format(created_date, '%d/%m/%Y') as created_date FROM tbl_document_content WHERE client_id = '".$_SESSION['client_id']."' AND document_category_id = '".$_POST['document_category_id']."' LIMIT $offset, $dataPerPage";
        }else{
            $sql = "SELECT document_content_id,document_name,date_format(created_date, '%d/%m/%Y') as created_date FROM tbl_document_content WHERE client_id = '".$_SESSION['client_id']."' ORDER BY document_name LIMIT $offset, $dataPerPage";
        }
      $rs_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($rs_result)==0) {
      ?>
        <p class="text-center text-danger" role="alert">No document at this moment :(</p>
      <?php  
      //exit(); 
      }
      ?>

        <!-- Page Content -->
        <div class="content">
            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <!-- Tasks List -->
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Documents</h3>
                        <div class="block-options">
                            <div class="block-options-item">
                                <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                                    <i class="fa fa-filter"></i>
                                </button>
                            </div>
                        </div>
                    </div>                    
                    <div class="js-task-list">
                        <!-- Task -->
                        <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                        <div class="js-task block block-rounded mb-5 animated fadeIn" data-task-id="9" data-task-completed="false" data-task-starred="false">
                            <table class="table table-borderless table-vcenter mb-0">
                                <tr>
                                    <td class="js-task-content font-w600">
                                        <?php echo $row['document_name'] ?>
                                    </td>
                                    <td class="text-right" style="width: 100px;">
                                        <a class="js-task-star btn btn-sm btn-alt-success" href="document-detail.php?act=29dvi59&ntf=29dvi59-<?php echo $row["document_content_id"]?>-94dfvj!sdf-349ffuaw">
                                            <i class="si si-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- END Task -->
                        <?php } mysqli_free_result($rs_result); ?>
                    </div>
                    <!-- END Tasks List -->
                </div>
            </div>
        </div>
        <!-- END Page Content -->

        <?php
        // COUNT TOTAL NUMBER OF ROWS IN TABLE
        if(@$_REQUEST['s']=='1091vdf8ame151') {           
          $sql = "SELECT count(document_content_id) as jumData FROM tbl_document_content WHERE client_id = '".$_SESSION['client_id']."' AND (document_name LIKE '%$txt_search%' OR document_files_tag LIKE '%$txt_search%')";
        }elseif(@$_REQUEST['s']=='k') {           
          $sql = "SELECT count(document_content_id) as jumData FROM tbl_document_content WHERE client_id = '".$_SESSION['client_id']."' AND document_category_id = '".$_POST['category_id']."'";
        }else{            
          $sql = "SELECT count(document_content_id) as jumData FROM tbl_document_content WHERE client_id = '".$_SESSION['client_id']."'";
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

<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Send</button> <button type="button" class="btn btn-alt-secondary mr-5 mb-5" data-dismiss="modal">Cancel</button>');  
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
      url:"document-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Send</button> <button type="button" class="btn btn-alt-secondary mr-5 mb-5" data-dismiss="modal">Cancel</button>');  
      }  
    });  
  });

});
</script>

<script src="../assets/js/plugins/select2/select2.full.min.js"></script>
        <script src="../assets/js/pages/be_forms_plugins.js"></script>
        <script>
            jQuery(function () {
                // Init page helpers (BS Datepicker + BS Colorpicker + BS Maxlength + Select2 + Masked Input + Range Sliders + Tags Inputs plugins)
                Codebase.helpers(['select2']);
            });
        </script>

