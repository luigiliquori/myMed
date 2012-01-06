<?php 
define('APPLICATION_NAME', "myTravel");

require_once dirname(__FILE__).'/MyTravel.class.php'; 
$myTravel = new MyTravel();
$myTravel->printTemplate();
?>
