<?php
	define('APPLICATION_NAME', "myMed");
	define('APP_ROOT', 'application/myMed');
	define('MYMED_ROOT', __DIR__);
	define('MYMED_URL_ROOT', '');
			
	// Include main controller : Dispatches actions to individual controllers
	include(MYMED_ROOT . '/system/controllers/index-controller.php');

	main_controller();
?>

