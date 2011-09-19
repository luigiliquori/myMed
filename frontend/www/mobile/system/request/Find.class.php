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
		for($i=0 ; $i<$_POST['numberOfOntology'] ; $i++){
			/*MDataBean*/ $ontology = json_decode(urldecode($_POST['ontology' . $i]));
			$ontology->value = $_POST[$ontology->key];
			if($ontology->ontologyID < 4) {
				// it's a predicate
				$predicateArray[$numberOfPredicate++] = $ontology;
			}
		}
		
		// Construct the requests
		parent::addArgument("application", $_POST['application']);
		
		// Broadcast predicate algorithm
		$result = array();
		for($i=1 ; $i<pow(2, $numberOfPredicate) ; $i++){
			$mask = $i;
			$predicate = "";
			$j = 0;
			while($mask > 0){
				if($mask&1 == 1){
					$predicate .= $predicateArray[$j]->value;
				}
				$mask >>= 1;
				$j++;
			}
			if($predicate != ""){
// 				echo '<script type="text/javascript">alert("$predicate = ' . $predicate . '")</script>';
				parent::addArgument("predicate", $predicate);
				$response = parent::send();
				$check = json_decode($response);
				if($check->error == null) {
 					$this->handler->setSuccess($response); // TODO CONCATENATION!!!!!
 					break;
				}
			}
		}
	}
}
?>