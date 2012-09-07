<?php

require_once('../request/Requestv2.php');
require_once('../../../system/config.php');

session_start();
/*
 * 
 * Reputation Vote
 */


$request = new Requestv2("InteractionRequestHandler", UPDATE, $_GET);
$request->addArgument("consumer", $_SESSION['user']->id );

$request->addArgument("start", time() );
$request->addArgument("end", time() );

/*
 * feedback
 * predicate
 * producer
 * application
 */


$responsejSon = $request->send();
session_write_close();
echo $responsejSon;


?>