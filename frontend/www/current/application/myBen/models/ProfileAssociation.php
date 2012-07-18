<?php

/** Profile for an association */
class ProfileAssociation extends GenericDataBean {
	
	// ------------------------------------------------
	// Attributes
	// ------------------------------------------------
	
	/** Key : User id */
	public $userID;
	
	/** Key:ENUM:competences */
	public $competences;
	
	/** Key:ENUM:typeMissions */
	public $missions;
	
	/** Data */
	public $siret;
	public $tel;
	public $site;
	public $adresse;
	
	// ------------------------------------------------
	// Constructor
	// ------------------------------------------------
	
	function __construct() {
		
		// The namespace
		$this->NAMESPACE = "ProfileAssociation";
		
		// Predicates (keys)
		$this->_predicatesDef = array(
				"userID" => KEYWORD,
				"competences" => ENUM,
				"missions" => ENUM);
		
		// Data attributes
		$this->_dataDef = array(
				"siret" => KEYWORD,
				"tel" => TEXT,
				"site" => TEXT,
				"adresse" => TEXT);
		

	}
	
}