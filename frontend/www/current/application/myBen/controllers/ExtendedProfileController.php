<?php

/** 
 * Dummy class to show the extended profile.
 * Used as standrd entry point by the launchpad. 
 */
class ExtendedProfileController extends ExtendedProfileRequired {
	
	
	// Forward to profile view 
	function defaultMethod() {
		$this->forward("editProfile");
	}
}