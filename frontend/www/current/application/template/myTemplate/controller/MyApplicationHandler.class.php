<?php 
require_once '../../../lib/dasp/request/PublishRequest.class.php';
require_once '../../../lib/dasp/beans/DataBean.class.php';
require_once '../../../lib/dasp/beans/OntologyBean.class.php';

require_once '../../../lib/dasp/request/Find.class.php';
require_once '../../../lib/dasp/request/Delete.class.php';
require_once '../../../lib/dasp/request/GetDetail.class.php';
require_once '../../../lib/dasp/request/Subscribe.class.php';
require_once '../../../lib/dasp/request/StartInteraction.class.php';
require_once '../../../lib/dasp/request/Request.class.php';

/**
 * 
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class MyApplicationHandler implements IRequestHandler {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*string*/ $error;
	private /*string*/ $success;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
		$this->handleRequest();
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function handleRequest() { 
		if(isset($_POST['method'])) {
			if($_POST['method'] == "publish") {
				
				/*
				 * Create a DataBean with all the POST datas
				 */
				
				$predicates = array();
				$datas = array();
				$numberOfPredicate = 0;
				$numberOfOntology = 0;
				
				
				// construct the predicate + data
				for($i=0 ; $i<$_POST['numberOfOntology'] ; $i++){
				
					/*OntologyBean*/ $ontology = json_decode(urldecode($_POST['ontology' . $i]));
				
					if(isset($_POST[$ontology->key])) {
						$ontology->value = $_POST[$ontology->key];
					}
					
				
					// construct the predicate
					if($ontology->ontologyID < 4 && $ontology->value != "") {
						$predicates[$numberOfPredicate++] = $ontology;
					}
				
					// construct the data
					$datas[$numberOfOntology++] = $ontology;
				}
				
				
				$dataBean = new DataBean($predicates, $datas);
				
				
				
				$publish = new Publish($this, $dataBean);
				return $publish->send();
			} else if($_POST['method'] == "subscribe") {
				
				
				
				
				$subscribe = new Subscribe($this);
				return $subscribe->send();
			} else if($_POST['method'] == "find") {
				$find = new Find($this);
				return $find->send();
			} else if($_POST['method'] == "getDetail") {
				$getDetail = new GetDetail($this);
				return $getDetail->send();
			} else if($_POST['method'] == "delete") {
				$delete = new Delete($this);
				return $delete->send();
			} else if($_POST['method'] == "startInteraction") {
				$startInteraction = new StartInteraction($this);
				return $startInteraction->send();
			} 
		} 
	}
	
	/* --------------------------------------------------------- */
	/* Getter&Setter */
	/* --------------------------------------------------------- */
	public /*String*/ function getError(){
		return $this->error;
	}
	
	public /*void*/ function setError(/*String*/ $error){
		return $this->error = $error;
	}
	
	public /*String*/ function getSuccess(){
		return $this->success;
	}
	
	public /*void*/ function setSuccess(/*String*/ $success){
		return $this->success = $success;
	}
	
}
?>