<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

class SearchController extends AuthenticatedController {
	
	public $index;
	
	public $themesall = array(
			"education",
			"travail",
			"entreprise",
			"environnement",
			"agriculture",
			"peche",
			"recherche",
			"santé"
	);
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		$this->index=array();

		global $themesall;
		$themes = array();
		$places = array();
		
		foreach( $_GET as $i=>$v ){
			if ($v == "on"){
				if ( in_array($i, $this->themesall)){
					array_push($themes, $i);
				} else {
					array_push($places, $i);
				}
			}
		}
		
		array_push($this->index, new DataBeanv2("themes", ENUM, join("|",$themes)));
		
		array_push($this->index, new DataBeanv2("places", ENUM, join("|",$places)));
		
		$p = preg_split('/[ ,+:-]/', $_GET['keywords'], NULL, PREG_SPLIT_NO_EMPTY);
		$p = array_map('strtolower', $p);
		$p = array_filter($p, array($this, "smallWords"));
		$p = array_unique($p);
		array_push($this->index, new DataBeanv2("keyword", ENUM, join("|",$p)));
	

		debug("search on.. ".$_GET['namespace']);
		
		$find = new RequestJson($this,
				array("application"=>APPLICATION_NAME.":".$_GET['namespace'], "predicates"=>$this->index));
		
		try{
			$res = $find->send();
		}
		catch(Exception $e){
			//return null;
			$this->result = array();
		}
		
		$this->success = "";

		if (isset($res))
			$this->result = $res->results;
		
		$this->suggestions = array();
		
		// Render the view			
		$this->renderView("Results");
		

	}
	
	function smallWords($w){
		return strlen($w) > 2;
	}
}
?>