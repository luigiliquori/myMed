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
		parent::addArgument("application", $_POST['application']);
		parent::addArgument("data", json_encode($data));
		parent::addArgument("user", json_encode($_SESSION['user']));

		// Broadcast predicate algorithm
		for($i=1 ; $i<pow(2, $numberOfPredicate) ; $i++){
			$mask = $i;
			$predicate = "";
			$j = 0;
			while($mask > 0){
				if($mask&1 == 1){
					$predicate .= $predicateArray[$j]->key . "(" . $predicateArray[$j]->value . ")";
				}
				$mask >>= 1;
				$j++;
			}
			if($predicate != ""){
				// 				echo '<script type="text/javascript">alert("$predicate = ' . $predicate . '")</script>';
				parent::addArgument("predicate", urlencode($predicate));

				$responsejSon = parent::send();
				$responseObject = json_decode($responsejSon);

				if($responseObject->status != 200) {
					// 					echo '<script type="text/javascript">alert("$responseObject->status = ' . $responseObject->status . '")</script>';
					$this->handler->setError($responseObject->description);
					return;
				}
			}
		}

		if($check->error == null) {
			$this->handler->setSuccess("Request sent!");
		}
	}
}
?>
