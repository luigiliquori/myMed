<?php

require_once "oauth-common.php";

define("APPLICATION_NAME", $_GET['applicationname']);
// 'applicationname' is defined in the LoginView.php href for Google+;
// used to redirect to the application main (myEurope for example) from the Google+ login (not to mymed)

require_once 'contrib/Google_Oauth2Service.php';
$oauth2 = new Google_Oauth2Service($client);

$authUrl = $client->createAuthUrl();
header('Location: ' . $authUrl);
?>