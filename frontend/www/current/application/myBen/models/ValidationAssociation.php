<?php

/** Dummy object used to notify association that they have been activated */
class ValidationAssociation extends GenericDataBean {
	
	// ------------------------------------------------
	// Attributes
	// ------------------------------------------------

	/** Key : AssociationID */
	public $associationID;
	
	/** Key : Validated : true */
	public $valid;
	
	// ------------------------------------------------
	// Constructor
	// ------------------------------------------------
	
	function __construct() {
		
		// The namespace
		$this->NAMESPACE = "ValidationAssociation";
		
		// Predicates (keys)
		$this->_predicatesDef = array(
			"associationID" => KEYWORD);
		
		$this->_wrapDef = array("valid");
	}
	
	
}