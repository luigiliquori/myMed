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
<?php

/** Profile for an association */
class ProfileBenevole extends AbstractProfile {
	
	// ------------------------------------------------
	// Attributes
	// ------------------------------------------------
	
	/** Key:ENUM:competences */
	public $competences;
	
	/** Key:ENUM:typeMissions */
	public $missions;
	
	/** Key:ENUM:typeMissions */
	public $disponibilites;
	
	/** Key:ENUM:mobilite */
	public $mobilite;
	
	/** Data */
	public $tel;
	public $sexe;
	public $situation;
	public $subscribe = true;
	
	// ------------------------------------------------
	// Constructor
	// ------------------------------------------------
	
	function __construct() {
		
		// The namespace
		$this->NAMESPACE = "ProfileBenevole";
		
		// Predicates (keys)
		$this->_predicatesDef = array(
				"userID" => KEYWORD,
				"competences" => ENUM,
				"missions" => ENUM,
				"mobilite" => ENUM);
		
		// Data attributes
		$this->_dataDef = array(
				"tel" => KEYWORD,
				"sexe" => ENUM,
				"disponibilites" => ENUM,
				"situation" => ENUM,
				"subscribe" => KEYWORD);
		
		// Wraped
		$this->_wrapDef = array("name");
		
	}
	
	/** 
	 * 	Return the annonce query corresponding to the criterias of this benevole.
	 * 	@return Annonce 
	 */
	public function getAnnonceQuery() {
		$annonce = new Annonce();
		$annonce->competences = $this->competences;
		// $annonce->typeMission = $this->missions;
		$annonce->quartier = $this->mobilite;
		return $annonce;
	}
	
	/** 
	 *  If subscription is activated, subscribe to announces before publishing the new profile
	 */
	public function publish() {
		parent::publish();
	}
	
	/** @Override */
	public function populateFromRequest($except=array()) {
		
		// Do not populate the "subscribe" field, as it is a checkbox and is missing if not checked
		array_push($except, "subscribe");
		
		parent::populateFromRequest($except);
		
		// Fill the subscribe attribute
		$this->subscribe = in_request("subscribe");
		
		// Add the option "undef" to "mobilite"
		if (!in_array("undef", $this->mobilite)) {
			array_push($this->mobilite, "undef");
		}
			
	}
	
	// ----------------------------------------------------------------------
	// Static methods
	// ----------------------------------------------------------------------
	
	/** @return ProfileBenevole */
	public static function getFromUserID($userID) {
		$query = new ProfileBenevole();
		$query->userID = $userID;
		
		$res = $query->find();
		
		if (sizeof($res) != 1) {
			throw new InternalError(
					"Expected 1 benevole profile with id=$userID, but got" . sizeof($res));
		}
		
		$res[0]->getDetails();
		
		return $res[0];
	}
	
	
}