<?php
require_once 'system/templates/AbstractTemplate.class.php';
AbstractTemplate::initializeTemplate("myRivieraAdmin");


// IMPORT THE MAIN VIEW
require_once dirname(__FILE__).'/views/tabbar/PublishView.class.php';
// IMPORT AND DEFINE THE REQUEST HANDLER
require_once dirname(__FILE__).'/handler/MyApplicationHandler.class.php';
$handler = new MyApplicationHandler();
$handler->handleRequest();

$publish = new PublishView();
$publish->printTemplate();

?>
