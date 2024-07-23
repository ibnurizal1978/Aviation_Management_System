<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
require_once 'components.php';

if(@$_REQUEST['q']=='174yurhwebfn') {
    $user_id    = $ntf[1];
}else{
    $user_id    = $_SESSION['user_id'];
}

$sql    = "SELECT department_id,full_name,user_birth_date,user_home_address,user_email_address,user_phone,user_photo,user_marital_status FROM tbl_user WHERE user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h      = mysqli_query($conn,$sql);
$row    = mysqli_fetch_assoc($h);
?>
<!--<body onLoad="javascript:window.print();">-->
<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">   
        <div class="block table-responsive">
            <div class="block-content">
                <table width="100%" border="0">
                    <tr>
                        <td colspan="2"><h3><?php echo $row['full_name'] ?></h3></td>
                        <td width="20%" rowspan="5" align="right"><img src="../uploads/user/<?php echo $row['user_photo'] ?>" width="150"></td>
                    </tr>
                    <tr>
                        <td><b>Marital Status</b></td><td><?php echo $row['user_marital_status'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Mobile</b></td><td><?php echo $row['user_phone'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Address</b></td><td><?php echo $row['user_home_address'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Email</b></td><td><?php echo $row['user_email_address'] ?></td>
                    </tr>
                </table>

                <br/><br/>
                <b>Educational Background</b>
                <table class="table table-sm table-vcenter">
                    <tbody>
                        <?php 
                        $sql2 = "SELECT training_id,year_from,year_to,description FROM tbl_user_cv_training WHERE user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' ORDER BY year_from DESC";
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
                        </tr>
                        <?php } mysqli_free_result($h2); ?>
                    </tbody>
                </table>

                <br/><br/>
                <b>Work Experiences</b>
                <table class="table table-sm table-vcenter">
                    <tbody>
                        <?php 
                        $sql2 = "SELECT work_experiences_id,year_from,year_to,description FROM tbl_user_cv_work_experiences  WHERE user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' ORDER BY year_from DESC";
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
                        </tr>
                        <?php } mysqli_free_result($h2); ?>
                    </tbody>
                </table>

                <br/><br/>
                <?php if($row['department_id']==1) {
                    echo '<h4>Additional Information</h4>';
                    $sql3    = "SELECT amel_no FROM tbl_user_otr WHERE user_id = '".$user_id."' LIMIT 1";
                    $h3      = mysqli_query($conn,$sql3);
                    $row3    = mysqli_fetch_assoc($h3);
                    echo 'Aircraft Maintenance Engineer License No.: '.$row3['amel_no'];
                    echo '<br/><br/>';
                    $sql4 = "SELECT additional_id,year,title,description FROM tbl_user_cv_additional  WHERE user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."' ORDER BY year DESC";
                    $h4   = mysqli_query($conn,$sql4);
                    ?>
                    <table class="table table-sm table-vcenter">
                    <?php while ($row4 = mysqli_fetch_assoc($h4)) { ?>
                        <tr>
                            <td><?php if($row4['year']<>'0000') { 
                            echo $row4['year']; }?></td>
                            <td>
                            <?php echo $row4['title'].' '.$row4['description'].'<br/>';
                            ?></td>
                        </tr>
                    
                    <?php } ?>
                </table>
            <?php } ?>
                <br/><br/><br/>
                Yours Sincerely,
                <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
                <?php echo $row['full_name'] ?>
                <br/><br/>
                     

            </div>
        </div>
        <!-- END Small Table -->
        <a class="btn btn-success mr-5 mb-5" href="javascript:window.print();"><i class="si si-printer mr-5"></i>Print</a>
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>