<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$message_subject    = input_data(filter_var($_POST['message_subject'],FILTER_SANITIZE_STRING));
$message_detail     = input_data(filter_var($_POST['message_detail'],FILTER_SANITIZE_STRING));
$message_type = '';


if($message_subject == "" || $message_detail == "") {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill marked (*) forms'
  })
  </script>
<?php
  exit();
}

$format_file    = array("jpg", "png", "gif", "zip", "bmp");
$max_file_size  = 1024*1000; //maksimal 1 MB
$path           = "../uploads/messages/";
$count          = 0;
$message_code   = date('dmyhis').$_SESSION['user_id'].$_SESSION['client_id'];

if(@$_FILES['files']['name'] != ''){
  // Loop $_FILES to exeicute all files
  foreach ($_FILES['files']['name'] as $f => $name) {     
    if ($_FILES['files']['error'][$f] == 4) {
        continue; // Skip file if any error found
    }        
    if ($_FILES['files']['error'][$f] == 0) {            
        if ($_FILES['files']['size'][$f] > $max_file_size) {
            $message[] = "$name is too large!.";
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
            $sql1 = "INSERT INTO tbl_messages_file (message_code,file_name,created_date,user_id,client_id) VALUES ('".$message_code."','".$newfilename."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
            mysqli_query($conn,$sql1);
          $count++; // Number of successfully uploaded file
        }
      }
    }
  }
  //echo 'berhasil upload '.$count.' files';
}

//kirim ke pic
$total_recipients = count(@$_POST['send_to_recipients']);
for ($i=0; $i<$total_recipients; $i++) {
  if(@$_POST['send_to_recipients'][$i]) {

    $sql    = "SELECT user_id,full_name,user_email_address FROM tbl_user WHERE client_id = '".$_SESSION['client_id']."' AND user_id = '".$_POST['send_to_recipients'][$i]."'";
    $h      = mysqli_query($conn,$sql);
    $row    = mysqli_fetch_assoc($h);
    $cid    = $_SESSION['user_id'].''.$row['user_id'];  
    $sql    = "INSERT INTO tbl_messages (from_id,send_to,message_detail,date_send,c_id,message_subject,message_type,client_id,message_code,message_initial) VALUES ('".$_SESSION['user_id']."','".@$_POST['send_to_recipients'][$i]."','".$message_detail."',UTC_TIMESTAMP(),'".$cid."','".$message_subject."','".$message_type."','".$_SESSION['client_id']."','".$message_code."',1)";
    //echo $row['full_name'].'<br/>';
    mysqli_query($conn,$sql);

    $to           = $row['user_email_address'];
    $subject      = "You have new message";
    $htmlContent  = 'Dear'.$row['full_name'].', you have a message on AMS. Please login to read';
    $headers      = "MIME-Version: 1.0" . "\r\n";
    $headers      .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers      .= 'From: AMS <no-reply@ams.satukode.com>' . "\r\n";
    mail($to,$subject,$htmlContent,$headers); 
  }
}

//kirim ke group
$total_group = count(@$_POST['send_to_group']);
for ($i=0; $i<$total_group; $i++) {
  if(@$_POST['send_to_group'][$i]=='ALLE') {
    $sql_u    = "SELECT user_id,full_name,user_email_address FROM tbl_user WHERE client_id = '".$_SESSION['client_id']."' AND department_id = 1";
  }elseif(@$_POST['send_to_group'][$i]=='ALLA') {
    $sql_u    = "SELECT user_id,full_name,user_email_address FROM tbl_user WHERE client_id = '".$_SESSION['client_id']."' AND user_id <> '".$_SESSION['user_id']."'";    
  }else{
    $sql_u    = "SELECT user_id,full_name,user_email_address FROM tbl_user WHERE client_id = '".$_SESSION['client_id']."' AND department_id = 4";
  }
  //echo $sql_u.'<br/>';        
  $h_u      = mysqli_query($conn,$sql_u);
  while($row_u = mysqli_fetch_assoc($h_u)) {
    $cid    = $_SESSION['user_id'].''.$row_u['user_id'];
    $sql        = "INSERT INTO tbl_messages (from_id,send_to,message_detail,date_send,c_id,message_subject,message_type,client_id,message_code,message_initial) VALUES ('".$_SESSION['user_id']."','".$row_u['user_id']."','".$message_detail."',UTC_TIMESTAMP(),'".$cid."','".$message_subject."','".$message_type."','".$_SESSION['client_id']."','".$message_code."',1)";
    //echo $row_u['full_name'].'<br/>';
    mysqli_query($conn,$sql);

    $to           = $row_u['user_email_address'];
    $subject      = "You have new message";
    $htmlContent  = 'Dear'.$row_u['full_name'].', you have a message on AMS. Please login to read';
    $headers      = "MIME-Version: 1.0" . "\r\n";
    $headers      .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers      .= 'From: AMS <no-reply@ams.satukode.com>' . "\r\n";
    mail($to,$subject,$htmlContent,$headers);
  } 
}

//insert ke table log user
$sql_log    = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('MESSAGE-ADD','MESSAGE','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'compose new message with subject: $message_subject','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
swal({text: "Data updated", type: "success"}).then(function(){ location.reload(); });
</script>