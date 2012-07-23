<?
class ListAnnoncesController extends ExtendedProfileRequired {
		
	/** @var Annonce[] */
	public $annonces;
	
	/** Processed when argument "method" is missing */
	function defaultMethod() {
		
		// Build a query
		$annonceQuery = new Annonce();
		
		
		if ($this->extendedProfile instanceof ProfileAssociation) {
	
			// If logged as an association, show the list of annonces for this association
			$annonceQuery->associationID = $this->extendedProfile->userID;
			
		} else {
			
			// Show the matching
			
		}
		
		// Search the corersponding annonces
		$this->annonces = $annonceQuery->find();
		
		$this->renderView("listAnnonces");
		
	}


}
?>