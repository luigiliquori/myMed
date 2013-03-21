<?

class ListBenevolesController extends ProfileNiceBenevolatRequired {

	
	/** @var ProfileAssociation[] */
	public $benevoles = array();
	
	/** Processed when argument "method" is missing */
	function defaultMethod() {
		
		// Set the filter 
		if (in_request("filter")) {
			$this->filter = $_REQUEST['filter'];
		}
		
		// Query
		$query = new ProfileBenevole();
		
		// XXX Hack : Search for all missions types to all it all
		$query->missions = array_keys(CategoriesMissions::$values);
		$this->benevoles = $query->find();
		
		// The view
		$this->renderView("listBenevoles");
		
		
		
	}
}
?>