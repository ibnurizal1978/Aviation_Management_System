<?php 
require_once 'header.php';
//require_once 'components.php';
?>

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
            $sql = "SELECT km_content_id,km_name,date_format(created_date, '%d/%m/%Y') as created_date FROM tbl_km_content WHERE client_id = '".$_SESSION['client_id']."' AND (km_name LIKE '%$txt_search%' OR km_files_tag LIKE '%$txt_search%') LIMIT $offset, $dataPerPage";     
        }elseif (@$_REQUEST['s']=='k'){           
            $sql = "SELECT km_content_id,km_name,date_format(created_date, '%d/%m/%Y') as created_date FROM tbl_km_content WHERE client_id = '".$_SESSION['client_id']."' AND km_category_id = '".$_POST['category_id']."' LIMIT $offset, $dataPerPage";
        }else{
            $sql = "SELECT * FROM tbl_document_category WHERE client_id = '".$_SESSION['client_id']."' ORDER BY document_category_name LIMIT $offset, $dataPerPage";
        }
      $rs_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($rs_result)==0) {
      ?>
        <p class="text-center text-danger" role="alert">No data at this moment :(</p>
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
                        <h3 class="block-title">Document Category</h3>
                    </div>                    
                    <div class="js-task-list">
                        <!-- Task -->
                        <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                        <div class="js-task block block-rounded mb-5 animated fadeIn" data-task-id="9" data-task-completed="false" data-task-starred="false">
                            <table class="table table-borderless table-vcenter mb-0">
                                <tr>
                                    <td class="js-task-content font-w600">
                                        <?php echo $row['document_category_name'] ?>
                                    </td>
                                    <td class="text-right" style="width: 100px;">
                                        <a class="btn btn-sm btn-alt-success" href="document-category-detail.php?act=29dvi59&ntf=29dvi59-<?php echo $row["document_category_id"]?>-94dfvj!sdf-349ffuaw">
                                            <i class="si si-pencil"></i>
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
          $sql = "SELECT count(km_content_id) as jumData FROM tbl_km_content WHERE client_id = '".$_SESSION['client_id']."' AND (km_name LIKE '%$txt_search%' OR km_files_tag LIKE '%$txt_search%')";
        }elseif(@$_REQUEST['s']=='k') {           
          $sql = "SELECT count(km_content_id) as jumData FROM tbl_km_content WHERE client_id = '".$_SESSION['client_id']."' AND km_category_id = '".$_POST['category_id']."'";
        }else{            
          $sql = "SELECT count(document_category_id) as jumData FROM tbl_document_category WHERE client_id = '".$_SESSION['client_id']."'";
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