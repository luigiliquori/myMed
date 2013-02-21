<?php
session_start();

require_once "oauth-common.php";

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