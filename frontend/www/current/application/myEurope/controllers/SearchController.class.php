<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

class SearchController extends AuthenticatedController {
	
	public $index;
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		$this->index=array();

		$placesAll = array("france", "italy");
		$themes = array();
		$places = array();
		
		foreach( $_GET as $i=>$v ){
			if ($v == "on"){
				if ( in_array($i, $placesAll)){
					array_push($places, $i);
				} else {
					array_push($themes, $i);
				}
			}
		}
		
		$p = preg_split('/[ +]/', $_GET['themes'], NULL, PREG_SPLIT_NO_EMPTY);
		$p = array_map('strtolower', $p);
		$themes = array_unique(array_merge($themes, $p));
		if (count($themes)){
			array_push($this->index, new DataBeanv2("themes", ENUM, $themes));
		}
		
		$p = preg_split('/[ +]/', $_GET['places'], NULL, PREG_SPLIT_NO_EMPTY);
		$p = array_map('strtolower', $p);
		$places = array_unique(array_merge($places, $p));
		if (count($places)){
			array_push($this->index, new DataBeanv2("places", ENUM, $places));
		}

		debug("search on.. ".$_GET['namespace']);
		
		$find = new MatchMakingRequestv2("v2/FindRequestHandler", READ, array("index"=>json_encode($this->index)),
				$_GET['namespace'], $this);
			
		try{
			$result = $find->send();
		}
		catch(Exception $e){
			//return null;
		}

		$this->success = "";
		// Give this to the view
		$this->result = $result;
		$this->suggestions = array();
		
		// Render the view			
		$this->renderView("Results");
		

	}
}
?>