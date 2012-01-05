<?php
define('APPLICATION_NAME', "myConcert");

require_once dirname(__FILE__).'/MyConcert.class.php';
$myConcert = new MyConcert();
$myConcert->printTemplate();
?>