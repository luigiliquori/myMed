<?php
require_once dirname(__FILE__).'/Login.class.php';
require_once dirname(__FILE__).'/Privacy.class.php';
require_once dirname(__FILE__).'/Aboutus.class.php';
$login = new Login();
$login->printTemplate();
$privacy = new Privacy();
$privacy->printTemplate();
$aboutus = new Aboutus();
$aboutus->printTemplate();
?>