<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$message_id    = input_data(filter_var($_POST['message_id'],FILTER_SANITIZE_STRING));
$message_detail    = input_data(filter_var($_POST['message_detail'],FILTER_SANITIZE_STRING));
$send_to           = input_data(filter_var($_POST['send_to'],FILTER_SANITIZE_STRING));
$message_code      = input_data(filter_var($_POST['message_code'],FILTER_SANITIZE_STRING));

if($message_detail == "") {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill message'
  })
  </script>
<?php
  exit();
}

$format_file = array("jpg", "png", "gif", "zip", "bmp");
$max_file_size = 1024*1000; //maksimal 1 MB
$path = "../uploads/messages/";
$count = 0;

$sql_c      = "SELECT message_subject,c_id FROM tbl_messages WHERE message_id = '".$message_id."' LIMIT 1";
$h_c        = mysqli_query($conn,$sql_c);
$row_c      = mysqli_fetch_assoc($h_c);

$sql        = "INSERT INTO tbl_messages (from_id,send_to,message_subject,message_detail,date_send,c_id,client_id,message_code) VALUES ('".$_SESSION['user_id']."','".$send_to."','RE: ".$row_c['message_subject']."','".$message_detail."',UTC_TIMESTAMP(),'".$row_c['c_id']."','".$_SESSION['client_id']."','".$message_code."')";

mysqli_query($conn,$sql);
$last_id = mysqli_insert_id($conn);

if(@$_FILES['files']['name'] != ''){
  // Loop $_FILES to exeicute all files
  foreach ($_FILES['files']['name'] as $f => $name) {     
      if ($_FILES['files']['error'][$f] == 4) {
          continue; // Skip file if any error found
      }        
      if ($_FILES['files']['error'][$f] == 0) {            
          if ($_FILES['files']['size'][$f] > $max_file_size) {
              $message[] = "$name is too large. Maximum 1 MB";
              continue; // Skip large files
          }
      elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $format_file) ){
        $message[] = "$name is not a valid format";
        continue; // Skip invalid file formats
      }
          else{ // No error found! Move uploaded files 
            $temp = explode(".", $_FILES["files"]["name"][$f]); 
            $newfilename = $name.date('dmyhis').round(microtime(true)) . '.' . end($temp);
            $target_file = $path.$newfilename;
            if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $target_file)) {
                //input ke DB
              $sql1 = "INSERT INTO tbl_messages_file (message_id,message_code,file_name,created_date,user_id,client_id) VALUES ('".$last_id."','".$message_code."','".$newfilename."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
              mysqli_query($conn,$sql1);
            $count++; // Number of successfully uploaded file
          }
        }
      }
  }
  //echo 'berhasil upload '.$count.' files';
}

//insert ke table log user
$sql_log    = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('MESSAGE-REPLY','MESSAGE','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'reply message with message ID: $message_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>