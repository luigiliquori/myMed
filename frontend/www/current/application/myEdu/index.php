<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------

define('APPLICATION_NAME', "myTemplate");
define('APP_ROOT', __DIR__);
define('MYMED_ROOT', __DIR__ . '/../..');

// Include main controller : Dispatches actions to individual controllers
include(MYMED_ROOT . '/system/controllers/index-controller.php');

// Use the application specific locales
textdomain(APPLICATION_NAME);

require_once('header-bar.php');
require_once('footer-bar.php');

// Print Page
include_once('header.php');
main_controller();
include_once('footer.php');

?>
