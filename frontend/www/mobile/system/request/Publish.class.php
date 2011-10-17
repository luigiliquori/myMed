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
		// construct the predicate + data
		$predicateArray;
		$data = array();
		$numberOfPredicate = 0;
		for($i=0 ; $i<$_POST['numberOfOntology'] ; $i++){
			/*MDataBean*/ $ontology = json_decode(urldecode($_POST['ontology' . $i]));
			$ontology->value = $_POST[$ontology->key];
			if($ontology->ontologyID < 4 && $ontology->value != "") { // it's a predicate
				$predicateArray[$numberOfPredicate++] = $ontology;
			}
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
