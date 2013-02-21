<?php
session_start();

require_once "oauth-common.php";

if(strpos($_SERVER['SERVER_NAME'], 'www')==false){ // http://mymed.fr
	define('Facebook_APP_ID', '263064853727560');
	define('Facebook_APP_SECRET', 'dbcad40d88c3c5e4a3532be114117e56');
}else{ // http://www.mymed.fr
	define('Facebook_APP_ID', '161275950692324');
	define('Facebook_APP_SECRET', 'c46c7bbbdf2c83990b7858ecb5c9e53c');
}

$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection

$dialog_url = "https://www.facebook.com/dialog/oauth?client_id=". Facebook_APP_ID 
. "&redirect_uri=" . urlencode(getReturnTo()) 
. "&state=". $_SESSION['state']
. "&scope=email";

debug(getReturnTo());
debug(urlencode(getReturnTo()));
debug($dialog_url);
header('Location: ' . $dialog_url);

?>