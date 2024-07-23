<?php
session_start();
require_once "../config.php";
require_once "../check-session.php";
require_once "components.php";

$sql  = "SELECT *,CURDATE(), DATEDIFF(safety_finding_target_date, CURDATE()) AS selisih,safety_finding_id,safety_finding_no,safety_finding_status,date_format(safety_finding_target_date, '%d/%m/%Y') AS safety_finding_target_date,date_format(created_date, '%d/%m/%Y') AS created_date FROM tbl_safety_finding WHERE client_id = '".$_SESSION['client_id']."' AND safety_finding_id = '".$ntf[1]."' LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
?>
<style type="text/css">
body {
  background: #fff;
}  
</style>
<!-- Main Container -->
<main id="main-container">
  <!-- Page Content -->
  <div class="content">
    <table class="table table-sm table-vcenter">
      <tr>
        <td><img src="<?php echo $base_url ?>assets/img/logo.jpg"></td>
        <td class="text-center">
          <h1><?php echo strtoupper($_SESSION['client_name']) ?></h1>
          <b>AUDIT/SURVEILLANCE FINDING</b>
        </td>
      </tr>
    </table>
    <!--table-->   
    <div class="block table-responsive">
      <div class="block-content">
        <div class="card-body">
          <table class="table table-sm table-vcenter table-bordered">
            <tr>
              <td colspan="2" style="background: #E6E6E6"><h5>PART I - FINDING SECTION</h5></td>
            </tr>
            <tr>
              <td width="50%"><b>COMPANY:</b> <?php echo $_SESSION['client_name'] ?></td>
              <td><b>FINDING NO:</b> <?php echo $row['safety_finding_no'] ?></td>
            </tr>
            <tr>
              <td width="50%"><b>LOCATION:</b> <?php echo $row['safety_finding_location'] ?></td>
              <td><b>AREA:</b> <?php echo $row['safety_finding_area'] ?></td>
            </tr>
            <tr>
              <td width="50%"><b>REFERENCE:</b> <?php echo $row['safety_finding_reference'] ?></td>
              <td><b>TYPE OF FINDING:</b> <?php echo $row['safety_finding_type'] ?></td>
            </tr>
            <tr>
              <td colspan="3"><b>FINDING:</b><br/><br/><?php echo $row['safety_finding_title'] ?><br/><br/><br/><br/><br/><br/></td>
            </tr>
            <tr>
              <td colspan="3"><b>CORRECTIVE ACTION PLAN:</b><br/><br/><?php echo $row['safety_finding_description'] ?><br/><br/><br/><br/><br/><br/></td>
            </tr>
          </table>
          <table class="table table-sm table-vcenter table-bordered">
            <tr>
              <td><b>Auditor/Inspector:</b> <?php echo $_SESSION['full_name'] ?></td>
              <td><b>Date:</b> <?php echo $row['created_date'] ?></td>
              <td rowspan="3" align="center"><b>Target Completion Date:</b><br/>                                 
                <?php
                if($row['selisih']<3) {
                    echo '<span class="text-danger">'.$row['safety_finding_target_date'].'</span>';
                }else{
                    echo '<span class="text-success">'.$row['safety_finding_target_date'].'</span>';
                }
                echo '<br/><b>Status</b>&nbsp;';
                if($row['safety_finding_status']=='OPEN') {
                  echo '<span class="badge badge-danger">OPEN</span>';
                }else{
                  echo '<span class="badge badge-success">CLOSED</span>';
                }                
                ?>
              </td>
            </tr>
            <tr>
              <td><b>Auditee Operation/Maintenance:</b> <?php echo $row['safety_finding_target_full_name'] ?></td>
              <td><b>Date:</b></td>
            </tr>
            <tr>
              <td><b>Audit Manager/Team Leader:</b> <?php echo $row['safety_finding_team_leader_full_name'] ?></td>
              <td><b>Date:</b> <?php echo $row['created_date'] ?></td>
            </tr>
          </table>

          <?php if($row['safety_finding_status']=='OPEN') { ?>
          <form id="form_simpan" enctype="multipart/form-data">
            <input type="hidden" name="safety_finding_id" value="<?php echo $row['safety_finding_id'] ?>">
            <table class="table table-sm table-vcenter table-bordered">
              <tr>
                <td style="background-color: #E6E6E6"><b>PART II - CORRECTIVE SECTION</b></td>
              </tr>
              <tr>
                <td>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="bmd-label-floating"><b>Corrective Action</b><span class="text-danger">*</span></label>
                        <textarea class="form-control" name="safety_finding_response_corrective_action" rows="3" autocomplete="off"></textarea>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="col-12"><b>Choose Proof File (Format JPG, BMP, GIF, PNG, PDF, XLS, DOC)</b></label>
                        <div class="col-12">
                          <input type="file" name="upload_file" />
                        </div>
                      </div>
                    </div>
                  </div>                                  
                  <div id="results"></div><div id="button"></div>
                  <div class="clearfix"></div>
                </td>
              </tr>
            </table>
          </form>
          <?php 
          }else{
          $sql2 = "SELECT *,date_format(created_date, '%d/%m/%Y') AS created_date FROM tbl_safety_finding_response WHERE safety_finding_id = '".$row['safety_finding_id']."' LIMIT 1";
          $h2   = mysqli_query($conn,$sql2);
          $row2 = mysqli_fetch_assoc($h2);
          ?>
          <table class="table table-sm table-vcenter table-bordered">
            <tr>
              <td><h5>PART II - CORRECTIVE SECTION</h5></td>
            </tr>
            <tr>
              <td><b>Corrective Action:</b><br/><br/><br/> <?php echo $row2['safety_finding_response_corrective_action'] ?><br/><br/><br/><br/><br/><br/></td>
            </tr>
            <tr>
              <td><b>File:</b> <a href="<?php echo $base_url ?>uploads/safety/<?php echo $row2['safety_finding_response_file'] ?>" target="_blank"><i class="si si-paper-clip"></i></a></td>
            </tr>
            <tr>
              <td>Auditee Operation/Maintenance: <?php echo $row2['created_full_name'] ?><br/>
                Date: <?php echo $row2['created_date'] ?>
              </td>
            </tr>
            <tr>
              <td>Verified by Auditor: <?php echo $row['safety_finding_team_leader_full_name'] ?><br/>
                Date <?php echo $row2['created_date'] ?>
              </td>
            </tr>
            <tr>
              <td>
                <?php if($_SESSION['department_id']==7) { ?>
                <form id="form_simpan2">
                  <input type="hidden" name="safety_finding_id" value="<?php echo $row['safety_finding_id'] ?>">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="col-12"><b>Choose Proof File (Format JPG, BMP, GIF, PNG, PDF, XLS, DOC)</b></label>
                        <select class="form-control" required name="safety_finding_status">
                          <option value=""> -- Choose -- </option>
                          <option value="OPEN">STILL OPEN</option>
                          <option value="CLOSED">CLOSED</option>
                        </select>
                      </div>
                    </div>
                  </div>                                  
                  <div id="results2"></div><div id="button2"></div>
                  <div class="clearfix"></div>
                </form>
                <?php } ?>
              </td>
            </tr>                
          </table>
          <?php } ?>          
        </div>
      </div>
    </div>
  <!-- END Small Table -->

  <!-- END Page Content -->
  </div>
</main>
<?php require_once 'footer.php' ?>

