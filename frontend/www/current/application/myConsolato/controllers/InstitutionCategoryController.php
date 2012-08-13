<?php

/** Main view */
class InstitutionCategoryController extends InstitutionNodeController {
	
	// ---------------------------------------------------------------------
	// Constructor
	// ---------------------------------------------------------------------
	
	public function __construct() {
		parent::__construct(
				"InstitutionCategory", // Name of the class 
				"category"); // Name of the bean for the view  
	}
	
	// ---------------------------------------------------------------------
	// Actions
	// ---------------------------------------------------------------------
	
	/** Show the root cateogry by default */
	public function defaultMethod() {
		$this->show("root");
	}
	
	
}