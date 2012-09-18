<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

class SearchController extends ExtendedProfileRequired {
	
	public $part;

	public function handleRequest() {
		
		parent::handleRequest();
		
		$this->part = new Partnership($this);
		$this->part->initSearch($_GET);

		try{
			$this->result = $this->part->search();
		}catch (NoResultException $e) {

		}catch(Exception $e){
		}
		$this->success = "";
		
		$this->suggestions = array();
		function addvaluelashes($o){
			$o->value = addslashes($o->value);
			return $o;
		}

		$this->index = array_map("addvaluelashes", $this->part->index); //for ajax subscribe

		// Render the view			
		$this->renderView("Results");
		

	}
	
	function smallWords($w){
		return strlen($w) > 2;
	}
	
	
}
?>