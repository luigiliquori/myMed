<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

class SearchController extends AuthenticatedController {
	
	public $part;

	public function handleRequest() {
		
		parent::handleRequest();
		
		$this->part = new Partnership();
		$this->part->setIndex($_GET);
		
		$mapper = new DataMapper;

		try {
			$this->result = $mapper->findByPredicate($this->part);
		} catch (Exception $e) {
			$this->result = array();
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