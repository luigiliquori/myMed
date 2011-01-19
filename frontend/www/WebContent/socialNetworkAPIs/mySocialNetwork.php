<?php session_start(); ?>
<?php ini_set('display_errors', 0); ?>

<!-- 
******************************************************************
* VISITEUR (Default values)
******************************************************************
 -->
<?php
 	if($_GET["try"] == 1){
		$_SESSION['user'] = json_decode('{
		   "id": "",
		   "name": "Visiteur",
 		  "first_name": "Un",
 		  "last_name": "Known",
		   "link": "http://www.facebook.com/profile.php?id=007",
		   "hometown": {
 		     "id": "",
 		     "name": null
 		  },
 		  "gender": "something",
		   "timezone": 1,
		   "locale": "somewhere",
		   "verified": true,
		   "updated_time": "now"
		}');
		
		$_SESSION['profile_picture_url'] = "http://graph.facebook.com//picture?type=large";

		$_SESSION['socialNetwork'] = "myMed";
	} 
?>


<!-- 
******************************************************************
* FACEBOOK
******************************************************************
 -->

<!-- APIs -->
<script src="http://connect.facebook.net/en_US/all.js"></script>

<!-- FACEBOOK APPLICATION AUTHENTICATION-->
<?php
	define('FACEBOOK_APP_ID', '154730914571286');
	define('FACEBOOK_SECRET', '06b728cd7b6527c7cc2af70b3581bbf3');

	function get_facebook_cookie($app_id, $application_secret) {
	  $args = array();
	  parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
	  ksort($args);
	  $payload = '';
	  foreach ($args as $key => $value) {
	    if ($key != 'sig') {
	      $payload .= $key . '=' . $value;
   	 	}
  	  }
  	  if (md5($payload . $application_secret) != $args['sig']) {
    	return null;
  	  }
  	  return $args;
	}

	$cookie = get_facebook_cookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);
?>

<!-- FACEBOOK USER AUTHENTICATION -->
<?php 
	if ($cookie['access_token']) { 
		$_SESSION['user'] = json_decode(file_get_contents(
   			   'https://graph.facebook.com/me?access_token=' . $cookie['access_token']));
		
		$_SESSION['profile_picture_url'] = "http://graph.facebook.com/" . $_SESSION['user']->id . "/picture?type=large"; 

		$_SESSION['friends'] = json_decode(file_get_contents(
   		   				 'https://graph.facebook.com/me/friends?access_token=' .
   		   				 $cookie['access_token']))->data;

		$_SESSION['socialNetwork'] = "facebook";
	}
?>

<!-- INIT -->
<div id="fb-root"></div>
<script>
      FB.init({appId: '<?= FACEBOOK_APP_ID ?>', status: true,
               cookie: true, xfbml: true});
      FB.Event.subscribe('auth.login', function(response) {
        window.location.reload();
      });
</script>

<!-- 
******************************************************************
* TWITTER NEED PHP5-CURL PACKAGE
******************************************************************
 -->
 
 <?php
 /**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

require_once('twitter/twitteroauth/twitteroauth/twitteroauth.php');
require_once('twitter/config.php');

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  	$_SESSION['oauth_status'] = 'oldtoken';
	session_destroy();
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

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
  
	/* Get user access tokens out of the session. */
	$access_token = $_SESSION['access_token'];

	/* Create a TwitterOauth object with consumer/user tokens. */
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

	/* If method is set change API call made. Test is called by default. */
	$content = $connection->get('account/verify_credentials');

	$_SESSION['user'] = json_decode('{
		   "id": "' . $content->id_str . '",
		   "name":"' . $content->name . '",
 		  "first_name": "Un",
 		  "last_name": "Known",
		   "link": "http://twitter.com/?id=' . $content->id_str . '",
		   "hometown": {
 		     "id": "",
 		     "name": null
 		  },
 		  "gender": "something",
		   "timezone": 1,
		   "locale": "'. $content->lang .'",
		   "verified": true,
		   "updated_time": "' . $content->created_at . '"
		}');
		
	$_SESSION['profile_picture_url'] = ereg_replace("_normal", "", $content->profile_image_url);

	$_SESSION['socialNetwork'] = "twitter";
}
?>

<!-- 
******************************************************************
* GOOGLE
******************************************************************
 -->
 
<!-- 
******************************************************************
* MYMED
******************************************************************
 -->
 
 <!-- SETUP THE ENVIRONEMENT VARS -->
<?php
	$user = $_SESSION['user'];
	$profile_picture_url = $_SESSION['profile_picture_url'];
	$friends = $_SESSION['friends'];
	$socialNetwork = $_SESSION['socialNetwork'];
?> 