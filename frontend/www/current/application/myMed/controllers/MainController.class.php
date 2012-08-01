<?

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class MainController extends AuthenticatedController {

	public $hiddenApplication = array("myMed", "myNCE", "myBEN", "myTestApp", "myMed_old");
	public $reputation = array();

	public function handleRequest() {

		parent::handleRequest();

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
		$this->renderView("main");
	}


}
?>