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