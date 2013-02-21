<?php

/* Start session and load library. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

//define('CONSUMER_KEY', 'DRRf2tOoQvsktXBO66kuTw'); define('CONSUMER_SECRET', 'Vd7hu2rvFiKb6NFwHVtmm0Ie4hOTvClf19RsfUNnQ8'); // machine 231

if(strpos($_SERVER['SERVER_NAME'], 'www')==false){ // http://mymed.fr
	define('CONSUMER_KEY', 'bNiImVmOCf4wdNPjEIsgw'); 
	define('CONSUMER_SECRET', 't3WP9o1jQUg6eIfNR9asU8YG0pCzPTa6ccak5sDsVc'); 
}else{
	define('CONSUMER_KEY', 'rxnjz46zCzvrrYZmyxx0A');
	define('CONSUMER_SECRET', 'oVRSV3p2BVzld2Ay95DG1MXM6Va1KGvUQq2kySAHWBc');
}

/* Build TwitterOAuth object with client credentials. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET); // defined in twitter/config.php

/* Get temporary credentials. */
$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

/* Save temporary credentials to session. */
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 
/* If last connection failed don't display authorization link. */
switch ($connection->http_code) {
  case 200:
    /* Build authorize URL and redirect user to Twitter. */
    $url = $connection->getAuthorizeURL($token);
    header('Location: ' . $url); 
    break;
  default:
    /* Show notification if something went wrong. */
    echo 'Could not connect to Twitter. Refresh the page or try again later.';
}
