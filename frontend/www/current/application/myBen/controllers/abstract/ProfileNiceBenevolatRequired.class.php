<?php 

/** Extended profile required, with type == BENVOLE */
class ProfileNiceBenevolatRequired extends ExtendedProfileRequired {
	public function __construct() {
		parent::__construct(NICE_BENEVOLAT);
	}
	
	/** Static request handler */
	static public function check() {
		$instance = new ProfileNiceBenevolatRequired();
		$instance->handleRequest();
	}
}

?>