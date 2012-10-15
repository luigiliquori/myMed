<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------

define('APPLICATION_NAME', "myBen");
define('APPLICATION_LABEL', "myBénévolat");
define('APP_ROOT', __DIR__);
define('MYMED_ROOT', __DIR__ . '/../..');
define('DATE_FORMAT', "d/m/Y");

// Include main controller : Dispatches actions to individual controllers
require(MYMED_ROOT . '/system/controllers/index-controller.php');

// Use the application specific locales
textdomain(APPLICATION_NAME);

// Support for php-gettext
if (PHP_GETTEXT) {
	_textdomain(APPLICATION_NAME);
}

// IE DETECTION - FORBIDEN
if (ereg("MSIE", $_SERVER["HTTP_USER_AGENT"]) && !$ieok) {
	include 'IeView.php';
} else {
	// Call the main controller
	main_controller();
}

?>
