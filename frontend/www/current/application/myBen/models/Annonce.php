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