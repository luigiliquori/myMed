<?php

require_once('../request/RequestJson.php');
require_once('../../../system/config.php');

$request = new RequestJson(null, $_POST, CREATE);
session_start();

if (!isset($_SESSION['accessToken']))
	$request->addArgument('accessToken', 'fb59ac1476cddb835c613732d394fe3b905ef786'); //for demo
$responsejSon = $request->send();
session_write_close();
echo json_encode($responsejSon);


?>