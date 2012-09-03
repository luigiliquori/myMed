<?php

/** Profile for an association */
class Candidature extends GenericDataBean {
	
	// ------------------------------------------------
	// Attributes
	// ------------------------------------------------
	
	/** Key: Annonce ID */
	public $annonceID;
	
	/** Unique key : id */
	public $id;
	
	/** Wrapped data : text */
	public $message;
	
	// ------------------------------------------------
	// Constructor
	// ------------------------------------------------
	
	function __construct() {
		
		// The namespace
		$this->NAMESPACE = "Candidature";
		
		// Predicates (keys)
		$this->_predicatesDef = array(
				"id" => KEYWORD,
				"annonceID" => KEYWORD);
		
		// Wrapped attributes
		$this->_wrapDef = array("message");
		
	}
	
	// ---------------------------------------------------------------------
	// Helpers
	// ---------------------------------------------------------------------
	
	
	/** Get the annonce for this candidature */
	function getAnnonce() {
		$annonceQuery = new Annonce();
		$annonceQuery->id = $this->annonceID;
		$annonces = $annonceQuery->find();
		
		if (sizeof($annonces) != 1) {
			throw new InternalError(
				"Expected 1 annonce with id=$this->annonceID, but got" . sizeof($annonces));
		}
		
		return $annonces[0];
	}
	
	
}