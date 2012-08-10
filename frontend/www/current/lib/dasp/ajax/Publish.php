<?php

require_once('../request/Requestv2.php');
require_once('../../../system/config.php');


$request = new Requestv2("v3/PublishRequestHandler", CREATE, $_POST);
session_start();

$request->addArgument('accessToken', 'fb59ac1476cddb835c613732d394fe3b905ef786'); //for demo
$responsejSon = $request->send();
session_write_close();
echo $responsejSon;


?>