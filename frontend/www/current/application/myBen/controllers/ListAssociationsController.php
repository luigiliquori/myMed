<?

define("ASS_ALL", "all");
define("ASS_INVALID", "invalid");

class ListAssociationsController extends ProfileAssociationRequired {
	
	/** @var ProfileAssociation[] */
	public $associations = array();
	public $filter = ASS_ALL;
	
	/** Processed when argument "method" is missing */
	function defaultMethod() {
		
		// Set the filter 
		if (in_request("filter")) {
			$this->filter = $_REQUEST['filter'];
		}
		
		// Query
		$query = new ProfileAssociation();
		
		// Build a query
		if ($this->filter == ASS_ALL) {

			$query->valid = "true";
			$this->associations = $query->find();
		}
		
		// XXX Hack : search valid and not valid associations to have it all
		$query->valid = "false";
		$this->associations = array_merge($this->associations, $query->find());
		
		// The view
		$this->renderView("listAssociations");
		
	}
}
?>