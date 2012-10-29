<? 

class SearchController extends ExtendedProfileRequired {

	function defaultMethod() {
		
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
		
		$this->title = "";
		array_walk($this->part->index, array($this, "getValues"));
		if (empty($this->title)){
			$this->title = "all";
		}

		// Render the view			
		$this->renderView("Results");
		
	}
	
	function getValues($o){
		if (!empty($o->value)){
			$this->title .= $o->key.'='.$o->value.' ';
		}
	}
	
}
?>