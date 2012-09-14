<?php

require_once('../request/RequestJson.php');
require_once('../../../system/config.php');

include_once('../../../system/common/common-utils.php');

$userCommented = $_POST['userCommented'];
unset($_POST['userCommented']);

$request = new RequestJson(null, $_POST, UPDATE);
session_start();

$t = time();
$k = hash("crc32b", $t.$_SESSION['user']->id);
$data = array(
		$k => json_encode(array(
				"time"=>$t,
				"user"=>$_SESSION['user']->id,
				"replyTo"=>$_POST['replyTo'],
				"text"=>$_POST['text']
		))
);
$request->addArgument('data', $data);

$res = (array) $request->send();

if (isset($res)) {
	$_POST['user'] = $_SESSION['user']->id;
	$_POST['time'] = $t;
	$_POST['up']=0;
	$_POST['down']=0;
}

session_write_close();

comment($k, $_POST, $userCommented, $_POST['replyTo']!=""?true:false);

//echo json_encode($response);


?>