<?php
session_start();
$_SESSION['name']=$_REQUEST['username'];
$_SESSION['photo']=$_REQUEST['sample'];
echo $_SESSION['name'];
echo $_SESSION['photo'];
header("location:my.php");
?>