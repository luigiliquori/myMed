<?

define('EXTENDED_PROFILE_PREFIX' , '_extended');
define('STORE_PREFIX' , '_store');

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */

class MainController extends AuthenticatedController {

	//bootstrap application in AuthenticatedController I know it's dirty I have to find an other solution for statistics
	//public static $bootstrapApplication = array("myEurope", "myRiviera", "myFSA", "myMemory", "myBen", "myEuroCIN");
	public static $otherApplication = array("myJob", "myConsolato", "myStudent", "myAutoinsieme", "myREADME", "myAngel", "myStatistics");

	protected $currentSuccessMess = null;
	protected $currentErrorMess = null;
	
	public function __construct() {
		
	}

	function resetApplicationStatus(){
		$this->applicationStatus = array();
		foreach($this->applicationList as $app) {
			$this->applicationStatus[$app] = "off";
		}
	}
	
	function setOff($app){
		$_SESSION['applicationList'][$app] = "off";
	}
	
	function setOn($app){
		$_SESSION['applicationList'][$app] = "on";
	}

	
	public function handleRequest() {

		parent::handleRequest();

		// Set the flag
		$_SESSION["launchpad"] = true;

		// get the success/error message (if it exists)
		$this->setSuccess($this->currentSuccessMess);
		$this->setError($this->currentErrorMess);
		
		//let's store them in session since we are not in the UK
		if (!isset($_SESSION['applicationList'])){
			if (is_null($myApps = json_decode($_SESSION['user']->applicationList, true))){
				$myApps = self::$bootstrapApplication;
				//store it in the user db profile
			}
			$allaps = array_merge(self::$bootstrapApplication, self::$otherApplication);
			array_walk( $allaps, array($this, 'setOff'));
			array_walk( $myApps, array($this, 'setOn'));
		}

		debug_r($_SESSION['applicationList']);
		debug_r($_SESSION['user3']);
		debug_r($_SESSION['user2']);
		debug_r($_SESSION['user']);

		// REPUTATION
		if (!isset($_SESSION['reputation'])){	// NEED TO REMOVE TO UPDATE THE VALUE WHEN THE REP CHANGE

			foreach($_SESSION['applicationList'] as $app => $status){
				
				// Get the reputation of the user in each the application
				$request = new Request("ReputationRequestHandler", READ);
				$request->addArgument("application",  $app);
				$request->addArgument("producer",  $_SESSION['user']->id);					// Reputation of data
				$request->addArgument("consumer",  $_SESSION['user']->id);
					
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
					
				if (isset($responseObject->data->reputation)) {
					$i=0;
					$value = json_decode($responseObject->data->reputation);
					$_SESSION['reputation'][$app . EXTENDED_PROFILE_PREFIX] = $value * 100;
				} else {
					$_SESSION['reputation'][$app . EXTENDED_PROFILE_PREFIX] = 100;
				}
				
				// Get the reputation of the application
				$request->addArgument("application",  APPLICATION_NAME);
				$request->addArgument("producer",  $app);			// Reputation of data
				$request->addArgument("consumer",  $_SESSION['user']->id);
				
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
				
				if (isset($responseObject->data->reputation)) {
					$value = json_decode($responseObject->data->reputation);
					$_SESSION['reputation'][$app . STORE_PREFIX] = $value * 100;
				} else {
					$_SESSION['reputation'][$app . STORE_PREFIX] = 100;
				}
			}
		}

		$this->renderView("main");
	}


}
?>