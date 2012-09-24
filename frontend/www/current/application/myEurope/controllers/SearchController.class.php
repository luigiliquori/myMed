<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

class SearchController extends AuthenticatedController {
	
	public $part;

	public function handleRequest() {
		
		parent::handleRequest();
		
		$this->part = new Partnership();
		$this->part->initSearch($_GET);

		try {
			$this->result = $this->part->search();
		} catch (Exception $e) {
		}
		
		$this->suggestions = array();
		
		function addvaluelashes($o){
			$o->value = addslashes($o->value);
			return $o;
		}
		$this->part->index = array_map("addvaluelashes", $this->part->index); //for ajax subscribe

		// Render the view			
		$this->renderView("Results");
		

	}
	
	function smallWords($w){
		return strlen($w) > 2;
	}
	
	
}
?>