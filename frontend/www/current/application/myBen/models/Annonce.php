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
	
	/** Key:promue/not promue */
	public $promue;
	
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
			"promue" => KEYWORD,
			"associationID" => KEYWORD,
			"competences" => ENUM,
			"typeMission" => ENUM,
			"quartier" => ENUM);
		
		// Data attributes
		$this->_wrapDef = array("titre", "description", "begin", "end");
		
	}
	
	// ---------------------------------------------------------------------
	// Helpers
	// ---------------------------------------------------------------------
	
	/** Retrieve all candidatures */
	public function getCandidatures() {
		
		// Build a query to get them all 
		$candidatureQuery = new Candidature();
		$candidatureQuery->annonceID = $this->id;
		
		// Find all 
		$candidatures = $candidatureQuery->find();
		
		return $candidatures;
	}
	
	/** Retrieve all candidatures */
	public function getAssociation() {
	
		return ExtendedProfileRequired::getExtendedProfile($this->associationID);
		
	}
	
	/** Is this event passed ? */
	public function isPassed() {
		
		if (empty($this->end)) return false;
		
		// End date
		$date = DateTime::createFromFormat(
				DATE_FORMAT, 
				$this->end);
		
		$today = new DateTime('today');
		
		return $today > $date;
		
	}
	
	// ---------------------------------------------------------------------
	// Overriden methods
	// ---------------------------------------------------------------------
	
	/** Promue is false by default */
	public function publish() {
		
		// No "promue" ? => false by default
		if ($this->promue == null) {
			$this->promue = "false";
		}
		
		parent::publish();
	}
	
	
}