<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

class SearchController extends ExtendedProfileRequired {
	
	public $index;

	public function handleRequest() {
		
		parent::handleRequest();
		$this->namespace = "part";
		$this->index=array();
		
		$this->themes = array();
		$this->places = array();
		$this->roles = array();
		
		debug_r($_GET);
		$cats = array_keys(Categories::$roles);
		$t = array_keys(Categories::$themes);
		foreach( $_GET as $i=>$v ){
			if ($v == "on"){
				if ( in_array($i, $t)){
					array_push($this->themes, $i);
				} else if ( in_array($i, $cats)){
					array_push($this->roles, $i);
				} else {
					array_push($this->places, $i);
				}
			}
		}
		
		array_push($this->index, new DataBeanv2("themes", ENUM, join("|",$this->themes)));
		
		array_push($this->index, new DataBeanv2("places", ENUM, join("|",$this->places)));
		
		if (!empty($this->roles))
			array_push($this->index, new DataBeanv2("cats", ENUM, join("|",$this->roles)));
		
		if ($_GET['call']!="")
			array_push($this->index, new DataBeanv2("call", ENUM, $_GET['call']));

		$p = preg_split('/[ ,+:-]/', $_GET['keywords'], NULL, PREG_SPLIT_NO_EMPTY);
		$p = array_map('strtolower', $p);
		$p = array_filter($p, array($this, "smallWords"));
		$this->p = array_unique($p);
		array_push($this->index, new DataBeanv2("keyword", ENUM, join("|",$this->p)));
		
		
		$find = new RequestJson($this,
				array("application"=>APPLICATION_NAME.":part", "predicates"=>$this->index));
		
		try{
			$res = $find->send();
		}
		catch(Exception $e){}
		$this->result = array();
		$this->success = "";
		
		debug_r($res);

		if (isset($res->results))
			$this->result = (array) $res->results;
		
		$this->suggestions = array();
		function addvaluelashes($o){
			$o->value = addslashes($o->value);
			return $o;
		}
		debug_r($this->index);
		$this->index = array_map("addvaluelashes", $this->index); //for ajax subscribe
		debug_r($this->index);
		// Render the view			
		$this->renderView("Results");
		

	}
	
	function smallWords($w){
		return strlen($w) > 2;
	}
	
	
}
?>