<?

define('EXTENDED_PROFILE_PREFIX' , '_extended');
define('STORE_PREFIX' , '_store');

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */

class MainController extends AuthenticatedController {

	//public static $hiddenApplication = array("myMed", "myNCE", "myBEN", "myTestApp", "myMed_old", "myOldEurope", "myTemplate");
	
	
	
	//if user->applicationList is empty
	public static $bootstrapApplication = array("myEurope", "myRiviera", "myFSA", "myMemory", "myBen", "myEuroCIN");
	
	public static $otherApplication = array("myJob", "myConsolato", "myStudent", "myAutoinsieme", "myREADME", "myAngel");

	
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

		// get all the application in MYMED_ROOT . '/application
// 		if ($handle = opendir(MYMED_ROOT . '/application')) {
// 			while (false !== ($file = readdir($handle))) {
// 				if(preg_match("/my/", $file) && !preg_match("/Admin/", $file) && !in_array($file, self::$hiddenApplication)) {
// 					array_push($this->applicationList, $file);
// 				}
// 			}
// 		}
		
// 		$extentedProfile = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);
		
		// get the success/error message (if it exists)
		$this->setSuccess($this->currentSuccessMess);
		$this->setError($this->currentErrorMess);
		
		// setup the extendedProfile if needed
// 		if($extentedProfile == null){
// 			$extentedProfile = new ExtendedProfile($_SESSION['user']->id, self::$bootstrapApplication);
// 			$extentedProfile->storeProfile($this);
// 		}
		// set the status of the applications

		//let's store them in session since we are not in the UK
		if (!isset($_SESSION['applicationList'])){
			if (is_null($myApps = json_decode($_SESSION['user']->applicationList))){
				$myApps = self::$bootstrapApplication;
			}
			
			array_walk( $myApps, array($this, 'setOn'));
			array_walk( self::$otherApplication, array($this, 'setOff'));
		}
// 		$this->resetApplicationStatus();

		
		
		debug_r($_SESSION['applicationList']);

		// REPUTATION
// 		if ($handle = opendir(MYMED_ROOT . '/application')) {
// 			while (false !== ($file = readdir($handle))) {
// 				if(preg_match("/my/", $file) && !preg_match("/Admin/", $file) && !in_array($file, $this->hiddenApplication)) {

		if (!isset($_SESSION['reputation'])){
			
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