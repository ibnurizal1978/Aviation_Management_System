<?php
require_once 'header.php';
require_once "components.php";
?>

<main id="main-container">
  <div class="content">
    <div class="container-fluid">
      
      <!--certificate-->
      <div class="block table-responsive">
        <div class="block-header block-header-default">
            <h3 class="block-title">Projects</h3>
        </div>
        <div class="block-content">

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
              $sql = "SELECT * FROM tbl_project_master WHERE (project_master_name LIKE '%$txt_search%' OR start_date LIKE '%$txt_search%' OR end_date LIKE '%$txt_search%') AND client_id = '".$_SESSION['client_id']."' LIMIT $offset, $dataPerPage";
            }elseif (@$_REQUEST['s']=='1'){
              $sql = "SELECT * FROM tbl_project_master  WHERE client_id = '".$_SESSION['client_id']."' ORDER by project_master_name LIMIT $offset, $dataPerPage";      
            }elseif (@$_REQUEST['s']=='2'){
              $sql = "SELECT * FROM tbl_project_master  WHERE client_id = '".$_SESSION['client_id']."' ORDER by start_date LIMIT $offset, $dataPerPage";
            }else{
              $sql = "SELECT * FROM tbl_project_master WHERE client_id = '".$_SESSION['client_id']."' LIMIT $offset, $dataPerPage";
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
                <tbody>
                  <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                  <tr>
                    <td>
                      <?php echo $row['project_master_name'] ?>
                      </td>
                    <td align="right"><span class="pull-right"><a class="btn btn-success mr-5 mb-5" href="project-detail.php?act=29dvi59&ntf=29dvi59-<?php echo $row["project_master_id"]?>-94dfvj!sdf-349ffuaw" >View</a></span></td>
                  </tr>
                  <?php }; mysqli_free_result($rs_result); ?>
                </tbody>
              </table>

              <?php
              // COUNT TOTAL NUMBER OF ROWS IN TABLE
              if(@$_REQUEST['s']=='1091vdf8ame151') {           
                $sql = "SELECT count(project_master_id) as jumData FROM tbl_project_master WHERE client_id = '".$_SESSION['client_id']."' AND (project_master_name LIKE '%$txt_search%' OR start_date LIKE '%$txt_search%' OR end_date LIKE '%$txt_search%')";
              }elseif(@$_REQUEST['s']=='1') {           
                $sql = "SELECT count(project_master_id) as jumData FROM tbl_project_master WHERE client_id = '".$_SESSION['client_id']."' ORDER by project_master_name";
              }elseif(@$_REQUEST['s']=='2') {           
                $sql = "SELECT count(project_master_id) as jumData FROM tbl_project_master WHERE client_id = '".$_SESSION['client_id']."' ORDER by start_date";
              }else{            
                $sql = "SELECT count(project_master_id) as jumData FROM tbl_project_master WHERE client_id = '".$_SESSION['client_id']."'";
              }          

              $hasil  = mysqli_query($conn,$sql);
              $data     = mysqli_fetch_assoc($hasil);
              $jumData = $data['jumData'];
              $jumPage = ceil($jumData/$dataPerPage);

              if ($noPage > 1) echo  "<a class=hal href='".$_SERVER['PHP_SELF']."?s=".@$_REQUEST['s']."&client_id=".@$_REQUEST['client_id']."&txt_search=".@$_REQUEST['txt_search']."&page=".($noPage-1)."'><i class=\"material-icons\">navigate_before</i></a>";

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

              if ($noPage < $jumPage) echo "<a class=hal href='".$_SERVER['PHP_SELF']."?s=".@$_REQUEST['s']."&client_id=".@$_REQUEST['client_id']."&txt_search=".@$_REQUEST['txt_search']."&page=".($noPage+1)."'><i class=\"material-icons\">navigate_next</i></a>";
              mysqli_free_result($hasil); 
              ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php require_once 'footer.php' ?>

<style type="text/css">
.btn-group-sm .btn-fab{
  border-radius: 80;
  position: fixed !important;
  right: 29px;
}
.btn-group .btn-fab{
  position: fixed !important;
  right: 20px;
}
#main{
  bottom: 20px;
} 
</style>