<?php
require_once 'header.php';
require_once "components.php";

$sql  = "SELECT document_files_id,document_file_name FROM tbl_document_files a INNER JOIN tbl_document_files_department b USING (document_content_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND document_department_id = '".$_SESSION['department_id']."' AND document_files_id = '".$ntf[1]."' LIMIT 1";
$h    = mysqli_query($conn, $sql);
if(mysqli_num_rows($h)>0) {
  $row  = mysqli_fetch_assoc($h);

  //input ke data siapa yang download
  $sql = "INSERT INTO tbl_document_files_download (document_files_id,created_date,user_id,client_id) VALUES ('".$row['document_files_id']."',UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
  mysqli_query($conn,$sql);
?>
<body onload="javascript:window.location.href='../../uploads/document/<?php echo $row['document_file_name'] ?>';">  
<?php } ?>
