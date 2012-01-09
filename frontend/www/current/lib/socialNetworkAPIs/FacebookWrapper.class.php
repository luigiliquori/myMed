<?php

require_once 'lib/socialNetworkAPIs/IWrapper.class.php';
require_once 'lib/socialNetworkAPIs/facebook/src/facebook.php';

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
				$user_mymedWrapper;
				
				// Create the myMed session
				$request = new Request("SessionRequestHandler", CREATE);
				$request->addArgument("userID", "FACEBOOK_" . $user_profile["id"]);
				$request->addArgument("accessToken", $this->facebook->getAccessToken());
				
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
				
				if($responseObject->status != 200) {
					throw new FacebookApiException();
				}
				$accessToken = $responseObject->data->accessToken;
				$url = $responseObject->data->url;
					
				// Format the user Object
				$user_mymedWrapper = new MUserBean();
				$user_mymedWrapper->id = "FACEBOOK_" . $user_profile["id"];
				$user_mymedWrapper->login = $user_profile["email"];
				$user_mymedWrapper->email = $user_profile["email"];
				$user_mymedWrapper->name = $user_profile["name"];
				$user_mymedWrapper->firstName = $user_profile["first_name"];
				$user_mymedWrapper->lastName = $user_profile["last_name"];
				$user_mymedWrapper->link = $user_profile["link"];
				$user_mymedWrapper->birthday = "";
				$user_mymedWrapper->hometown = "";
				$user_mymedWrapper->profilePicture = "http://graph.facebook.com/" . $user_profile["id"] . "/picture?type=large";
				$user_mymedWrapper->gender = $user_profile["gender"];
				$user_mymedWrapper->socialNetworkID = "Facebook";
				$user_mymedWrapper->socialNetworkName = "facebook";
					
				// make the user profile persistant into myMed TODO: verify if not exist
				$request = new Request("ProfileRequestHandler", CREATE);
				$request->addArgument("user", json_encode($user_mymedWrapper));
				$request->addArgument("accessToken", $this->facebook->getAccessToken());

				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
				
				if($responseObject->status != 200) {
					throw new FacebookApiException();
				}
				
				// MOMORIZE THE WRAPPER
				$_SESSION['wrapper'] = $this;
				
				// REDIRECTION
				header("Refresh:0;url=" . $url . "&accessToken=" . $accessToken);
				
			}	catch (FacebookApiException $e) {
				error_log($e);
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
		$this->refreshUserData();
		$this->refreshFriendsData();
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
