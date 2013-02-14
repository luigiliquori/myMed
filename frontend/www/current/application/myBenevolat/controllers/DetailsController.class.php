<? 
class DetailsController extends AuthenticatedController {
	
	public $reputation = array();
	
	public function handleRequest() {
		
		parent::handleRequest();
		/* Guest access provided */
		if (!(isset($_SESSION['user'])) && $_SESSION['user']->is_guest) {
			$id = rand(100000, 999999);
			$user = (object) array('id'=>'MYMED_'.$id, 'name'=>'user'.$id);
			$_SESSION['user'] = insertUser($user, null, true);
			$_SESSION['acl'] = array('defaultMethod', 'read');
			$_SESSION['user']->is_guest = 1;
		}
		
		$request = new Annonce();
		$request->id = $_GET['id'];
		$res = $request->find();
		
		// Should have only one result
		if (sizeof($res) != 1) {
			throw new InternalError("Expected one result for Annonce(id=$id). Got " . sizeof($res));
		}
		
		$annonce = $res[0];
		
		// Fetches the details
		$annonce->getDetails();
		 
		// Give this to the view
		$this->result = $annonce;
		
		$_SESSION['predicate'] = $annonce->getPredicateStr();
		$_SESSION['author'] = $annonce->publisherID;
		
		// get author reputation
		$request = new Request("ReputationRequestHandler", READ);
		$request->addArgument("application",  APPLICATION_NAME);
		$request->addArgument("producer",  $this->result->publisherID);							
		$request->addArgument("consumer",  $this->result->publisherID);
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
		if (isset($responseObject->data->reputation)) {
			$value =  json_decode($responseObject->data->reputation) * 100;
		} else {
			$value = 100;
		}
		
		// Save the reputation and the number of voters
		$this->reputation["author"] = $value;
		$this->reputation["author_noOfRatings"] = $responseObject->dataObject->reputation->noOfRatings;
		
		// get publication reputation
		$request->addArgument("producer",  $this->result->publisherID);	
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
		if (isset($responseObject->data->reputation)) {
			$value =  json_decode($responseObject->data->reputation) * 100;
		} else {
			$value = 100;
		}
		
		// Save the reputation and the number of ratings
		$this->reputation["value"] = $value;
		$this->reputation["value_noOfRatings"] = $responseObject->dataObject->reputation->noOfRatings;

		$this->search_apply();
		
		// Render the view
		$this->renderView("Details");
	}
	
	public function search_apply() {
		$search_applies = new Apply();
		$this->fillObjApply($search_applies);
		$this->result_apply = $search_applies->find();
	}
	
	private function fillObjApply($obj) {
		$obj->pred1 = 'apply&'.$_SESSION['predicate'].'&'.$_SESSION['author'];
	}
}
?>