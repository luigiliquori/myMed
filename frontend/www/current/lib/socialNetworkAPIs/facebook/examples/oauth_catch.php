<?php

require_once "oauth-common.php";

debug('catch');

/* $_SESSION['appliName'] is (has to be) defined in the index.php of the current application for the redirection after log in with the social network */

session_start();

if(!isset($_GET["code"])) { // code is sent by facebook in response
	debug('failed');
	
} else {
	
	if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
		$token_url = "https://graph.facebook.com/oauth/access_token?"
		. "client_id=" . Facebook_APP_ID . "&redirect_uri=" . urlencode(getReturnTo())
		. "&client_secret=" . Facebook_APP_SECRET . "&code=" . $_GET['code'];
	
		$response = file_get_contents($token_url);
		$params = null;
		parse_str($response, $params);
	
		$graph_url = "https://graph.facebook.com/me?access_token="
		. $params['access_token'];
	
		$user = json_decode(file_get_contents($graph_url));
		
		require_once ROOT.'lib/dasp/beans/MUserBean.class.php';
		$_SESSION['user3'] =(array) $user;
		$_SESSION['userFromExternalAuth'] = MUserBean::constructFromFacebookOAuth((array) $user);
		
		debug("session appliname ".$_SESSION['appliName']);
		header('Location: '.getTrustRoot().$_SESSION['appliName'].'?action=login');
		
		/*echo('<pre>');
		print_r($user);
		echo('</pre>');
		echo('<br>');
		echo('<pre>');
		print_r($_SESSION['user']);
		echo('</pre>');*/
		
	}
	else {
		echo("The state does not match. You may be a victim of CSRF.");
	}
	
}


?>