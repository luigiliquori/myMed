<?php

require_once('../request/Requestv2.php');
require_once('../../../system/config.php');


$request = new Requestv2("v2/PublishRequestHandler", CREATE);
session_start();

if (!isset($_POST['id']))
	$_POST['id'] = time();

foreach ($_POST as $k => $v)
	$request->addArgument($k, $v);



$request->addArgument('accessToken', 'fb59ac1476cddb835c613732d394fe3b905ef786'); //for demo
$responsejSon = $request->send();
session_write_close();
echo $responsejSon;


?>