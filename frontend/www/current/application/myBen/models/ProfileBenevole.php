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
				"situation" => ENUM);
		
	}
	
	
}