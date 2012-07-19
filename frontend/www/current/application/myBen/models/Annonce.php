<?php

/** Profile for an association */
class Annonce extends GenericDataBean {
	
	// ------------------------------------------------
	// Attributes
	// ------------------------------------------------
	
	/** Key: Annonce ID */
	public $id;
	
	/** Key : AssociationID */
	public $associationID;
	
	/** Key:ENUM:competences */
	public $competences;
	
	/** Key:ENUM:typeMissions */
	public $typeMission;
	
	/** Key:ENUM:mobilite */
	public $quartier;
	
	/** Data */
	public $titre;
	public $description;
	public $begin;
	public $end;
	
	// ------------------------------------------------
	// Constructor
	// ------------------------------------------------
	
	function __construct() {
		
		// The namespace
		$this->NAMESPACE = "Annonce";
		
		// Predicates (keys)
		$this->_predicatesDef = array(
				"id" => KEYWORD,
				"associationID" => KEYWORD,
				"competences" => ENUM,
				"typeMission" => ENUM,
				"quartier" => ENUM);
		
		// Data attributes
		$this->_wrapDef = array("titre", "description", "begin", "end");
		
	}
	
	
}