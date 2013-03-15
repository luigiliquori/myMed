<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------
define('APPLICATION_NAME', "myRiviera");
define('APP_ROOT', '.');
define('MYMED_ROOT', '../../');
define('MYMED_URL_ROOT', '../../');

// Include main controller : Dispatches actions to individual controllers
include(MYMED_ROOT . '/system/controllers/index-controller.php');

session_start();

// Name of current application used for the redirection when log in with the socialNetworks
$_SESSION['appliName'] = APPLICATION_NAME;

textdomain(GLOBAL_MESSAGES);

// IE DETECTION - FORBIDEN
/*if (ereg("MSIE", $_SERVER["HTTP_USER_AGENT"])) {
	include 'IeView.php';
} else {
	
}*/
main_controller();
?>
