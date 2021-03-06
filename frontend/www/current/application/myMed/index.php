<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
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

session_start();

// Name of current application used for the redirection when log in with the socialNetworks
$_SESSION['appliName'] = APPLICATION_NAME;

require_once('header-bar.php');
require_once("header.php");

main_controller();

?>
