<?php

require_once 'lib/socialNetworkAPIs/FacebookWrapper.class.php';

/**
 * Connction to the facebook APIs
 * @author lvanni
 */
class SocialNetworkConnection {
	
	private /*IWrapper[]*/ $wrappers;
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $init
	 */
	public function __construct(/*boolean*/ $init = false) {
		$this->wrappers = array(
			new FacebookWrapper()
		);
	}
	
	public /*IWrapper[]*/ function getWrappers(){
		return $this->wrappers;
	}
}
?>