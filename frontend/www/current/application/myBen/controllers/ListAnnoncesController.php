<?
class ListAnnoncesController extends ExtendedProfileRequired {
		
	/** @var Annonce[] */
	public $annonces;
	
	/** Processed when argument "method" is missing */
	function defaultMethod() {
		
		// Build a query
		$annonceQuery = new Annonce();
		
		debug_r($this->extendedProfile);
		
		if ($this->extendedProfile instanceof ProfileNiceBenevolat) {
			
			// Nice benevolat => Select all 
			// XXX hack : Select all types of missions
			$annonceQuery->quartier = array_keys(CategoriesMobilite::$values);
			
		} elseif ($this->extendedProfile instanceof ProfileAssociation) {
	
			// If logged as an association, show the list of annonces for this association
			$annonceQuery->associationID = $this->extendedProfile->userID;
			
		} elseif ($this->extendedProfile instanceof ProfileBenevole) {
			
			// Filter the annonces to the ones corresponding to the query
			$annonceQuery = $this->extendedProfile->getAnnonceQuery();
			
		} else {
			throw new InternalError("Should not happen");
		}
		
		// Search the corersponding annonces
		$this->annonces = $annonceQuery->find();
		
		$this->renderView("listAnnonces");
		
	}


}
?>