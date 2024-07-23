<?php
session_start();
require_once 'config.php';
unset($_SESSION['username']);
unset($_SESSION['user_id']);
session_destroy();
if($_GET['s']=='e') {
	header("Location: ".$base_url."index.php?p=e");
}elseif($_GET['s']=='session') {
	header("Location: ".$base_url."index.php?p=s");
}else{
	header("Location: ".$base_url);
}
?>