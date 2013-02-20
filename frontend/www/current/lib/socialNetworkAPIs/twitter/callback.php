<?php
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* $_SESSION['appliName'] is (has to be) defined in the index.php of the current application for the redirection after log in with the social network */

session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  	$_SESSION['oauth_status'] = 'oldtoken';
  	header('Location: ./clearsessions.php');
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(Twitter_APP_KEY, Twitter_APP_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

/* Save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['access_token'] = $access_token;

/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code) {
  /* The user has been verified and the access tokens can be saved for future use */
	$_SESSION['status'] = 'verified';
  
  
  	$access_token = $_SESSION['access_token'];
  
  	/* Create a TwitterOauth object with consumer/user tokens. */
  
  	/* If method is set change API call made. Test is called by default. */
  	$content = $connection->get('account/verify_credentials');
  	$array_content = (array)$content;
  	if(!isset($array_content["errors"])){
	  	
	  	require_once ROOT.'lib/dasp/beans/MUserBean.class.php';
	  	$_SESSION['user3'] =(array) $content;
	  	$_SESSION['userFromExternalAuth'] = MUserBean::constructFromTwitterOAuth((array) $content);
	
	  	header('Location: '.getTrustRoot().$_SESSION['appliName'].'?action=login');
  	}else{
  		echo 'Error of authentification';
  	}
	  	/*?>
  	<pre>
        <?php print_r($content); ?>
        
        <?php  print_r($_SESSION['user']); ?>
  	</pre>
	<?*/
  
  
} else {
  /* Save HTTP status for error dialog on connnect page.*/
  
}
