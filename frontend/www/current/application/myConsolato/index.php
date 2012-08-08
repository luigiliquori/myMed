<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------

define('APPLICATION_NAME', "myBen");
define('APP_ROOT', __DIR__);
define('MYMED_ROOT', __DIR__ . '/../..');
define('DATE_FORMAT', "d/m/Y");

// Include main controller : Dispatches actions to individual controllers
require(MYMED_ROOT . '/system/controllers/index-controller.php');

// Use the application specific locales
textdomain(APPLICATION_NAME);
_textdomain(APPLICATION_NAME); // for PHPGetText

// Call the main controller
main_controller();

?>
