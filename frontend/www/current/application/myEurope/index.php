<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------

define('APPLICATION_NAME', "myEurope");
define('APP_ROOT', __DIR__);
define('MYMED_ROOT', __DIR__ . '/../..');
define('MYMED_URL_ROOT', '../../');

define('APPLICATION_LABEL', _("Transborder social network"));

// Include main controller : Dispatches actions to individual controllers
include(MYMED_ROOT . '/system/controllers/index-controller.php');

textdomain(GLOBAL_MESSAGES);

// IE DETECTION - FORBIDEN
if (ereg("MSIE", $_SERVER["HTTP_USER_AGENT"])) {
	include 'IeView.php';
} else {
	main_controller();
}

?>
