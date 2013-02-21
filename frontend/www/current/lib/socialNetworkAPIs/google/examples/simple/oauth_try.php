<?php

if(strpos($_SERVER['SERVER_NAME'], 'www')==false){ // http://mymed.fr
	define('Google_APP_SECRET', 'AIzaSyDUjDokDbzFpxNKXAj8EXqyWeUrGW06TIk');
	define('Google_APP_CLIENT_ID', '376803621438.apps.googleusercontent.com');
	define('Google_APP_CLIENT_SECRET', 'v3qux1l94rOJqCyXS1qMrFhy');
}else{
	define('Google_APP_SECRET', 'AIzaSyAsFQAe0YULLS72-o_Nd0fX1jyj97CZBgA');
	define('Google_APP_CLIENT_ID', '493954073161.apps.googleusercontent.com');
	define('Google_APP_CLIENT_SECRET', '2eqeVS49dcgQuTQDdl42MtJ_');
}

require_once "oauth-common.php";

require_once 'contrib/Google_Oauth2Service.php';

$oauth2 = new Google_Oauth2Service($client);

$authUrl = $client->createAuthUrl();
header('Location: ' . $authUrl);
?>