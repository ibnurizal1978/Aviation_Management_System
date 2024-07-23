<?php
require_once 'header.php';
require_once "components.php";

$sql  = "SELECT km_files_id,km_file_name FROM tbl_km_files a INNER JOIN tbl_km_files_department b USING (km_content_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND km_department_id = '".$_SESSION['department_id']."' AND km_files_id = '".$ntf[1]."' LIMIT 1";
$h    = mysqli_query($conn, $sql);
if(mysqli_num_rows($h)>0) {
  $row  = mysqli_fetch_assoc($h);

  //input ke data siapa yang download
  $sql = "INSERT INTO tbl_km_files_download (km_files_id,created_date,user_id,client_id) VALUES ('".$row['km_files_id']."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
  mysqli_query($conn,$sql);
?>
<body onload="javascript:window.location.href='../../uploads/km/<?php echo $row['km_file_name'] ?>';">  
<?php } ?>
