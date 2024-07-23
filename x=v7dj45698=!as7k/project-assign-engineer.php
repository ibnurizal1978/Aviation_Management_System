<?php
require_once 'header.php';
require_once "components.php";
?>

<div class="content">
  <div class="container-fluid">
    <!--filter-->
    <div class="row">
      <div class="col-md-12">

        <div class="card">
          <div class="card-header card-header-tabs card-header-info">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" href="#profile" data-toggle="tab">
                      <i class="material-icons">search</i> Search by Name
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#messages" data-toggle="tab">
                      <i class="material-icons">sort</i> Sort
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane active" id="profile">
                <form class="navbar-form">
                  <div class="input-group no-border">
                    <input type="hidden" name="s" value="1091vdf8ame151">
                    <input type="text" value="" class="form-control" placeholder="Search..." name="txt_search">
                    <button type="submit" class="btn btn-white btn-round btn-just-icon">
                      <i class="material-icons">search</i>
                      <div class="ripple-container"></div>
                    </button>
                  </div>
                </form>
              </div>
              <div class="tab-pane" id="messages">
                <ul class="nav navbar-nav">
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sort By <span class="glyphicon glyphicon-user pull-right"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="project.php?s=1">By Name</a></li>
                      <li><a href="project.php?s=2">By Date</a></li>
                    </ul>
                  </li>
                </ul>                     
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title ">Assign Engineer</h4>
            <p class="card-category"> Choose project to assign your team</p>
          </div>
          <div class="card-body">

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


           <div class="card-body table-responsive">
                <table class="table">
                  <tbody>
                    <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                    <tr>
                      <td>
                        <?php 
                        echo $row['project_master_name'].'<br/>';
                        echo 'Assigned Engineer:<br/>';
                        $sql2 = "SELECT full_name FROM tbl_user a INNER JOIN tbl_project_engineer_team b ON a.user_id = b.engineer_user_id WHERE project_master_id = '".$row['project_master_id']."'";
                        $h2   = mysqli_query($conn,$sql2);
                        while($row2 = mysqli_fetch_assoc($h2)) {
                          echo '<span style="padding:3px" class="btn-danger">&nbsp;'.$row2['full_name'].'&nbsp;</span> ';
                        }
                         ?>
                        </td>
                      <td align="right"><span class="pull-right"><a class="btn-default" href="project-assign-engineer-new.php?act=29dvi59&ntf=29dvi59-<?php echo $row["project_master_id"]?>-94dfvj!sdf-349ffuaw" ><i class="material-icons">keyboard_arrow_right</i></a></span></td>
                    </tr>
                    <?php }; mysqli_free_result($rs_result); ?>
                  </tbody>
                </table>
            </div>


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

<div class="btn-group">
  <a href="project-new.php" class="btn btn-danger btn-round btn-fab" id="main">
    <i class="material-icons">edit</i>
  </a>
</div>