<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

class SearchController extends AuthenticatedController {
	
	public $index;
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		$this->index=array();

		$themes = array();
		$regs = array();
		
		foreach( $_GET as $i=>$v ){
			if ($v == "on"){
				if ( strpos($i, "theme") === 0){
					array_push($themes, $i);
				} else if  ( strpos($i, "reg") === 0){
					array_push($regs, $i);
				}
			}
		}
		if (count($themes)){
			array_push($this->index, new DataBeanv2("theme", ENUM, $themes));
		}
		
		if (count($regs)){
			array_push($this->index, new DataBeanv2("reg", ENUM, $regs));
		}
		$tags = preg_split('/[ +]/', $_GET['q'], NULL, PREG_SPLIT_NO_EMPTY);
		$p = array_unique(array_map('strtolower', $tags));
		if (count($p)){
			array_push($this->index, new DataBeanv2("tags", ENUM, $p));
		}
		
		debug("search on.. ".$_GET['namespace']);
		$find = new FindRequestv2($this, $_GET['namespace'], $this->index);
			
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