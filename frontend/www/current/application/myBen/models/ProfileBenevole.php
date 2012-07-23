<?php

/** Profile for an association */
class ProfileBenevole extends GenericDataBean {
	
	// ------------------------------------------------
	// Attributes
	// ------------------------------------------------
	
	/** Key : User id */
	public $userID;
	
	/** Key:ENUM:competences */
	public $competences;
	
	/** Key:ENUM:typeMissions */
	public $missions;
	
	/** Key:ENUM:typeMissions */
	public $disponibilites;
	
	/** Key:ENUM:mobilite */
	public $mobilite;
	
	/** Data */
	public $tel;
	public $sexe;
	public $dateNaissance;
	public $situation;
	public $subscribe = true;
	
	// ------------------------------------------------
	// Constructor
	// ------------------------------------------------
	
	function __construct() {
		
		// The namespace
		$this->NAMESPACE = "ProfileBenevole";
		
		// Predicates (keys)
		$this->_predicatesDef = array(
				"userID" => KEYWORD,
				"competences" => ENUM,
				"missions" => ENUM,
				"mobilite" => ENUM);
		
		// Data attributes
		$this->_dataDef = array(
				"tel" => KEYWORD,
				"dateNaissance" => DATE,
				"sexe" => ENUM,
				"disponibilites" => ENUM,
				"situation" => ENUM,
				"subscribe" => KEYWORD);
		
	}
	
	/** 
	 *  If subscription is activated, subscribe to announces before publishing the new profile
	 */
	public function publish() {
		
		// Subscribe to "annonces" with the same infos
		if (is_true($this->subscribe)) {
			
			// Dummy announce to subscribe to
			$annonce = new Annonce();
			$annonce->competences = $this->competences;
			$annonce->typeMission = $this->missions;
			$annonce->quartier = $this->mobilite;
			
			$annonce->subscribe();
		}
		
		parent::publish();
		
	}
	
	/** @Override */
	public function populateFromRequest($except=array()) {
		
		// Do not populate the "subscribe" field, as it is a checkbox and is missing if not checked
		array_push($except, "subscribe");
		
		parent::populateFromRequest($except);
		
		// Fill the subscribe attribute
		$this->subscribe = in_request("subscribe");
		
		// Add the option "undef" to "mobilite"
		if (!in_array("undef", $this->mobilite)) {
			array_push($this->mobilite, "undef");
		}
			
	}
	
	
}