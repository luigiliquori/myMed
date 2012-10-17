<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------
define('APPLICATION_NAME', "myMed");
define('APP_ROOT', '.');
define('MYMED_ROOT', __DIR__ . '/../..');
define('MYMED_URL_ROOT', '../../');

// Include main controller : Dispatches actions to individual controllers
include(MYMED_ROOT . '/system/controllers/index-controller.php');

// Use the application specific locales
textdomain(GLOBAL_MESSAGES);

// use the common dictionary
include(MYMED_ROOT . '/system/lang/langue.php');

// use the specific dictionary
include('lang/langue.php');

main_controller();

?>
