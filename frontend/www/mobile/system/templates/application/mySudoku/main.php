<?php 
define('APPLICATION_NAME', "mySudoku");

require_once dirname(__FILE__).'/MySudoku.class.php'; 
$mySudoku = new MySudoku();
$mySudoku->printTemplate();
?>