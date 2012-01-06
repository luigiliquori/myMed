<?php 
define('APPLICATION_NAME', "myTranslator");

require_once dirname(__FILE__).'/MyTranslator.class.php'; 
$translator = new MyTranslator();
$translator->printTemplate();
?>
		
		