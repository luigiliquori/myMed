<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
<?

/** 
 *  A node in the tree of categories/articles
 */
abstract class InstitutionTreeNode extends MyConsolatoPublication {
	
	// ---------------------------------------------------------------------
	// Attributes
	// ---------------------------------------------------------------------
	
	/** Parent node */
	public $parentID;
	
	/** Short title */
	public $title;
	
	/** Short description (wrapped) */
	public $desc;

	/** Cache of parent */
	protected $_parent;
	
	// ---------------------------------------------------------------------
	// Constructor
	// ---------------------------------------------------------------------
	
	/** Construct */
	public function __construct() {
		
		parent::__construct();
			
		// Predicates
		$this->_predicatesDef["parentID"] = KEYWORD; 
		
		// Wrapped
		$this->_wrapDef[] = "title";
		$this->_wrapDef[] = "desc";
	}
	
	// ---------------------------------------------------------------------
	// Methods
	// ---------------------------------------------------------------------
	
	public function getParent() {
		if ($this->_parent == null) {
			$this->_parent = InstitutionCategory::getByID($this->parentID);
		}
		return $this->_parent;
	}
	
}
?>