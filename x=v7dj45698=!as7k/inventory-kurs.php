<?php 
require_once 'header.php';
//require_once 'components.php';
?>

<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">

      <?php if(@$ntf[0]=="r827ao") {?>
        <div class="alert alert-danger ks-solid text-center" role="alert">Please fill all forms</div>
      <?php } ?> 
      <?php if(@$ntf[0]=="dpk739a") {?>
        <div class="alert alert-danger ks-solid text-center" role="alert">Duplicate parts name</div>
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
        $sql = "SELECT parts_rack_location_id,parts_id,parts_name,parts_treshold,parts_code,parts_rack_location_name,parts_warehouse_name FROM tbl_parts a INNER JOIN tbl_parts_rack_location b USING (parts_rack_location_id) INNER JOIN tbl_parts_warehouse c USING (parts_warehouse_id) WHERE (parts_name LIKE '%$txt_search%' OR parts_code LIKE '%$txt_search%') AND a.client_id = '".$_SESSION['client_id']."' ORDER BY parts_name ASC LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='1'){
        $sql = "SELECT parts_rack_location_id,parts_id,parts_name,parts_treshold,parts_code,parts_rack_location_name,parts_warehouse_name FROM tbl_parts a INNER JOIN tbl_parts_rack_location b USING (parts_rack_location_id) INNER JOIN tbl_parts_warehouse c USING (parts_warehouse_id) WHERE a.client_id = '".$_SESSION['client_id']."' ORDER by parts_name LIMIT $offset, $dataPerPage";      
      }else{
        $sql = "SELECT *, date_format(created_date, '%d/%m/%Y') as created_date FROM tbl_parts_kurs INNER JOIN tbl_user b USING (user_id) ORDER BY parts_kurs_id DESC LIMIT $offset, $dataPerPage";
      }
      $rs_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($rs_result)==0) {
      ?>
        <div class="alert alert-info ks-solid text-center" role="alert">Oops, no data found</div>
    <?php } ?>    
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Kurs - Green font is active kurs</h3>
            </div>
            <div class="block-content">
                <table class="table table-sm table-vcenter">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                        <tr>
                            <td><?php echo $row['created_date'] ?></td>
                            <td>
                              <?php 
                              if($row['active_status']== 1) {
                                echo '<span class=text-success>Rp. '.number_format($row['parts_kurs_amount'],0,",",".").'</span>';
                              }else{
                                echo '<del>Rp. '.number_format($row['parts_kurs_amount'],0,",",".").'</del>';
                              }  ?></td>
                            <td><?php echo $row['full_name'] ?></td>
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
          $sql = "SELECT count(parts_id) as jumData FROM tbl_parts a INNER JOIN tbl_parts_rack_location b USING (parts_rack_location_id) INNER JOIN tbl_parts_warehouse c USING (parts_warehouse_id)  WHERE a.client_id = '".$_SESSION['client_id']."' AND (parts_rack_location_name LIKE '%$txt_search%')";
        }elseif(@$_REQUEST['s']=='1') {           
          $sql = "SELECT count(parts_id) as jumData FROM tbl_parts a INNER JOIN tbl_parts_rack_location b USING (parts_rack_location_id) INNER JOIN tbl_parts_warehouse c USING (parts_warehouse_id) WHERE a.client_id = '".$_SESSION['client_id']."' ORDER by parts_rack_location_name";
        }else{            
          $sql = "SELECT count(parts_kurs_id) as jumData FROM tbl_parts_kurs a INNER JOIN tbl_user b USING (user_id)";
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
