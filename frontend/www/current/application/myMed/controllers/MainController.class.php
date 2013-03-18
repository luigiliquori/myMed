<?

define('EXTENDED_PROFILE_PREFIX' , 'extended_profile_');
define('STORE_PREFIX' , 'store_');

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */

class MainController extends AuthenticatedController {

	//bootstrap application in AuthenticatedController I know it's dirty I have to find an other solution for statistics
	//public static $bootstrapApplication = array("myEurope", "myRiviera", "myFSA", "myMemory", "myBen", "myEuroCIN");
	//public static $otherApplication = array("myJob", "myConsolato", "myAutoinsieme", "myREADME", "myAngel", "myStatistics", "myEdu", "myStudent");
	public static $otherApplication = array("myStatistics");
	
// 	public $applicationUrls = array("myEurope"=>"europe", "myRiviera"=>"riviera");
	
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

		
		//parent::handleRequest();
		// Check for user in session
		if ( !isset($_SESSION['user']) ) {
			// Redirect to the main view
			$this->renderView("Splash");
		}
		
		if (strpos($_SERVER["HTTP_USER_AGENT"], "MSIE") !== false) {
			$this->setError(_("You are using Internet Explorer, the interface is not optimized for it,
				please download Chrome or Firefox for a better experience"));
		}

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

		// REPUTATION
		/* Seems to not working to get a list of reputation
		//use that instead @see getReputation()
		$appsRep =  new ReputationSimple(array_keys($_SESSION['applicationList']), array());
		$res = $appsRep->send();
		if($res->status == 200) {
			$_SESSION['apps_reputation'] = formatReputation($res->dataObject->reputation);
			debug_r($_SESSION['apps_reputation']);
		}
		$userRep =  new ReputationSimple(array_keys($_SESSION['applicationList']), array($_SESSION['user']->id));
		$res = $userRep->send();
		if($res->status == 200) {
			$_SESSION['user_reputation'] = formatReputation($res->dataObject->reputation);
			debug_r($_SESSION['user_reputation']);
		}
		*/

		foreach($_SESSION['applicationList'] as $app => $status){
			
			// Get the reputation of the user in each application
			$request = new Request("ReputationRequestHandler", READ);
			$request->addArgument("application",  $app);
			$request->addArgument("producer",  $_SESSION['user']->id);					// Reputation of data
			$request->addArgument("consumer",  $_SESSION['user']->id);
				
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
				
			if (isset($responseObject->data->reputation)) {
				$i=0;
				$value = json_decode($responseObject->data->reputation);
				$_SESSION['reputation'][EXTENDED_PROFILE_PREFIX . $app] = $value * 100;
			} else {
				$_SESSION['reputation'][EXTENDED_PROFILE_PREFIX . $app] = 100;
			}
			
			// Get the reputation of the application
			$request->addArgument("application",  APPLICATION_NAME);
			$request->addArgument("producer",  $app);			// Reputation of data
			$request->addArgument("consumer",  $_SESSION['user']->id);
			
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			
			if (isset($responseObject->data->reputation)) {
				$value = json_decode($responseObject->data->reputation);
				$_SESSION['reputation'][STORE_PREFIX . $app] = $value * 100;
			} else {
				$_SESSION['reputation'][STORE_PREFIX . $app] = 100;
			}
		}

		$this->renderView("main");
	}


}
?>