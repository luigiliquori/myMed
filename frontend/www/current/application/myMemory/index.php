<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------

define('APPLICATION_NAME', "myMemory");
define('APP_ROOT', __DIR__);
define('MYMED_ROOT', __DIR__ . '/../..');
define('APP_URL', $_SERVER['HTTP_HOST'].'/application/'.APPLICATION_NAME);

// Include main controller : Dispatches actions to individual controllers
include(MYMED_ROOT . '/system/controllers/index-controller.php');

// Use the application specific locales
textdomain(GLOBAL_MESSAGES);

require_once('header-bar.php');

// Print Page
include_once('header.php');

// Call the main controller
main_controller();

include_once('footer.php');
?>
