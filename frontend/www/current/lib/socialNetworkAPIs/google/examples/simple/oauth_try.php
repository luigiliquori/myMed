<?php

require_once "oauth-common.php";

require_once 'contrib/Google_Oauth2Service.php';
$oauth2 = new Google_Oauth2Service($client);

$authUrl = $client->createAuthUrl();
header('Location: ' . $authUrl);
?>