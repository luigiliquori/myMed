<?php

/** Abstract extended profile, with a userID */
class AbstractProfile extends GenericDataBean {
	
	// ------------------------------------------------
	// Attributes
	// ------------------------------------------------
	
	/** Key : User id */
	public $userID;
	public $user;
	
	/** User name, copied from the user */
	public $name;
	
	// ---------------------------------------------------------------------
	// Methods
	// ---------------------------------------------------------------------
	
	/** Fetch the whole User profile information from the user id */
	public function getUser() {
		
		// Cache the result
		if (!isset($this->user)) {
			$rh = new ProfileRequest($this->userID);
			$this->user = $rh->send();
		}
	
		return $this->user;
	}
	
	public function setUser($user) {
		$this->user = $user;
	}
	
	/** Override the publish : Copy the name of the application in it */
	public function publish()  {
	
		// Fetch the user profile
		$user = $this->getUser();
	
		// Copy the name into the extended profile
		$this->name = $user->name;
		
		// Inherited publish
		parent::publish();
	}
	
	/** @override Ignore "name" from fields to populate from POST FORM */
	public function populateFromRequest($except = array()) {
		array_push($except, "name");
		parent::populateFromRequest($except);
	}
	
}