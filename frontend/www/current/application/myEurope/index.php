<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------

define('APPLICATION_NAME', "myEurope");
define('APP_ROOT', __DIR__);
define('MYMED_ROOT', __DIR__ . '/../..');
define('APP_URL', $_SERVER['HTTP_HOST'].'/application/'.APPLICATION_NAME);
define('MYMED_URL', $_SERVER['HTTP_HOST']);

// Include main controller : Dispatches actions to individual controllers
include(MYMED_ROOT . '/system/controllers/index-controller.php')

?>
