<?php

/** 
 *  Abstract class for the publications of myConsolato.
 *  This class has 4 subclasses for each main topic,
 *  with 4 different namespaces.
 */
abstract class MyConsolatoPublication extends GenericDataBean {
	
	// ---------------------------------------------------------------------
	// Static method
	// ---------------------------------------------------------------------
	
	/**
	 * Get Publication by id.
	 * generic static method that should be specialized for each sub class
	 * @return InstObject
	 */
	static public function getByIDGeneric($id, $classname) {
	
		// Search this object
		$req = new $classname();
		$req->id = $id;
		$res = $req->find();
	
		// Defensive check => there should be one
		if (sizeof($res) != 1) {
			$size = sizeof($res);
			throw new InternalError("Expected 1 object with ID $id in $req->NAMESPACE but got $size");
		}
	
		// Return uniq result
		return $res[0];
	}
	
	// ---------------------------------------------------------------------
	// Attributes
	// ---------------------------------------------------------------------
	
	/** Uniq id */
	public $id;
	
	// ---------------------------------------------------------------------
	// Constructor
	// ---------------------------------------------------------------------
	
	/** Constructor */
	function __construct() {
			
		// Predicates
		$this->_predicatesDef["id"] = KEYWORD;
		
	}
	
	// ---------------------------------------------------------------------
	// Override
	// ---------------------------------------------------------------------
	
	/** Ignore some fields when populating from a POST Form */
	public function populateFromRequest($except = array()) {
		$except[] = "id";
		parent::populateFromRequest($except);
	}
	
}