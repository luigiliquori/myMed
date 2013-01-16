<?php
require_once "oauth-common.php";

/* $_SESSION['appliName'] is (has to be) defined in the index.php of the current application for the redirection after log in with the social network */

session_start();

if (isset($_GET['code'])) {
	
	require_once 'contrib/Google_Oauth2Service.php';
	$oauth2 = new Google_Oauth2Service($client);
		
	$client->authenticate($_GET['code']);

	$user = $oauth2->userinfo->get();
	
	require_once ROOT.'lib/dasp/beans/MUserBean.class.php';
	$_SESSION['user3'] =(array) $user;
	$_SESSION['userFromExternalAuth'] = MUserBean::constructFromGoogleOAuth((array) $user);
	
	$tokens = json_decode($client->getAccessToken(), TRUE);	
	$_SESSION['accessToken'] = $tokens['access_token'];

	// Redirect to main page
	header('Location: '.getTrustRoot().$_SESSION['appliName'].'?action=login');

}

?>