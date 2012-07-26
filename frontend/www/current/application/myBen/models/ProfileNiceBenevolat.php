<?php

/** Dummy singleton extended profile for nice benevolat */
class ProfileNiceBenevolat extends ProfileAssociation {
	
	/** Hardcoded user id for Nice benevolat */
	static public $USERID = "MYMED_raphael.jolivet+nicebenevolat@gmail.com"; 
	
	/** @var $instance ProfileNiceBenevolat */
	static private $instance;
	
	/** Singleton */
	static public function getInstance() {
		if (ProfileNiceBenevolat::$instance == null) {
			ProfileNiceBenevolat::$instance = new ProfileNiceBenevolat();
			ProfileNiceBenevolat::$instance->userID = ProfileNiceBenevolat::$USERID;
		}
		return ProfileNiceBenevolat::$instance;
	}
}