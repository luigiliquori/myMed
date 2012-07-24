<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

class SearchController extends AuthenticatedController {
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		$index=array();

		$themes = array();
		$regs = array();
		
		foreach( $_GET as $i=>$v ){
			if ($v == "on"){
				if ( strpos($i, "theme") === 0){
					array_push($themes, substr($i, count("theme")));
				} else if  ( strpos($i, "reg") === 0){
					array_push($regs, substr($i, count("reg")));
				}
			}
		}
		if (count($themes)){
			array_push($index, new DataBeanv2("theme", ENUM, $themes));
		}
		
		if (count($regs)){
			array_push($index, new DataBeanv2("reg", ENUM, $regs));
		}
		
		debug("search on.. ".$_GET['namespace']);
		$find = new FindRequestv2($this, $_GET['namespace'], $index);
			
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