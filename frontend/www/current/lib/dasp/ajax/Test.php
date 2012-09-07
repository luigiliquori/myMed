<?php

require_once '../request/Request.v4.php';
require_once '../../../system/config.php';
session_start();

$request = new Request("v2/TestRequestHandler", CREATE);
$request->addArgument("application", "toto");
$request->addArgument("predicate", "tata");
$request->addArgument("user", json_encode($_SESSION['user']));
$responsejSon = $request->send();
echo $responsejSon;


?>