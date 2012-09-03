<?php

/** Profile for an association */
class ProfileAssociation extends AbstractProfile {
	
	// ------------------------------------------------
	// Attributes
	// ------------------------------------------------
	
	/** Key:ENUM:competences */
	public $competences;
	
	/** Key:ENUM:typeMissions */
	public $missions;
	
	/** Key:boolean:valid */
	public $valid;
	
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
				"valid" => KEYWORD,
				"competences" => ENUM,
				"missions" => ENUM);
		
		// Wraped
		$this->_wrapDef = array("name");
		
		// Data attributes
		$this->_dataDef = array(
				"siret" => KEYWORD,
				"tel" => TEXT,
				"site" => TEXT,
				"adresse" => TEXT);
	}
	
	
	// ---------------------------------------------------------------------
	// Overridden method
	// ---------------------------------------------------------------------
	
	/** Ignore "valid" from fields to populate from FORM */
	public function populateFromRequest($except = array()) {
		array_push($except, "valid");
		parent::populateFromRequest($except);
	}
	
	/** @override  */
	public function publish() {
		
		// No "valid" ? => false by default
		if ($this->valid == null) {
			$this->valid = "false";
		}
		
		// Inherited method
		parent::publish();
	} 
}