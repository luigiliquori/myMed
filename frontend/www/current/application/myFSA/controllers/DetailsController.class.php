<? 
class DetailsController extends AuthenticatedController {
	
	//public /*String*/ $user; //not needed because is taken from session
	public /*String*/ $author;//consumer
	public /*String*/ $predicate;
	public /*String*/ $feedback;
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] == "Reputation") {
			$this->feedback = $_REQUEST['rate'];
			$this->storeReputation();
				
// 			$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
// 			$debugtxt  .= var_export($_REQUEST['rate'], TRUE);
// 			$debugtxt  .= var_export($_SESSION['user'], TRUE);
// 			$debugtxt .= "</pre>";
// 			$debugtxt  .= var_export(htmlspecialchars($_GET["action"]), TRUE);
// 			debug($debugtxt);
			$this->renderView("main");
		
		}
		else {
			// Get arguments of the query
			$this->predicate = $_GET['predicate'];
			$this->author = $_GET['author'];
			
			// Create an object
			$obj = new PublishObject($this->predicate);
			$obj->publisherID = $this->author;
			
			// Fetches the details
			$obj->getDetails();
				
			// Give this to the view
			$this->result = $obj;
			
			// Render the view
			$this->renderView("details");
			
		}
	}
	public /*void*/ function storeReputation(){
			
		$rep = new Reputation($_SESSION['user'],$this->author,$this->predicate, $this->feedback);
			
		$rep->storeReputation();
			
	}
	
	public /*void*/ function getReputation(){
	
		//ExtendedProfile::getExtendedProfile($_SESSION['user']);
	}
	
	
}
?>