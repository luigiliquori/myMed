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