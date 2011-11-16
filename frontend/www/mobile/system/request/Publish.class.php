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
// 		parent::setMultipart(true);
		$this->handler	= $handler;
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {
		$predicateArray;
		$dataArray;
		$askForMobilePicture = false;
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
			$params = array(
				 	  	  'code'=>'0',
						  'application'=>$application,
						  'user'=>$user,
						  'predicate'=>$predicate,
						  'data'=>$data
			);
				
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_URL, BACKEND_URL . "/PublishRequestHandler");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
				
			// SSL CONNECTION
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // see address in config.php
			curl_setopt($ch, CURLOPT_CAINFO, "/local/mymed/backend/WebContent/certificate/mymed.crt"); // TO EXPORT FROM GLASSFISH!
				
			$responsejSon = curl_exec($ch);
			curl_close($ch);
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
			. urlencode($data));
			
			$this->handler->setSuccess("ok");
		}
	}
}
?>
