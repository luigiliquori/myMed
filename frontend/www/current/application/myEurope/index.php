<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------

define('APPLICATION_NAME', "myEurope");
define('APP_ROOT', __DIR__);
define('MYMED_ROOT', __DIR__ . '/../..');
define('MYMED_URL_ROOT', '../../');


// Include main controller : Dispatches actions to individual controllers
include(MYMED_ROOT . '/system/controllers/index-controller.php');

textdomain(GLOBAL_MESSAGES);

require_once('header-bar.php');
// require_once('footer-bar.php');

// Print Page
include_once('header.php');
main_controller();
include_once('footer.php');

?>
