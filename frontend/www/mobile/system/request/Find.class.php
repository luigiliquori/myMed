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
class Find extends Request {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(/*IRequestHandler*/ $handler) {
		parent::__construct("FindRequestHandler", READ);
		$this->handler	= $handler;
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {
		
		// construct the predicate + data
		$predicateArray;
		$numberOfPredicate = 0;
		$predicate = "";
		for($i=0 ; $i<$_POST['numberOfOntology'] ; $i++){
			/*MDataBean*/ $ontology = json_decode(urldecode($_POST['ontology' . $i]));
			$ontology->value = $_POST[$ontology->key];
			if($ontology->ontologyID < 4 && $ontology->value != "") {
				// it's a predicate
				$predicateArray[$numberOfPredicate++] = $ontology;
				$predicate .= $ontology->key . "(" . $ontology->value . ")";
			}
		}
		
		// Construct the requests
		parent::addArgument("application", $_POST['application']);
		
		if(isset($_POST['broadcast'])){		// Broadcast predicate algorithm
			$result = array();
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
					parent::addArgument("predicate", urlencode($predicate));
					
					$responsejSon = parent::send();
					$responseObject = json_decode($responsejSon);
					
					if($responseObject->status == 200) {
						$result = array_merge($result, json_decode($responseObject->data->results));
	// 					echo '<script type="text/javascript">alert("$response = ' . json_decode($response) . '")</script>';
					}
				}
			}
	 		$this->handler->setSuccess(json_encode($result));
		} else {		// Classical matching
			parent::addArgument("predicate", urlencode($predicate));
			
			$responsejSon = parent::send();
			$responseObject = json_decode($responsejSon);
			// echo '<script type="text/javascript">alert("$response = ' . $responsejSon . '")</script>';
			if($responseObject->status != 200) {
				$this->handler->setError($responseObject->description);
			} else {
				$this->handler->setSuccess($responseObject->data->results);
			}
		}
	}
}
?>
