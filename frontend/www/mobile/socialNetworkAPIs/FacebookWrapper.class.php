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
	 * refreshUserData
	 */
	public /*void*/ function refreshUserData() {
		// Get User ID
		$user = $this->facebook->getUser();
		if ($user) {
			try {
				// Proceed knowing you have a logged in user who's authenticated.
				$user_profile = $this->facebook->api('/me');
					
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
				$_SESSION['user']->hometown = "";
				$_SESSION['user']->profilePicture = "http://graph.facebook.com/" . $user_profile["id"] . "/picture?type=large";
				$_SESSION['user']->gender = $user_profile["gender"];
				$_SESSION['user']->socialNetworkID = "Facebook";
				$_SESSION['user']->socialNetworkName = "facebook";
					
				// make the user profile persistant into myMed
				$request = new Request("ProfileRequestHandler", CREATE);
				$request->addArgument("user", json_encode($_SESSION['user']));

				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
				if($responseObject->status != 200) {
					throw new FacebookApiException($responseObject->description);
				}
			}	catch (FacebookApiException $e) {
				error_log($e);
				$_SESSION['user'] = null;
			}
		}
	}

	/**
	 * refreshFriendsData
	 */
	public /*void*/ function refreshFriendsData() {
		// Get User ID
		$user = $this->facebook->getUser();
		if ($user) {
			try {
				$friends =  $this->facebook->api('/me/friends?access_token=' . $this->facebook->getAccessToken());
				$_SESSION['friends'] = $friends["data"];
				$i=0;
				foreach ($_SESSION['friends'] as $friend ) {
					$_SESSION['friends'][$i]["link"] = "https://www.facebook.com/profile.php?id=" . $friend["id"];
					$_SESSION['friends'][$i]["profilePicture"] = "http://graph.facebook.com/" . $friend["id"] . "/picture?type=large";

					// GET POSITION
					$request = new Request("PositionRequestHandler", READ);
					$request->addArgument("userID", "FACEBOOK_" . $friend["id"]);
					$responsejSon = $request->send();
					$responseObject = json_decode($responsejSon);
					if($responseObject->status == 200) {
						$_SESSION['friends'][$i]["position"] = json_decode($responseObject->data->position);
					}
					$i++;
				}
			}	catch (FacebookApiException $e) {
				error_log($e);
				$_SESSION['friends'] = null;
			}
		}
	}
	
	/**
	 * Return the social network name of the wrapper
	 */
	public /*String*/ function getSocialNetworkName() {
		return "Facebook";
	}

	public /*String*/ function getSocialNetworkButton() {
		return "<img alt='facebook' src='img/facebook_button.gif' />";
	}

	/**
	 * Handle a facebook login request
	 */
	public /*void*/ function handleAuthentication() {
		if(!isset($_SESSION['wrapper'])){
			$_SESSION['wrapper'] = $this;
		}
		if(!isset($_SESSION['user'])) {
			$this->refreshUserData();
		}
		if(!isset($_SESSION['friends'])) {
			$this->refreshFriendsData();
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