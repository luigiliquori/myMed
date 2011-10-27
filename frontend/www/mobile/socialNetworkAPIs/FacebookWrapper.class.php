<?php

require_once 'socialNetworkAPIs/IWrapper.class.php';
require_once 'socialNetworkAPIs/facebook/src/facebook.php';

/**
 * Connction to the facebook APIs
 * @author lvanni
 */
class FacebookWrapper implements IWrapper {
	
	private /*Facebook*/ $facebook;
	
	/**
	* Return the social network name of the wrapper
	*/
	public /*String*/ function getSocialNetworkName() {
		return "Facebook";
	}
	
	/**
	 * Default constructor
	 * @param $initialization
	 * 			set to true if the initialization is required
	 */
	public function __construct(/*boolean*/ $initialization = false) { 
		$this->facebook = new Facebook(array(
	 	  'appId'  => Facebook_APP_ID,
	 	  'secret' => Facebook_APP_SECRET,
	 	));
	}

	/**
	 * Handle a facebook login request
	 */
	public /*void*/ function handleAuthentication() {
		// Get User ID
	 	$user = $this->facebook->getUser();
	 	if ($user) {
	 		try {
	 			// Proceed knowing you have a logged in user who's authenticated.
	 			$user_profile = $this->facebook->api('/me');
	 			// register the wrapper
	 			$_SESSION['wrapper'] = $this;
	 			
	 			// Format the user Object
	 			$_SESSION['user'] = new MUserBean();
	 			$_SESSION['user']->id = "FACEBOOK_" . $user_profile["id"];
	 			$_SESSION['user']->login = $user_profile["email"];
	 			$_SESSION['user']->email = $user_profile["email"];
	 			$_SESSION['user']->name = $user_profile["name"];
	 			$_SESSION['user']->firstName = $user_profile["first_name"];
	 			$_SESSION['user']->lastName = $user_profile["last_name"];
	 			$_SESSION['user']->link = $user_profile["link"];
	 			$_SESSION['user']->birthday = $user_profile["birthday"];
	 			$_SESSION['user']->hometown = $user_profile["hometown"];
	 			$_SESSION['user']->profilePicture = "http://graph.facebook.com/" . $user_profile["id"] . "/picture?type=large";
	 			$_SESSION['user']->gender = $user_profile["gender"];
	 			$_SESSION['user']->socialNetwork = "Facebook";
	 			
	 			$_SESSION['friends'] =  $this->facebook->api('/me/friends?access_token=' . $this->facebook->getAccessToken());
	 			$_SESSION['friends'] = $_SESSION['friends']["data"];
	 			$i=0;
	 			foreach ($_SESSION['friends'] as $friend ) { 
	 					$_SESSION['friends'][$i++]["link"] = "https://www.facebook.com/profile.php?id=" . $friend["id"];
	 			}
	 			
	 		} catch (FacebookApiException $e) {
	 			error_log($e);
	 			$user = null;
	 		}
	 		
// 	 		header("Refresh:0;url=" . $_SERVER['PHP_SELF'] . "?socialNetwork=facebook&accessToken=" . $this->facebook->getAccessToken()); // REDIRECTION
	 	}	
	}
	
	/**
	 * get the login url for the social network button
	 */
	public /*String*/ function getLoginUrl() {
		return $this->facebook->getLoginUrl() . "&scope=email,read_stream";
	}
	
	/**
	* get the logout url for the social network button
	*/
	public /*String*/ function getLogoutUrl() {
		return $this->facebook->getLogoutUrl();
	}
	
}

?>