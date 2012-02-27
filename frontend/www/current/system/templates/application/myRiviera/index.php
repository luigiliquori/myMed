<?php 
session_start();
$_SESSION['application'] = "myRiviera";
header("Refresh:0;url=".$_SERVER['PHP_SELF']);
?>