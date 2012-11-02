<?

/**
 * This controller is the main controller of the statistics application.
 * it shows the statistics about publish and subscribe in myMed applications
 *
 */ 

//require(MYMED_ROOT."/application/myMed/controllers/MainController.class.php");
require_once MYMED_ROOT. '/lib/dasp/request/Request.class.php';


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
			$response=$this->sendRequestToBackend($_POST['select-method'], $_POST['select-year'], $_POST['select-month'], $_POST['select-application']);
			$this->tabrep = $this->analyzeBackendResponse($response);
		}
		$this->renderView("main");
	}

	function generateGraph($response){
		echo "generate graph";
		$this->array_resp_return = array();
		$this->max_array_value=0;
		$resp_obj = json_decode($response);
		$tab = $resp_obj->statistics;
		if((isset($_POST['select-month'])&& $_POST['select-month']!= "all") || (isset($_POST['select-year']) && $_POST['select-year']!="all")){
			$i=0;
			foreach($tab as $val => $value){
				if($i==$val){
					$array_resp_return[$i] = $value;
					if($value>$max_array_value){
						$max_array_value=$value;
					}
				}
				else{
					$array_resp_return[$i]=0;
				}
				$i++;
			}
		}
		
		//My brain to me :"use JSON"
		//Yep good idea brain
		//$this->response = "{\"name\" : \"curve1\",\"type\" : \"month\", \"curve\" : \"[[1,5000],[2,5500],[3,10000],[4,500]]\"}"
	}
	
	function sendRequestToBackend($method,$year,$month,$application){
		$request = new Request("StatisticsRequestHandler", READ);
		//$request->addArgument("accessToken",$_SESSION['user']->session);
		$request->addArgument("accessToken",$_SESSION['accessToken']);
		echo "Access token= ".$_SESSION['accessToken'];
		$request->addArgument("code",1);
		$request->addArgument("application",$application);
		$request->addArgument("method",$method);
		if($year != "all"){
			$request->addArgument("year",$year);
		}
		if($month != "all"){
			$request->addArgument("month",$month);
		}
		
		return $request->send();
		
	}
	
	function analyzeBackendResponse($response){
		echo "resp obj= ".$response;
		$resp_obj = json_decode($response);
		if($resp_obj->status != 500){
			generateGraph($resp->dataObject);
		}
	}
}
?>