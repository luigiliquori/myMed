<?php 

/** Extended profile required, with type == ASSOCIATION and Validated */
class ProfileAssociationValidRequired extends ProfileAssociationRequired {

	public function __construct() {
		parent::__construct(ASSOCIATION);
	}

	public function handleRequest() {
		parent::handleRequest();
		if (!(($this->extendedProfile instanceof ProfileNiceBenevolat)  || is_true($this->extendedProfile->valid))) {
			throw new UserError(
					"Votre association n'a pas encore été validée par Nice Bénévolat.
					Pour celà, merci de leur communiquer une copie de votre parution au Journal Officiel.");
		}
	}

	/** Static request handler */
	static public function check() {
		$instance = new ProfileAssociationValidRequired();
		$instance->handleRequest();
	}
}

?>