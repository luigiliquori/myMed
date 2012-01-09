<?php
require_once 'lib/dasp/request/Request.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';
require_once 'system/templates/handler/IRequestHandler.php';

/**
 *
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class Publish extends Request {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(/*IRequestHandler*/ $handler) {
		parent::__construct("PublishRequestHandler", CREATE);
		$this->handler	= $handler;
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {
		$predicateArray;
		$dataArray;
		$numberOfPredicate = 0;
		$numberOfOntology = 0;
		
		// construct the predicate + data
		for($i=0 ; $i<$_POST['numberOfOntology'] ; $i++){
			/*MDataBean*/ $ontology = json_decode(urldecode($_POST['ontology' . $i]));
			
			if ($ontology->ontologyID == PICTURE) {
				if (TARGET == "desktop") {
					$ontology->value = "not implemented yet...";
					// TODO upload the picture with the rest
				} else {
					continue; // The file upload will be managed by the binary 
				}
			} else {
				$ontology->value = $_POST[$ontology->key];
			}
			// construct the predicate
			if($ontology->ontologyID < 4 && $ontology->value != "") {
				$predicateArray[$numberOfPredicate++] = $ontology;
			}
				
			// construct the data
			$dataArray[$numberOfOntology++] = $ontology;
		}

		// Construct the requests
		$application = $_POST['application'];
		$user = json_encode($_SESSION['user']);
		$predicate = json_encode($predicateArray);
		$data = json_encode($dataArray);
		
		if (TARGET == "desktop") {
			
			parent::addArgument("application", $application);
			parent::addArgument("user", $user);
			parent::addArgument("predicate", $predicate);
			parent::addArgument("data", $data);
			
			$responsejSon = parent::send();
			$responseObject = json_decode($responsejSon);
			
			if($responseObject->status != 200) {
				$this->handler->setError($responseObject->description);
			} else {
				$this->handler->setSuccess($responseObject->description);
			}
			
		} else { // TARGET == "mobile"
			header("Refresh:0;url=mobile_binary". MOBILE_PARAMETER_SEPARATOR 
			."publish" . MOBILE_PARAMETER_SEPARATOR 
			. urlencode($application) . MOBILE_PARAMETER_SEPARATOR 
			. urlencode($user) . MOBILE_PARAMETER_SEPARATOR 
			. urlencode($predicate) . MOBILE_PARAMETER_SEPARATOR 
			. urlencode($data) . MOBILE_PARAMETER_SEPARATOR 
			. urlencode($_SESSION['accessToken']));
			
		}
	}
}
?>
