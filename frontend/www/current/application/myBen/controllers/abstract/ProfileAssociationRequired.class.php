<?php 

/** Extended profile required, with type == ASSOCIATION */
class ProfileAssociationRequired extends ExtendedProfileRequired {
	public function __construct() {
		parent::__construct(ASSOCIATION);
	}
	
	/** Static request handler */
	static public function check() {
		$instance = new ProfileAssociationRequired();
		$instance->handleRequest();
	} 
}

?>