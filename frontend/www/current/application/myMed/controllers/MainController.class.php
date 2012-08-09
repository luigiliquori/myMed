<?

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class MainController extends AuthenticatedController {

	public static $hiddenApplication = array("myMed", "myNCE", "myBEN", "myTestApp", "myMed_old", "myOldEurope");
	public static $bootstrapApplication = array("myRiviera", "myFSA", "myEurope", "myMemory", "myBen", "myEuroCIN");
	
	public $applicationList = array();
	public $applicationStatus = array();

	public $reputation = array();

	public function __construct() {
		
		// get all the application in MYMED_ROOT . '/application
		if ($handle = opendir(MYMED_ROOT . '/application')) {
			while (false !== ($file = readdir($handle))) {
				if(preg_match("/my/", $file) && !preg_match("/Admin/", $file) && !in_array($file, self::$hiddenApplication)) {
					array_push($this->applicationList, $file);
				}
			}
		}
		
		$extentedProfile = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);
		// setup the extendedProfile if needed
		if($extentedProfile == null){
			$extentedProfile = new ExtendedProfile($_SESSION['user']->id, self::$bootstrapApplication);
			$extentedProfile->storeProfile($this);
		} 
		// set the status of the applications
		$this->resetApplicationStatus();
		foreach ($extentedProfile->applicationList as $app){
			$this->applicationStatus[$app] = "on";
		}
		
	}
	
	function resetApplicationStatus(){
		$this->applicationStatus = array();
		foreach($this->applicationList as $app) {
			$this->applicationStatus[$app] = "off";
		}
	}

	public function handleRequest() {

		parent::handleRequest();
		
		// Set the flag
		$_SESSION["launchpad"] = true;

		// Get the reputation of the user in each the application
		if ($handle = opendir(MYMED_ROOT . '/application')) {
			while (false !== ($file = readdir($handle))) {
				if(preg_match("/my/", $file) && !preg_match("/Admin/", $file) && !in_array($file, $this->hiddenApplication)) {
					// REPUTATION
					$request = new Request("ReputationRequestHandler", READ);
					$request->addArgument("application",  $file);
					$request->addArgument("producer",  $_SESSION['user']->id);				// Reputation of data
					$request->addArgument("consumer",  $_SESSION['user']->id);
					
					$responsejSon = $request->send();
					$responseObject = json_decode($responsejSon);
					
					if(isset($responseObject->data->reputation)){
						$i=0;
						$value = json_decode($responseObject->data->reputation);
						$this->reputation[$file] = $value * 100;
					}
				}
			}
		}
		
		// Set the applicationList of the user
		if(isset($_REQUEST['applicationStore']) && isset($_REQUEST['status'])) {
			$extentedProfile = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);
			$appList = $extentedProfile->applicationList;
			$i=0;
			print_r($extentedProfile->applicationList);
			while ($i < sizeof($appList)) {
				if($appList[$i] == $_REQUEST['applicationStore']){
					if($_REQUEST['status'] == "off") {
						unset($appList[$i]);
						$this->setSuccess("L'application " . $_REQUEST['applicationStore'] . " est maintenant désactivée");
					} else {
						break;
					}
				}
				$i++;
			}
			if($i==sizeof($appList) && $_REQUEST['status'] == "on") {
				array_push($appList, $_REQUEST['applicationStore']);
				$this->setSuccess("L'application " . $_REQUEST['applicationStore'] . " est maintenant activée");
			}
			$extentedProfile = new ExtendedProfile($_SESSION['user']->id, $appList);
			$extentedProfile->storeProfile($this);
			
			// set the status of the applications
			$this->resetApplicationStatus();
			foreach ($extentedProfile->applicationList as $app){
				$this->applicationStatus[$app] = "on";
			}
			
		}
		
		$this->renderView("main");
	}


}
?>