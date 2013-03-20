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
<?

define("ANN_ALL", "all");
define("ANN_VALID", "valid");
define("ANN_PAST", "past");
define("ANN_CRITERIA", "criteres");

class ListAnnoncesController extends GuestOrUserController {
		
	/** @var Annonce[] */
	public $annonces;
	public $filter = ANN_VALID;
	
	/** Processed when argument "method" is missing */
	function defaultMethod() {
		
		// We need a token (guest one maybe)
		$this->getToken();
		
		// Get the filter
		if (in_request('filter')) {
			
			$this->filter = $_REQUEST['filter'];
			
		} else if ($this->extendedProfile instanceof ProfileBenevole) {
	
			// By default, benevoles show their requests
			$this->filter = ANN_CRITERIA;
			
		} 
		
		// Build a query
		$annonceQuery = new Annonce();
		
		// Select all 
		// XXX hack : Select all types of missions
		$annonceQuery->quartier = array_keys(CategoriesMobilite::$values);
		
		// Search the criterias of a benevole
		if ($this->extendedProfile instanceof ProfileBenevole && $this->filter == ANN_CRITERIA) {
			
			// Filter the annonces to the ones corresponding to the query
			$annonceQuery = $this->extendedProfile->getAnnonceQuery();
			
		} 
		
		// For an association, search only the annonces created by them
		if ($this->getUserType() == USER_ASSOCIATION) {	
			// Filter the annonces to the ones corresponding to the query
			$annonceQuery->associationID = $this->extendedProfile->userID;		
		}
				
		if ($this->filter == ANN_VALID) {
			$annonceQuery->promue = "false";
		}
		
		// Search the corresponding annonces
		$this->annonces = $annonceQuery->find();
		
		// Filter the active/past ones
		$this->annonces = array_filter($this->annonces, array($this, "filter"));
		
		$this->renderView("listAnnonces");
		
	}
	
	/** Filter method */
	function filter($annonce) {
		if ($this->filter == ANN_ALL) return true;
		$res = ($annonce->isPassed() || is_true($annonce->promue));
		if ($this->filter != ANN_PAST) $res = !$res;
		return $res;
	}
	
	/** Can you post new annonces ? */
	function canPost() {
		return (($this->extendedProfile instanceof ProfileNiceBenevolat) || 
			($this->extendedProfile instanceof ProfileAssociation));
	}


}
?>