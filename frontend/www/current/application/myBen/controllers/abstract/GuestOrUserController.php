<?php 

// Tags used in SESSION
define('USER', 'user');
define('ACCESS_TOKEN', 'accessToken');
define('EXTENDED_PROFILE', "extendedProfile");

// Guest user/token
define("GUEST_USERID", "MYMED_mb-guest@yopmail.com");
define("GUEST_TOKEN", APPLICATION_NAME . ":guest");

// types of users
define('USER_GUEST', 'guest');
define('USER_MYMED', 'mymed');
define('USER_NICE_BENEVOLAT', 'nice_benevolat');
define('USER_BENEVOLE', 'benevole');
define('USER_ASSOCIATION', 'association');

/**
 *  This controller fetches user, extendedProfile and token information from the 
 *  SESSION and brings it to the attributes.
 *  It also provides a mean to obtain a guest TOKEN.
 */
class GuestOrUserController extends AbstractController {
	
	// ---------------------------------------------------------------------
	// Attributes
	// ---------------------------------------------------------------------
	
	/** @var MUserBean MyMed profile, or null if not authenticated */
	public $user = null;
	
	/** @var AbstractProfile Extended MyBen profile, or null if not authenticated */
	public $extendedProfile = null;
	

	// ---------------------------------------------------------------------
	// Methods
	// ---------------------------------------------------------------------
	
	/** 
	 *  Overrides request handler, called before each method. 
	 *  If a user is authenticated, fetch the attributes "$user" and "$extendedProfile"  
	 */
	
	public function handleRequest() {
		parent::handleRequest();
		
		if (isset($_SESSION[USER])) {
			$this->user = $_SESSION[USER];
		}
		
		if (isset($_SESSION[EXTENDED_PROFILE])) {
			$this->extendedProfile = $_SESSION[EXTENDED_PROFILE];
		} else {
			
			if (isset($this->user)) {
				// Try to fetch an extended profile (for a MyMed user comming from the launchpad)
				$profile = ExtendedProfileRequired::getExtendedProfile($this->user->id);
				if ($profile != null) {
					$this->extendedProfile = $profile;
					$_SESSION[EXTENDED_PROFILE] = $profile;
				}
			}
		}
	}
	
	/** 
	 * Get the tooken of the authenticated user, or init (and cache) a guest token.
	 * @return string The token 
	 */
	public function getToken() {

		// No token found ?
		if (!isset($_SESSION[ACCESS_TOKEN])) {
			
			$rq = new Request("SessionRequestHandler", CREATE);
			$rq->addArgument("userID", GUEST_USERID);
			$rq->addArgument("accessToken", GUEST_TOKEN);
			$res = json_decode($rq->send());

			// Error ?
			if ($res->status != 200) {
				throw new InternalError($responseObject->description);
			}
			
			// Set the token
			$_SESSION[ACCESS_TOKEN] = $res->data->accessToken;
			
		}
		
		return $_SESSION[ACCESS_TOKEN];
		
	}
	
	/** 
	 *  Get user type.
	 *  @return USER_GUEST : Not logged
	 *          USER_MYMED : Logged but no extended profile 
	 *          USER_BENEVOLE : Logged with benevole extended profile
	 *          USER_ASSOCIATION : Logged with association extended profile
	 *          USER_NICE_BENEVOLAT : Logged with nice benvolat extended profile
	 */
	public function getUserType() {
		if (!isset($this->user)) return USER_GUEST;
		if (!isset($this->extendedProfile)) return USER_MYMED;
		if ($this->user->id == ProfileNiceBenevolat::$USERID) return USER_NICE_BENEVOLAT;
		if ($this->extendedProfile instanceof ProfileBenevole) return USER_BENEVOLE;
		return USER_ASSOCIATION;
	}
	
	
	// ---------------------------------------------------------------------
	// Setters
	// ---------------------------------------------------------------------
	
	/** Artificially  set the token in the session */
	public function setToken($token) {
		$_SESSION[ACCESS_TOKEN] = $token;
	}
	
	/** Set the user */
	public function setUser($user) {
		$this->user = $user;
		$_SESSION[USER] = $user;
	}
	
	/** Set the extended profile */
	public function setExtendedProfile($extendedProfile) {
		$this->extendedProfile = $extendedProfile;
		$_SESSION[EXTENDED_PROFILE] = $extendedProfile;
	}
	

}

?>