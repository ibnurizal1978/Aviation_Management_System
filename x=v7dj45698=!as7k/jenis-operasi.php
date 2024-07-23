<?php
require_once 'header.php';
require_once "components.php";
?>

<main id="main-container">
  <div class="content">


  <!--table-->   
  <div class="block table-responsive">
      <div class="block-header block-header-default">
        <h3 class="block-title">Create New Operation Price</h3>
      </div>
      <div class="block-content">

        <?php if(@$_GET['act']=='79dvi59g') { ?>
          <div class="alert alert-danger alert-dismissable" role="alert">
              <p class="mb-0">Please fill all form!</p>
          </div>
        <?php } ?>   

        <?php if(@$_GET['act']=='49856twnaq4') { ?>
          <div class="alert alert-success alert-dismissable" role="alert">
              <p class="mb-0">Success</p>
          </div>
        <?php } ?> 

        <?php if(@$_GET['act']=='aasfa') { ?>
          <div class="alert alert-danger alert-dismissable" role="alert">
              <p class="mb-0">Duplicate data</p>
          </div>
        <?php } ?>

        <div class="card-body">
          <form action="jenis-operasi-add.php" method="POST">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                    <label class="bmd-label-floating">Operation Type</label>
                    <select required name="jenis_operasi_id" class="form-control">
                        <option value=""> -- Choose -- </option>
                        <?php
                        $sql1  = "SELECT * FROM tbl_jenis_operasi WHERE client_id = '".$_SESSION['client_id']."'";
                        $h1    = mysqli_query($conn,$sql1);
                        while($row1 = mysqli_fetch_assoc($h1)) {
                        ?>
                        <option value="<?php echo $row1['jenis_operasi_id'] ?>"><?php echo $row1['jenis_operasi_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <label class="bmd-label-floating">IATA CODE</label>
                    <select required name="master_iata_id" class="form-control">
                        <option value=""> -- Choose -- </option>
                        <?php
                        $sql1  = "SELECT master_iata_id,iata_code,icao_code, iata_province FROM tbl_master_iata ORDER BY iata_province";
                        $h1    = mysqli_query($conn,$sql1);
                        while($row1 = mysqli_fetch_assoc($h1)) {
                        ?>
                        <option value="<?php echo $row1['master_iata_id'] ?>"><?php echo $row1['iata_code'].' / '.$row1['icao_code']." (".$row1['iata_province'].")" ?></option>
                        <?php } ?>
                    </select>
                </div>
              </div>              
            </div>                        
            </div>
            <div><input type="submit" class="btn btn-success mr-5 mb-5" value="Save" /></div>
          </form>
        </div>
      </div>
    </div>


    <div class="container-fluid">          
      <!--certificate-->
      <div class="block table-responsive">
        <div class="block-header block-header-default">
            <h3 class="block-title">Operation Type</h3>
        </div>
        <div class="block-content">

            <!--begin list data-->
            <?php
            @$page = @$_REQUEST['page'];
            $dataPerPage = 30;
            if(isset($_GET['page']))
            {
                $noPage = $_GET['page'];
            }
            else $noPage = 1;
            @$offset = ($noPage - 1) * $dataPerPage;
            //for total count data
            if(@$_REQUEST['s']=='1091vdf8ame151') {
              $txt_search   = input_data(filter_var($_REQUEST['txt_search'],FILTER_SANITIZE_STRING));
              $sql = "SELECT * FROM tbl_jenis_operasi WHERE (jenis_operasi_name LIKE '%$txt_search%' OR iata_code LIKE '%$txt_search%' OR icao_code LIKE '%$txt_search%') AND client_id = '".$_SESSION['client_id']."' LIMIT $offset, $dataPerPage";
            }elseif (@$_REQUEST['s']=='1'){
              $sql = "SELECT * FROM tbl_jenis_operasi  WHERE client_id = '".$_SESSION['client_id']."' ORDER by jenis_operasi_name LIMIT $offset, $dataPerPage";      
            }elseif (@$_REQUEST['s']=='2'){
              $sql = "SELECT * FROM tbl_jenis_operasi  WHERE client_id = '".$_SESSION['client_id']."' ORDER by iata_code LIMIT $offset, $dataPerPage";
            }else{
              $sql = "SELECT * FROM tbl_jenis_operasi_iata a INNER JOIN tbl_master_iata b USING (master_iata_id) INNER JOIN tbl_jenis_operasi c ON a.jenis_operasi_id = c.jenis_operasi_id WHERE a.client_id = '".$_SESSION['client_id']."' LIMIT $offset, $dataPerPage";
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
                        <th>IATA / ICAO</th>
                        <th>Operation Type</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                        <tr>
                            <td><?php echo $row['iata_code']." / ".$row['icao_code'].' ('.$row['iata_province'].')' ?></td>
                            <td><?php echo $row['jenis_operasi_name'] ?></td>
                            <td><span><a href="jenis-operasi-detail.php?act=29dvi59&ntf=29dvi59-<?php echo $row["id"]?>-94dfvj!sdf-349ffuaw" >View</a></span></td>
                  </tr>
                  <?php }; mysqli_free_result($rs_result); ?>
                </tbody>
              </table>

              <?php
              // COUNT TOTAL NUMBER OF ROWS IN TABLE
              if(@$_REQUEST['s']=='1091vdf8ame151') {           
                $sql = "SELECT count(jenis_operasi_id) as jumData FROM tbl_jenis_operasi WHERE client_id = '".$_SESSION['client_id']."' AND(jenis_operasi_name LIKE '%$txt_search%' OR iata_code LIKE '%$txt_search%' OR icao_code LIKE '%$txt_search%')";
              }elseif(@$_REQUEST['s']=='1') {           
                $sql = "SELECT count(jenis_operasi_id) as jumData FROM tbl_jenis_operasi WHERE client_id = '".$_SESSION['client_id']."' ORDER by jenis_operasi_name";
              }elseif(@$_REQUEST['s']=='2') {           
                $sql = "SELECT count(jenis_operasi_id) as jumData FROM tbl_jenis_operasi WHERE client_id = '".$_SESSION['client_id']."' ORDER by iata_code";
              }else{            
                $sql = "SELECT count(jenis_operasi_id) as jumData FROM tbl_jenis_operasi WHERE client_id = '".$_SESSION['client_id']."'";
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