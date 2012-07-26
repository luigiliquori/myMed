<?php

/** 
 * Dummy class to show the extended profile.
 * Used as standrd entry point by the launchpad. 
 */
class ExtendedProfileController extends AbstractController {
	
	// ----------------------------------------------------------------------
	// Attributes
	// ----------------------------------------------------------------------
	
	/** Controller to handle authentication */
	private $extendedProfileRequired;
	private $authenticationRequired;
	
	/** Got from the request, or current user */
	public $_extendedProfile = null; // The requested profile
	public $_user = null; 
	
	/** Constructor */
	function __construct() {
		$this->extendedProfileRequired = new ExtendedProfileRequired();
		$this->authenticationRequired = new AuthenticatedController();		
	}
	
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
	
		// We need an extended profile
		$this->handleExtendedProfileRequired();
		
		// Id provided ?
		if (in_request("id")) {
		
			// Get id
			$id = $_REQUEST['id'];
			
			// Not same id as current user ?
			if ($id != $this->user-id) {
					
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
	
	// ----------------------------------------------------------------------
	// Action handlers
	// ----------------------------------------------------------------------
	
	/** Show edit form */
	function edit() {
		
		// Profile needed
		$this->fetchProfiles();
		$this->requireWriteAccess();
				
		// Switch on profile type
		if ($this->_extendedProfile instanceof ProfileBenevole) {
			$this->profileBenevole = $this->_extendedProfile;
			$this->renderView("editProfileBenevole");
		} else {
			$this->profileAssociation = $this->_extendedProfile;
			$this->renderView("editProfileAssociation");
		}

	}
	
	/** Create an extended profile */
	function create() {
		
		// Authentication required
		$this->handleAuthenticationRequired();
		
		// Type of profile
		$type = $_REQUEST['type'];
		
		// Switch on profile type
		if ($type == "benevole") {
				
			// Create a benevole profile
			$profile = new ProfileBenevole();
		
		} else if ($type == "association") {
				
			// Create a association profile
			$profile = new ProfileAssociation();
				
		} else {
			throw new InternalException("Bad profile type : '$type'");
		}
		
		// Populate it
		$profile->userID = $this->user->id;
		
		// Populate all except userID
		$profile->populateFromRequest(
				array("userID"));
			
		// Save it
		$profile->publish();
		
		// View
		$this->redirectTo("main");
		
	}
	
	/** Update the profile */
	function update() {
		
		// Profile needed
		$this->fetchProfiles();
		$this->requireWriteAccess();
		
		// Type of profile
		switch($type = $_REQUEST['type']) {
			case "benevole" : 
				$profile = new ProfileBenevole();
				break;
			case "association" :
				$profile = new ProfileAssociation();
				break;
			default : 
				throw new Exception("Bad profile type : '$type'");
		}

		// Populate it
		$profile->userID = $this->user->id;
		$profile->publisherID = $this->user->id;

		// Populate all except userID
		$profile->populateFromRequest(
				array("userID"));

		// Update the profile (delete the old one)
		$this->_extendedProfile->delete();
		$profile->publish();
		$this->_extendedProfile = $profile;
		
		// If we are currently editing the current user profile, update it in the session
		if (!in_request("id")) {
			$this->extendedProfile = $this->_extendedProfile;
			$_SESSION[EXTENDED_PROFILE] = $profile;
		}

		// Fill success message
		$this->setSuccess("Profil mis à jour");
			
		// Show the edit form again
		$this->edit();
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
		
		// Profile needed
		$this->fetchProfiles();
		$this->requireReadAccess();
		
		// Switch on profile type
		if ($this->_extendedProfile instanceof ProfileBenevole) {
			$this->profileBenevole = $this->_extendedProfile;
		} else {
			$this->profileAssociation = $this->_extendedProfile;
		}
		
		// Render a read only view
		$this->renderView("ShowProfile");
	}
	
	/** By default : view the profile */
	function defaultMethod() {
		$this->show();
	}
}