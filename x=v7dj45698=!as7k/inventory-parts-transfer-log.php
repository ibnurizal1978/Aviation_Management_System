<?php 
require_once 'header.php';
//require_once 'components.php';
?>

<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">

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
        $sql = "SELECT a.parts_id,a.parts_name, d.qty, a.parts_number, serial_number, parts_price, d.parts_rack_location_id FROM tbl_parts a INNER JOIN tbl_parts_rack_location b USING (parts_rack_location_id) INNER JOIN tbl_parts_warehouse c USING (parts_warehouse_id) INNER JOIN tbl_parts_location_stock d ON a.parts_id = d.parts_id  WHERE (parts_name LIKE '%$txt_search%' OR parts_number LIKE '%$txt_search%') AND a.client_id = '".$_SESSION['client_id']."' ORDER BY parts_name ASC LIMIT $offset, $dataPerPage";
      }elseif (@$_REQUEST['s']=='1'){
        $sql = "SELECT a.parts_id,a.parts_name, b.qty, a.parts_number, serial_number, date_format(b.created_date, '%d/%m/%Y') as created_date, c.full_name, b.from_parts_rack_location_id, b.to_parts_rack_location_id FROM tbl_parts a  INNER JOIN tbl_parts_location_stock_log b USING (parts_id) INNER JOIN tbl_user c ON b.user_id = c.user_id  WHERE a.parts_id = '".$ntf[1]."' AND a.client_id = '".$_SESSION['client_id']."' ORDER BY b.created_date LIMIT $offset, $dataPerPage";      
      }else{
        $sql = "SELECT a.parts_id,a.parts_name, b.qty, a.parts_number, serial_number, date_format(b.created_date, '%d/%m/%Y') as created_date, c.full_name, b.from_parts_rack_location_id, b.to_parts_rack_location_id FROM tbl_parts a  INNER JOIN tbl_parts_location_stock_log b USING (parts_id) INNER JOIN tbl_user c ON b.user_id = c.user_id  WHERE a.parts_id = '".$ntf[1]."' AND a.client_id = '".$_SESSION['client_id']."' ORDER BY b.created_date ASC LIMIT $offset, $dataPerPage";
      }
      $rs_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($rs_result)==0) {
      ?>
        <div class="alert alert-info ks-solid text-center" role="alert">Oops, no data found</div>
    <?php } ?>    
        <div class="block table-responsive">
            <div class="block-header block-header-default">
                <h3 class="block-title">Parts Movement Log <!--| <a href="inventory-adj-log.php">Parts Adjustment Log</a> --></h3>
            </div>
            <div class="block-content">
                <table class="table table-sm table-vcenter">
                    <thead>
                        <tr>
                            <th width="30%">Part Name</th>
                            <th width="15%">P/N</th>
                            <!--<th width="15%">S/N</th>-->
                            <th width="10%">Qty</th>
                            <th width="10%">From</th>
                            <th width="10%">To</th>
                            <th width="10%">Date</th>
                            <th width="10%">User</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                        <tr>
                            <td><?php echo $row['parts_name'] ?></td>
                            <td><?php echo $row['parts_number'] ?></td>
                            <!--<td><?php echo $row['serial_number'] ?></td>-->
                            <td><?php echo $row['qty'] ?></td>
                            <td>
                              <?php 
                              $sql1 = "SELECT parts_warehouse_name, parts_rack_location_name FROM tbl_parts_rack_location a INNER JOIN tbl_parts_warehouse b USING (parts_warehouse_id) WHERE parts_rack_location_id = '".$row['from_parts_rack_location_id']."' AND a.client_id = '".$_SESSION['client_id']."' LIMIT 1";
                              $h1   = mysqli_query($conn, $sql1);
                              $row1 = mysqli_fetch_assoc($h1);
                              echo $row1['parts_warehouse_name'].'<br/>(rack '.$row1['parts_rack_location_name'].')';
                              ?>
                            </td>
                            <td>
                              <?php 
                              $sql1 = "SELECT parts_warehouse_name, parts_rack_location_name FROM tbl_parts_rack_location a INNER JOIN tbl_parts_warehouse b USING (parts_warehouse_id) WHERE parts_rack_location_id = '".$row['to_parts_rack_location_id']."' AND a.client_id = '".$_SESSION['client_id']."' LIMIT 1";
                              $h1   = mysqli_query($conn, $sql1);
                              $row1 = mysqli_fetch_assoc($h1);
                              echo $row1['parts_warehouse_name'].'<br/>(rack '.$row1['parts_rack_location_name'].')';
                              ?>
                            </td>
                            <td><?php echo $row['created_date'] ?></td>
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
          $sql = "SELECT count(parts_id) as jumData FROM tbl_parts a   WHERE a.client_id = '".$_SESSION['client_id']."' AND (parts_name LIKE '%$txt_search%' OR parts_number LIKE '%$txt_search%')";
        }elseif(@$_REQUEST['s']=='1') {           
          $sql = "SELECT count(parts_id) as jumData FROM tbl_parts a WHERE a.client_id = '".$_SESSION['client_id']."' ORDER by parts_rack_location_name";
        }else{            
          $sql = "SELECT count(id) as jumData FROM tbl_parts a  INNER JOIN tbl_parts_location_stock_log b USING (parts_id) INNER JOIN tbl_user c ON b.user_id = c.user_id  WHERE a.parts_id = '".$ntf[1]."' AND a.client_id = '".$_SESSION['client_id']."'";
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
