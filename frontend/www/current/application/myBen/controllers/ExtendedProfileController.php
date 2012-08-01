<?php

require_once('Request.class.php');

define("MODE_CREATE", "create");
define("MODE_EDIT", "edit");
define("MODE_SHOW", "show");



/** 
 *  Handle creation/update and deletion of profile
 */
class ExtendedProfileController extends GuestOrUserController {
	
	// ----------------------------------------------------------------------
	// Attributes
	// ----------------------------------------------------------------------
	
	/** Controller to handle authentication */
	private $extendedProfileRequired;
	private $authenticationRequired;
	
	/** Got from the request, or current user */
	public $_extendedProfile = null; // The requested profile
	public $_user = null; 
	
	/** MODE_CREATE or MODE_EDIT */
	public $mode =null; 
	
	// ----------------------------------------------------------------------
	// Helpers
	// ----------------------------------------------------------------------
	
	/** 
	 *  Ensure user is authenticated.
	 *  Copy the User as if this class inherited from "AuthenticationRequired"   
	 */
	function handleAuthenticationRequired() {
		$this->authenticationRequired->handleRequest();
		$this->user = $this->authenticationRequired->user;
	}
	
	/**
	 *  Ensure user has extended profile.
	 *  Copy the User as if this class inherited from "ExtendedUserRequiredController"
	 */
	function handleExtendedProfileRequired() {
		$this->extendedProfileRequired->handleRequest();
		$this->user = $this->extendedProfileRequired->user;
		$this->extendedProfile = $this->extendedProfileRequired->extendedProfile;
	}
	
	/** Fetch the profile and the extended profile, either from given id, or the ones of the current user */
	function fetchProfiles() {
	
		// Already fetched (might happen after a "forwardTo") ?
		if ($this->_extendedProfile != null) return;
		
		// We need an extended profile
		$this->handleExtendedProfileRequired();
		
		// Id provided ?
		if (in_request("id")) {
		
			// Get id
			$id = $_REQUEST['id'];
			
			// Not same id as current user ?
			if ($id != $this->user->id) {
					
				// Fetch the user profile
				$rq = new ProfileRequest($id);
				$this->_user = $rq->send();
					
				// Fetch the extended profile
				$this->_extendedProfile = ExtendedProfileRequired::getExtendedProfile($id);
					
				if ($this->_extendedProfile == null) {
					throw new InternalError("No extended profile found for userID:$id");
				}
					
				return;
			}
			
		} 
		
		// ELSE: Use profiles from the current user
		$this->_extendedProfile = $this->extendedProfile;
		$this->_user = $this->user;
	}
	
	
	/** Check whether current user has write access to current profile */
	function hasWriteAcces() {
		return ($this->extendedProfile instanceof ProfileNiceBenevolat ||
				$this->_extendedProfile->userID == $this->user->id);
	}
	
	/** Check whether current user has write access to current profile */
	function hasReadAccess() {
		return ($this->extendedProfile instanceof ProfileNiceBenevolat ||
				$this->_extendedProfile->userID == $this->user->id);
	}
	
	function requireReadAccess() {
		if (!$this->hasReadAccess()) throw new UserError(
				"Vous n'avez pas le droit d'accéder à ce profil");
	}
	
	function requireWriteAccess() {
		if (!$this->hasWriteAcces()) throw new UserError(
				"Vous n'avez pas le droit de modifier ce profil");
	}
	
	/**
	 * Update MyMed profile
	 * @return false if error happened
	 */
	function updateMyMedProfile() {
	
		// Preconditions TODO : i18n of error messages
		if (empty($_POST['email']) ){
			$this->error = "L'email ne peut pas être vide.";	
			return false;	
		}
		
		// Create User bean, or update one
		if ($this->mode == MODE_CREATE) {			
			// Creation of a new user
			$this->_user = new MUserBean();
			$this->_user->email = $_POST['email'];
			$this->_user->id = "MYMED_" . $_POST["email"];
			$this->_user->login = $_POST["email"];
		} else {
			// Fetch the user (either current one or from id)
			$this->fetchProfiles();
		}
	
		if (in_request("lastName")) {
			$this->_user->lastName = $_POST["lastName"];
		}
		$this->_user->firstName = $_POST["firstName"];
		$this->_user->name = $this->_user->firstName . " " . $this->_user->lastName;
		if (in_request("birthday")) {
			$this->_user->birthday = $_POST["birthday"];
		}
		
		// Create new user
		if ($this->mode == MODE_CREATE) {
			
			// Not already logged
			if ($this->user == null) {
				
				// Create new user with authentication
				if (empty($_POST['password']) ) {
					$this->error = "Le mot de passe est vide";
					return false;
				}
				if (empty($_POST['confirm']) ) {
					$this->error = "Le mot de passe de confirmation est vide";
					return false;
				}
				if ($_POST['confirm'] != $_POST['password']) {
					$this->error = "Le mot de passe et le mot de passe de confirmation sont différents";
					return false;
				}
							
				// Create authentication bean
				$mAuthenticationBean = new MAuthenticationBean();
				$mAuthenticationBean->login =  $this->_user->login;
				$mAuthenticationBean->user = $this->_user->id;
				$mAuthenticationBean->password = hash('sha512', $_POST["password"]);
				
				$rq = new Request("AuthenticationRequestHandler", CREATE);
				$rq->addArgument("authentication", json_encode($mAuthenticationBean));
				$rq->addArgument("user", json_encode($this->_user));
				$rq->addArgument("application", APPLICATION_NAME);
				$rq->setUseAccessToken(false);
				
			} else {
				
				// Only NiceBenevolat can create other users
				if ($this->user->id != ProfileNiceBenevolat::$USERID) {
					throw new UserError("Seul Nice Bénévolat peut créer de nouveaux utilisateurs");
				} 
				
				// Create new profile without authentication
				$rq = new Request("ProfileRequestHandler", CREATE);
				$rq->addArgument("user", json_encode($this->_user));
				$rq->addArgument("application", APPLICATION_NAME);
			}		
			
		} else {
			// Update existing user
			$rq = new Request("ProfileRequestHandler", UPDATE);
			$rq->addArgument("user", json_encode($this->_user));
			$rq->addArgument("application", APPLICATION_NAME);
		}
			
		// Send request
		$rq->setMultipart(true);
		$responseObject = json_decode($rq->send());

		// Error ?
		if ($responseObject->status != 200) {
			$this->error = $responseObject->description;
			return false;
		} 
			
		
	
		// Everything went fine
		return true;
	}
	
	// ----------------------------------------------------------------------
	// Action handlers
	// ----------------------------------------------------------------------
	
	/** Common handler, called before each other custom method */
	function handleRequest() {
		parent::handleRequest();
		$this->extendedProfileRequired = new ExtendedProfileRequired();
		$this->authenticationRequired = new AuthenticatedController();
		
		// Need a token (maybe guest one)
		$this->getToken();
	}
	
	/** Show edit form */
	function edit() {
		
		// Profile needed
		$this->fetchProfiles();
		$this->requireWriteAccess();
	
		// Used by the view
		$this->mode = MODE_EDIT;
		
		// Switch on profile type
		if ($this->_extendedProfile instanceof ProfileBenevole) {
			$this->profileBenevole = $this->_extendedProfile;
			$this->renderView("editProfileBenevole");
		} else {
			$this->profileAssociation = $this->_extendedProfile;
			$this->renderView("editProfileAssociation");
		}

	}
	
	/** Show the form to create a profile */
	public function create() {
		
		// Used by the view
		$this->mode = MODE_CREATE;
		
		// Do we know which type of profilze to create ?
		if (in_request("type")) {
					
			// Type of profile
			$type = $_REQUEST['type'];
			
			// Create empty profiles
			if ($type == BENEVOLE) {
				$this->_extendedProfile = new ProfileBenevole();
				$this->_extendedProfile->disponibilites = array_keys(CategoriesDisponibilites::$values);
				$this->_extendedProfile->missions = array_keys(CategoriesMissions::$values);
			} else {
				$this->_extendedProfile = new ProfileAssociation();
				$this->_extendedProfile->missions = array_keys(CategoriesMissions::$values);
			}
			
			// Create empty user profile 
			$this->_user = new MUserBean();
			
			// Show the view : Either Association or Benevole
			$this->renderView("createProfile" . ucfirst($type));
			
		} else {
			// Show the page to switch between different types of profiles 
			$this->renderView("createProfile");
		}

	}

	
	/** Do create an extended profile */
	function doCreate() {
		
		$this->mode = MODE_CREATE;
				
	 	// Create the myMed profile
		$res = $this->updateMyMedProfile();
		
		// Type of profile
		$type = $_REQUEST['type'];
		
		// Error ? Show profile form again
		if (!$res) {	
			$this->renderView("createProfile" . ucfirst($type));
		}
	 	
		// Switch on profile type
		if ($type == BENEVOLE) {
				
			// Create a benevole profile
			$profile = new ProfileBenevole();
		
		} else if ($type == ASSOCIATION) {
				
			// Create a association profile
			$profile = new ProfileAssociation();
				
		} else {
			throw new InternalException("Bad profile type : '$type'");
		}
		
		// Populate it
		$profile->userID = $this->_user->id;
		
		// Publisher is either NiceBenevolat or Guest
		// We do not use $this->_user because it may not be activated yet. 
		if ($this->user != null) {
			$profile->publisherID = $this->_user->id;
		} else {
			$profile->publisherID = GUEST_USERID;
		}
		
		// Set the user manually. Because in the case user has just been created, it is not activated
		// yet and the method getUser() (called by publish()) might fail 
		$profile->setUser($this->_user);
		
		// Populate all except userID
		$profile->populateFromRequest(array("userID"));
		
		// An association created by Nice Benevolat is active by default
		if ($this->extendedProfile == ProfileNiceBenevolat::getInstance() && $type == ASSOCIATION) {
			$profile->valid =true;
		}
		
		// Save it
		$profile->publish();
		
		if ($this->user == null) {
			// New user created a profile
			$this->success = "Félicitation, votre profil a été créé. Allez voir vos mail pour l'activer.";
			$this->forwardTo("login");
		} else {
			// Nice benevolat created a ghost profiles
			$this->success = "Profil correctement créé";
			$this->forwardTo("extendedProfile:show", array("id" => $this->_user->id));
		}
	}
	
	/** Update the profile */
	function update() {
		
		$this->mode = MODE_EDIT;
		
		// Profile needed
		$this->fetchProfiles();
		$this->requireWriteAccess();
		
		// Update the myMed profile
		$res = $this->updateMyMedProfile();
			
		// Error ? Show profile form again
		if (!$res) {
			$this->renderView("createProfile" . ucfirst($type));
		}
		
		// Update the extended profile (delete the old one)
		$this->_extendedProfile->delete();
			
		// Populate all except userID
		$this->_extendedProfile->populateFromRequest(array("userID"));
		$this->_extendedProfile->publish();
		
		// Benevole updated ? => Update subscription
		// Subscribe to "annonces" corresponding to the criterias
		if (is_true($this->subscribe)) {
			// TODO delete old subscription
			$this->getAnnonceQuery()->subscribe();
		}
	
		// If we are currently editing the current user profile, update it in the session
		if (!in_request("id")) {
			$this->extendedProfile = $this->_extendedProfile;
			$_SESSION[EXTENDED_PROFILE] = $this->_extendedProfile;
		}

		// Fill success message
		$this->setSuccess("Profil mis à jour");
			
		// Show the edit form again
		$this->show();
	}
	
	/** Delete the profile */
	function delete() {
		
		// Profile needed
		$this->fetchProfiles();
		$this->requireWriteAccess();
		
		// Delete the extended profile
		$this->_extendedProfile->delete();
		
		// Remove it from SESSION (if editing the current user)
		if (!in_request("id")) {
			unset($_SESSION[EXTENDED_PROFILE]);
		}
		
		$this->redirectTo("main");
	}

	/** View the profile */
	function show() {
		
		$this->mode = MODE_SHOW;
		
		// Profile needed
		$this->fetchProfiles();
		$this->requireReadAccess();
		
		// Render a read only view
		$this->renderView("ShowProfile");
	}
	
	/** Validate an association */
	function validate() {
		
		$this->fetchProfiles();
		
		// Only Nice Benevolat can validate an association
		if (!$this->extendedProfile instanceof ProfileNiceBenevolat) {
			throw new UserError("Vous n'avez pas les droits de valider cette association");
		}
		
		// Only an association can be validated
		if (!$this->_extendedProfile instanceof ProfileAssociation) {
			throw new UserError("Ceci n'est pas une association");
		}
		
		// Update the "valid" field
		$this->_extendedProfile->delete();
		$this->_extendedProfile->valid = "true";
		$this->_extendedProfile->publish();
		
		// Fill success message
		$this->setSuccess("Association validée");
		
		// Show the "view" form again
		$this->show();	
	}
	
	/** By default : view the profile */
	function defaultMethod() {
		$this->show();
	}
}