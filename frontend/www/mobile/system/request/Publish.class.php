<?php
require_once 'system/request/Request.class.php';
require_once 'system/handler/IRequestHandler.php';
require_once 'system/beans/MDataBean.class.php';

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
		$data = array();
		$askForMobilePicture = false;
		$numberOfPredicate = 0;

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
			$data[$i] = $ontology;
		}

		// Construct the requests
		$application = $_POST['application'];
		$user = json_encode($_SESSION['user']);
		$predicate = json_encode($predicateArray);
		$data = json_encode($data);
		
		if (TARGET == "desktop") {
			$responsejSon = parent::send();
			parent::addArgument("application", $application);
			parent::addArgument("user", $user);
			parent::addArgument("predicate", $predicate);
			parent::addArgument("data", $data);
			$responseObject = json_decode($responsejSon);
				
			if($check->error == null) {
				$this->handler->setSuccess("Request sent!");
			}
		} else { // TARGET == "mobile"
			header("Refresh:0;url=mobile_binary:publish:" . $application . ":" . $user . ":" . $predicate . ":" . $data);
		}
	}
}
?>
