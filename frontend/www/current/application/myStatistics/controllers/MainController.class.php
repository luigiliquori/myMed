<?

/**
 * This controller is the main controller of the statistics application.
 * it shows the statistics about publish and subscribe in myMed applications
 *
 */

//require(MYMED_ROOT."/application/myMed/controllers/MainController.class.php");

class MainController extends AuthenticatedController {
	
	public static function getBootstrapApplication(){
	 	return MainController::$bootstrapApplication;
	}

	/**
	* Request handler method is calling when we use
	* ?action=main in the address
	*/
	public function handleRequest() {
		parent::handleRequest();
		if(isset($_POST['first-select-curve'])){
			$this->generateGraph();
		}
		$this->renderView("main");
	}

	function generateGraph(){
		echo "generate graph";
		//My brain to me :"use JSON"
		//Yep good idea brain
		$this->response = "{\"name\" : \"curve1\",\"type\" : \"month\", \"curve\" : \"[[1,5000],[2,5500],[3,10000],[4,500]]\"}";
	}
	
	function analyzeBackendResponse($tab_resp){
		foreach(tab_resp as $value => $rep_json){
			$point = json_decode($rep_json);
			if($point->day != ""){
				
			}
			if($point->month !=""){
				
			}
		}
	}
}
?>