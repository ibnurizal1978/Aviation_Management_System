<?php  
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";

$parts_id  = input_data(filter_var($_POST['parts_id'],FILTER_SANITIZE_STRING));

if($_FILES['photo_file']=="") {
  header('location:inventory-parts.php?in=29dvi59&ntf=phr827ao-'.$parts_id.'-6eiysx3c2ak9di');
exit();
}

if ($_FILES['photo_file']['size'] > 1000000) {
  header('location:inventory-parts.php?in=29dvi59&ntf=phs1s34plod-'.$parts_id.'-6eiysx3c2ak9di');
  exit();
}

  $name = $_FILES['photo_file']['name'];
  $target_dir = "../assets/uploads/parts/";
  $target_file = $target_dir . basename($_FILES["photo_file"]["name"]);

  // Select file type
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Valid file extensions
  $extensions_arr = array("jpg","jpeg","png","gif");

// Check extension
if(!in_array($imageFileType,$extensions_arr) ){
    header('location:inventory-parts.php?in=29dvi59&ntf=phty3f1l3-'.$parts_id.'-6eiysx3c2ak9di');
exit();
}
   
// Convert to base64 
$image_base64 = base64_encode(file_get_contents($_FILES['photo_file']['tmp_name']) );
$image = 'data:image/'.$imageFileType.';base64,'.$image_base64;
// Insert record
$query = "INSERT INTO tbl_parts_photo (parts_id,client_id,parts_photo) VALUES ('".$parts_id."','".$_SESSION['client_id']."','".$image."')";
mysqli_query($conn,$query);
header('location:inventory-parts.php?ntf=phr1029wkwedt-6eiysx3c2ak9di');
 ?>  