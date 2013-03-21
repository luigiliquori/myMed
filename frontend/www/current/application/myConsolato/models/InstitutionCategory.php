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
 *  Categories in the tree of Institution publications
 */
class InstitutionCategory extends InstitutionTreeNode {
	
	// ---------------------------------------------------------------------
	// Static method
	// ---------------------------------------------------------------------
	
	/** 
	 * Static getter. 
	 * $id = "root" return empty root category.
	 **/
	public static function getByID($id) {
		if ($id == "root") {
			// Dummy empty "root" category. 
			$cat = new InstitutionCategory();
			$cat->id = "root";
			return $cat;
		} else {
			return 	MyConsolatoPublication::getByIDGeneric($id, "InstitutionCategory");
		}
	}
	
	// ---------------------------------------------------------------------
	// Attributes
	// ---------------------------------------------------------------------
	
	/** Cache of children */
	protected $_childCategories = null;
	protected $_childArticles = null;
	
	// ---------------------------------------------------------------------
	// Constructor
	// ---------------------------------------------------------------------
	
	/** Construct */
	public function __construct() {
		
		parent::__construct();
		
		// Namespace
		$this->NAMESPACE = "InstitutionCategory";
	}
	
	// ---------------------------------------------------------------------
	// Methods
	// ---------------------------------------------------------------------
	
	public function getChildCategories() {
		
		if ($this->_childCategories == null) {
			// Find all nodes with this article as parent
			$req = new InstitutionCategory();
			$req->parentID = $this->id;
			$this->_childCategories = $req->find();
		}	
		return $this->_childCategories;
	}
	
	public function getChildArticles() {
	
		if ($this->_childArticles == null) {
			// Find all nodes with this article as parent
			$req = new InstitutionArticle();
			$req->parentID = $this->id;
			$this->_childArticles = $req->find();
		}
		return $this->_childArticles;
	}
	
}
?>