<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------
define('APPLICATION_NAME', "myEurope_v2");
define('APP_ROOT', '.');
define('MYMED_ROOT', '../../');
define('MYMED_URL_ROOT', '../../');

// Include main controller : Dispatches actions to individual controllers
include(MYMED_ROOT . '/system/controllers/index-controller.php');

// Use the application specific locales
textdomain(APPLICATION_NAME);

// Call the main controller
main_controller();

?>
