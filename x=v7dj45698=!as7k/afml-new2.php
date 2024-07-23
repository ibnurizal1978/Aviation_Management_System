<?php
session_start();
require_once '../check-session.php';
require_once '../config.php';
?>
<title>Create New AFML</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<h3>CREATE NEW AFML</h3>
<a href="afml.php">Back to AFML List</a>
<br/><br/>

<form action="afml-add-excel.php" method="POST" id="form" enctype="multipart/form-data">
  <table>
    <tr>
      <td>DATE</td>
      <td><input type="date" id="afml_date" name="afml_date" autocomplete="off" /></td>
    </tr>
    <tr>
      <td>A/C REG</td>
      <td>
        <select required name="aircraft_reg_code">
          <option value=""> -- None -- </option>
          <?php
          $sql1  = "SELECT aircraft_reg_code FROM tbl_aircraft_master WHERE client_id = '".$_SESSION['client_id']."'";
          echo $sql1;
          $h1    = mysqli_query($conn,$sql1);
          while($row1 = mysqli_fetch_assoc($h1)) {
          ?>
          <option value="<?php echo $row1['aircraft_reg_code'] ?>"><?php echo $row1['aircraft_reg_code'] ?></option>
          <?php } ?>
        </select>
      </td>
    </tr>
    <tr>
      <td>AFML Page Number</td>
      <td><input type="text" name="afml_page_no" required="required" autocomplete="off" /></td>
    </tr>
    <tr>
      <td>Captain</td>
      <td>
        <select required name="afml_pilot">
          <option value=""> -- Choose -- </option>
          <?php
          $sql  = "SELECT user_id,full_name FROM tbl_user WHERE department_id = 4 AND client_id = '".$_SESSION['client_id']."'";
          echo $sql;
          $h    = mysqli_query($conn,$sql);
          while($row = mysqli_fetch_assoc($h)) {
          ?>
          <option value="<?php echo $row['user_id'] ?>"><?php echo $row['full_name'] ?></option>
          <?php } ?>
        </select>
      </td>
    </tr>
    <tr>
      <td>Copilot</td>
      <td>
        <select class="form-control" name="afml_copilot" style="padding-left: 5px; padding-right: 5px">
          <option value=""> -- Choose -- </option>
          <?php
          $sql  = "SELECT user_id,full_name FROM tbl_user WHERE department_id = 4 AND client_id = '".$_SESSION['client_id']."'";
          echo $sql;
          $h    = mysqli_query($conn,$sql);
          while($row = mysqli_fetch_assoc($h)) {
          ?>
          <option value="<?php echo $row['user_id'] ?>"><?php echo $row['full_name'] ?></option>
          <?php } ?>
        </select>
      </td>
    </tr>
    <tr>
      <td>EOB</td>
      <td>
        <select class="form-control" name="afml_engineer_on_board" style="padding-left: 5px; padding-right: 5px">
          <option value=""> -- None -- </option>
          <?php
          $sql  = "SELECT user_id,full_name FROM tbl_user WHERE department_id = 1 AND client_id = '".$_SESSION['client_id']."'";
          echo $sql;
          $h    = mysqli_query($conn,$sql);
          while($row = mysqli_fetch_assoc($h)) {
          ?>
          <option value="<?php echo $row['user_id'] ?>"><?php echo $row['full_name'] ?></option>
          <?php } ?>
        </select>
      </td>
    </tr>
    <tr>
      <td>Notes</td>
      <td>
        <input type="text" class="form-control" name="afml_notes_pilot" autocomplete="off" />
      </td>
    </tr>    
    <tr>
      <td>Upload AFML File (Format JPG, PDF)</td>
      <td><input type="file" name="upload_file"></td>
    </tr>
  </table>
  <h5>ECTM MANUAL ENTRY</h5>
  <table>
    <tr>
      <td>Time (format HH:MM)</td>
      <td><input type="text" name="etcm_time" id="etcm_time"  autocomplete="off" /></td>
    </tr>
    <tr>
      <td>Altitude</td>
      <td><input type="text" name="ectm_altitude" autocomplete="off" /></td>
    </tr>
    <tr>
      <td>IAS</td>
      <td><input type="text" name="ectm_ias" autocomplete="off" /></td>
    </tr>
    <tr>
      <td>TQ/EPR</td>
      <td><input type="text" name="ectm_tq" autocomplete="off" /></td>
    </tr>
    <tr>
      <td>ITT/EGT</td>
      <td><input type="text" name="ectm_itt" autocomplete="off" /></td>
    </tr>
    <tr>
      <td>NG/NG2</td>
      <td><input type="text" name="ectm_ng" autocomplete="off" /></td>
    </tr>
    <tr>
      <td>NP/N1</td>
      <td><input type="text" name="ectm_np" autocomplete="off" /></td>
    </tr>
    <tr>
      <td>F/F</td>
      <td><input type="text" name="ectm_ff" autocomplete="off" /></td>
    </tr>
    <tr>
      <td>Oil Temp</td>
      <td><input type="text" name="ectm_oil_temp" autocomplete="off" /></td>
    </tr>
    <tr>
      <td>Oil Press</td>
      <td><input type="text" name="ectm_oil_press" autocomplete="off" /></td>
    </tr>
    <tr>
      <td>OAT</td>
      <td><input type="text" name="ectm_oat" autocomplete="off" /></td>
    </tr>
  </table>
  <input type="submit" value="SAVE">
</form>



