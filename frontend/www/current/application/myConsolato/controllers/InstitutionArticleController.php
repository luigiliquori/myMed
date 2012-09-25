<?php

/** Main view */
class InstitutionArticleController extends InstitutionNodeController {
	
	// ---------------------------------------------------------------------
	// Constructor
	// ---------------------------------------------------------------------
	
	public function __construct() {
		parent::__construct(
				"InstitutionArticle", // Name of the class 
				"article"); // Name of the bean for the view  
	}
	
	
}