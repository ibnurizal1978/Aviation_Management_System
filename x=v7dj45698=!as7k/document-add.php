<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";
$document_name   = input_data(filter_var($_POST['document_files_name'],FILTER_SANITIZE_STRING));
$document_files_tag    = input_data(filter_var($_POST['document_files_tag'],FILTER_SANITIZE_STRING));
$document_category_id      = input_data(filter_var($_POST['document_category_id'],FILTER_SANITIZE_STRING));
$document_arrival_date      = input_data(filter_var($_POST['document_arrival_date'],FILTER_SANITIZE_STRING));
$document_source      = input_data(filter_var($_POST['document_source'],FILTER_SANITIZE_STRING));

if($document_name == "") {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill all forms'
  })
  </script>
<?php
  exit();
}

//date
$document_arrival_date_y   = substr($document_arrival_date,6,4);
$document_arrival_date_m   = substr($document_arrival_date,3,2);
$document_arrival_date_d   = substr($document_arrival_date,0,2);
$document_arrival_date_f   = $document_arrival_date_y.'-'.$document_arrival_date_m.'-'.$document_arrival_date_d;

$sql        = "INSERT INTO tbl_document_content (document_name,document_files_tag,document_category_id,document_arrival_date,document_source,created_date,user_id,client_id) VALUES ('".$document_name."','".$document_files_tag."','".$document_category_id."','".$document_arrival_date_f."','".$document_source."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')"; 
mysqli_query($conn,$sql);
$last_id = mysqli_insert_id($conn);


$format_file    = array("jpg", "png", "gif", "zip", "bmp","doc","docx","xls","xlsx","pdf","tar","ppt","pptx","jpeg");
$max_file_size  = 1024*1000; //maksimal 1 MB
$path           = "../uploads/document/";
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
            $target_file = $path.$name;
              if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $target_file)) {
                //input ke DB
                $sql1 = "INSERT INTO tbl_document_files (document_content_id,document_file_name,document_file_size,created_date,user_id,client_id) VALUES ('".$last_id."','".$name."','".$_FILES['files']['size'][$f]."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
                mysqli_query($conn,$sql1);
              $count++; // Number of successfully uploaded file
            }
          }
      }
  }
}


$banyaknya = count(@$_POST['department_id']);
for ($i=0; $i<$banyaknya; $i++) {
  if(@$_POST['department_id'][$i]) {
    $sql_menu2  = "INSERT INTO tbl_document_files_department(document_content_id,document_department_id,user_id,client_id) VALUES ('".$last_id."','".@$_POST['department_id'][$i]."','".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
    mysqli_query($conn,$sql_menu2);
    //echo $sql_menu2.'<br/>';
  }
}


//insert ke table log user
$sql_log    = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('DOCUMENT-ADD','DOCUMENT','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'upload new document with ID: $last_id','".$_SESSION['client_id']."')";
mysqli_query($conn,$sql_log);
?>
<script type="text/javascript">
Swal.fire(
  '',
  'Data Updated!',
  'success'
)
</script>