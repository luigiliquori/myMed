<?php 

/** Extended profile required, with type == BENVOLE */
class ProfileBenevoleRequired extends ExtendedProfileRequired {
	public function __construct() {
		parent::__construct(BENEVOLE);
	}
	
	/** Static request handler */
	static public function check() {
		$instance = new ProfileBenevoleRequired();
		$instance->handleRequest();
	}
}

?>