<!--
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
 -->
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