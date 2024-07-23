<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$afml_page_no     = input_data(filter_var($_POST['afml_page_no'],FILTER_SANITIZE_STRING));


//file harus < 1MB
if ($_FILES["upload_file"]["size"] > 1097152) {
  header('location:afml.php?act=79dvi59g&ntf=2-94dfvj!sdf-349ffuaw'); 
exit();
}

$temp = explode(".", $_FILES["upload_file"]["name"]);
$target_dir = "../uploads/afml-form/";
@$newfilename = $afml_page_no.'-'.date('dmY').'.'.end($temp);
$target_file = $target_dir.$newfilename;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

function compressImage($source_image, $compress_image) {
  $image_info = getimagesize($source_image);
  if ($image_info['mime'] == 'image/jpeg') {
    $source_image = imagecreatefromjpeg($source_image);
    imagejpeg($source_image, $compress_image, 50);
  } elseif ($image_info['mime'] == 'image/gif') {
    $source_image = imagecreatefromgif($source_image);
    imagegif($source_image, $compress_image, 50);
  } elseif ($image_info['mime'] == 'image/png') {
    $source_image = imagecreatefrompng($source_image);
    imagepng($source_image, $compress_image, 5);
  }
  return $compress_image;
}
  
//file yang boleh diupload
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "pdf" && $imageFileType != "gif" && $imageFileType != "jpeg") {
    header('location:afml.php?act=79dvi59g&ntf=3-94dfvj!sdf-349ffuaw');
  exit();
}

if(move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file)) {

  $source_image = $target_file;
  $image_destination = $target_dir.$newfilename;
  $compress_images = compressImage($source_image, $image_destination);

  $sql   = "UPDATE tbl_afml SET afml_file = '".@$newfilename."' WHERE afml_page_no = '".$afml_page_no."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
  mysqli_query($conn,$sql);

  //insert ke table log user
  $sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AFML-ATTACHMENT-ADD','AFML','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add attachment for AFML page no: $afml_page_no','".$_SESSION['client_id']."')";
  mysqli_query($conn,$sql_log);
//echo $sql;
  header('location:afml.php?act=79dvi59g&ntf=0-94dfvj!sdf-349ffuaw');
}else{
  echo 'gagal';
}
?>