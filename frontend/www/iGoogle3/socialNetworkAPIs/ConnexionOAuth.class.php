<?php
require_once dirname(__FILE__).'/Connexion.class.php';
@require_once 'OAuth.php';	//installed with ubuntu in /etc/share/php
/**
 * A class to define a OAuth login
 * @author blanchard
 */
abstract class ConnexionOAuth extends Connexion
{
	protected $oauth;
	protected $providerurl;
	protected __construct($providerurl, $key, $secret)
	{
		$this->oauth		= new OAuth($conskey,$conssec);
		$this->providerurl	= $providerurl;
	}
	protected /*void*/ function redirect()
	{
		$request_token_info = $oauth->getRequestToken($this->providerurl);
		$_SESSION['oauth_token_secret'] = $request_token_info['oauth_token_secret'];
		header('Location: '.$this->providerurl.'?oauth_token='.$request_token_info['oauth_token']);
		exit;
	}
}
?>







<?php
$req_url = 'https://fireeagle.yahooapis.com/oauth/request_token';
$authurl = 'https://fireeagle.yahoo.net/oauth/authorize';
$acc_url = 'https://fireeagle.yahooapis.com/oauth/access_token';
$api_url = 'https://fireeagle.yahooapis.com/api/0.1';
$conskey = 'your_consumer_key';
$conssec = 'your_consumer_secret';

session_start();

// En état state=1 la prochaine requete doit inclure le oauth_token.
// Si ce n'est pas le cas, retour à 0
if(!isset($_GET['oauth_token']) && $_SESSION['state']==1)
	$_SESSION['state'] = 0;
try
{
	$oauth = new OAuth($conskey,$conssec,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_URI);
	$oauth->enableDebug();
	if(!isset($_GET['oauth_token']) && !$_SESSION['state'])
	{
		$request_token_info = $oauth->getRequestToken($req_url);
		$_SESSION['secret'] = $request_token_info['oauth_token_secret'];
		$_SESSION['state'] = 1;
		header('Location: '.$authurl.'?oauth_token='.$request_token_info['oauth_token']);
		exit;
	}
	else if($_SESSION['state']==1)
	{
		$oauth->setToken($_GET['oauth_token'],$_SESSION['secret']);
		$access_token_info = $oauth->getAccessToken($acc_url);
		$_SESSION['state'] = 2;
		$_SESSION['token'] = $access_token_info['oauth_token'];
		$_SESSION['secret'] = $access_token_info['oauth_token_secret'];
	} 
	$oauth->setToken($_SESSION['token'],$_SESSION['secret']);
	$oauth->fetch("$api_url/user.json");
	$json = json_decode($oauth->getLastResponse());
	print_r($json);
}
catch(OAuthException $E)
{
	print_r($E);
}
?>