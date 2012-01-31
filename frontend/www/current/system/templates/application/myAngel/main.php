<?php
define('APPLICATION_NAME', "myAngel");

require_once dirname(__FILE__).'/MyAngel.class.php';
$myAngel = new MyAngel();
$myAngel->printTemplate();
?>
