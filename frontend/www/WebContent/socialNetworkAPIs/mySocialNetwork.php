<!-- import Libraries -->
<script src="http://connect.facebook.net/en_US/all.js"></script> <!-- FACEBOOK -->

<!-- INITIALISATION -->
<?php 
	session_start();
	if($debug) { 
		ini_set('display_errors', 1); 
	}
	  
	if($_GET["logout"]){
		session_destroy();
		$user="";
		$friends="";
		$_SESSION['logged'] = false;
		?>
		<script>window.location = "."</script>
<?php } else { ?>

<!-- 
******************************************************************
* VISITEUR (Default values)
******************************************************************
 -->
<?php
 	if($_GET["try"]){
		$_SESSION['user'] = json_decode('{
		  "id": "visiteur",
		  "name": "Visiteur",
 		  "gender": "something",
		  "locale": "somewhere",
		  "updated_time": "now",
		  "profile": "http://www.facebook.com/profile.php?id=007",
		  "profile_picture" : "http://graph.facebook.com//picture?type=large",
		  "social_network" : "unknown"
		}');
	} 
?>

<!-- 
******************************************************************
* MYMED
******************************************************************
 -->
 
 <!-- Inscription -->
<?php
	$time = date('l jS \of F Y h:i:s A');
 	if($_POST["inscription"]){
		$_SESSION['user'] = json_decode('{
		  "id": "' . $_POST["email"] . '",
		  "name": "' . $_POST["prenom"] . ' ' . $_POST["nom"] . '",
 		  "gender": "' . $_POST["gender"] . '",
		  "locale": "Francais",
		  "updated_time": "' . $time . '",
		  "profile": "?profile=' . $_POST["prenom"] . $_POST["nom"] . '",
		  "profile_picture" : "http://graph.facebook.com//picture?type=large",
		  "social_network" : "myMed",
		  "email" : "' . $_POST["email"] . '",
		  "password" : "' . $_POST["password"] . '"
		}');
	} 
?>

 <!-- Login -->
 <?php
 if($_POST["login"]){
 	$isAuthenticated = file_get_contents(trim("http://" . $_SERVER['HTTP_HOST'] . ":8080/mymed_backend/RequestHandler?act=12&email=" . $_POST["email"] . "&password=" . $_POST["password"]));
 	if($isAuthenticated){
 		$_SESSION['user'] = json_decode($isAuthenticated);
 	}
 }
?>

<!-- 
******************************************************************
* FACEBOOK
******************************************************************
 -->

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
	if ($cookie['access_token'] && !$_SESSION['logged']) { 
		$facebook_user = json_decode(file_get_contents(
   			   'https://graph.facebook.com/me?access_token=' . $cookie['access_token']));

		$_SESSION['user'] = json_decode('{
		  "id":  "facebook' . $facebook_user->id . '",
		  "name": "' . $facebook_user->name . '",
 		  "gender": "' . $facebook_user->gender . '",
		  "locale": "' . $facebook_user->locale . '",
		  "updated_time": "' . $facebook_user->updated_time . '",
		  "profile": "http://www.facebook.com/profile.php?id=' . $facebook_user->id . '",
		  "profile_picture" : "http://graph.facebook.com/' . $facebook_user->id . '/picture?type=large",
		  "social_network" : "facebook"
		}');		

		$_SESSION['friends'] = json_decode(file_get_contents(
   		   				 'https://graph.facebook.com/me/friends?access_token=' .
   		   				 $cookie['access_token']))->data;
		
	}
?>

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

if (!$_SESSION['logged']){
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
		   "id": "twitter' . $content->id_str . '",
		   "name":"' . $content->name . '",
 		   "gender": "something",
		   "locale": "'. $content->lang .'",
		   "updated_time": "' . $content->created_at . '",
		   "profile": "http://twitter.com/?id=' . $content->id_str . '",
 	       "profile_picture" : "' . ereg_replace("_normal", "", $content->profile_image_url) . '",
		   "social_network" : "twitter"
		}');
}
}
?>

<!-- 
******************************************************************
* GOOGLE
******************************************************************
 -->
 
 
 <!-- Store this information into the myMed network 
<?php
	file_get_contents($_SERVER['HTTP_HOST'] . ":8080/mymed_backend/RequestHandler?act=4&key1=" . $key . "&value1=" . $value);
?> -->

<?php } ?> <!-- END ELSE IF -->

<?php
	$user = $_SESSION['user'];
	$friends = $_SESSION['friends'];
?>

<!-- Store this information into the myMed network and mark session as "logged" -->
<?php	if($user->name && !$_SESSION['logged']){
		$encoded = json_encode($user);
		$result_getcontents = file_get_contents(trim("http://" . $_SERVER['HTTP_HOST'] . ":8080/mymed_backend/RequestHandler?act=10&user=" . urlencode($encoded)));
		$_SESSION['logged'] = true;
	}
?>

