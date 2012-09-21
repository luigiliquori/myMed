<?php
	define('APPLICATION_NAME', "myMed");
	define('APP_ROOT', 'application/myMed');
	define('MYMED_ROOT', __DIR__);
	define('MYMED_URL_ROOT', '');
	
	define('APPLICATION_LABEL', _("Réseau social transfontalier"));
	
	//_("myEuropeabout");
	//_("myMedabout");// just to generate this keywords once, comment them after
	
	// Include main controller : Dispatches actions to individual controllers
	include(MYMED_ROOT . '/system/controllers/index-controller.php');

	main_controller();
	
?>

