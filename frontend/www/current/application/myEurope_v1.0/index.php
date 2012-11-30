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

// Call the main controller
main_controller();

?>