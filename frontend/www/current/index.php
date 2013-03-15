<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------
define('APPLICATION_NAME', "myMed");
define('APP_ROOT', 'application/myMed');
define('MYMED_ROOT', __DIR__);
define('MYMED_URL_ROOT', '');
		
// Include main controller : Dispatches actions to individual controllers
include(MYMED_ROOT . '/system/controllers/index-controller.php');

session_start();

// Name of current application used for the redirection when log in with the socialNetworks
$_SESSION['appliName'] = APPLICATION_NAME;

main_controller();
?>

