<? 
class DetailsController extends AuthenticatedController {
	
	//public /*String*/ $user; //not needed because is taken from session
	public /*String*/ $author;//consumer
	public /*String*/ $predicate;
	public /*String*/ $feedback;
	public /*String*/ $comments;
	public /*String*/ $users;
	public /*String*/ $pictures;
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		//keyword is set in the jquery file when user set rank on jquery plugin
		if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] == "Reputation") {
			//$this->feedback = 0.5;
			$this->feedback = $_REQUEST['rate']/5;
			$this->storeReputation();
			$this->getMark();
			$this->renderView("main");
		
		}
		else {
			$_SESSION['test']=(isset($_SESSION['disabled']))?true:false;
						
			// Get arguments of the query
			$this->predicate = $_GET['predicate'];
			$_SESSION['predicate'] = $_GET['predicate'];
			$this->author = $_GET['author'];
			
			// Create an object
			$obj = new PublishObject($this->predicate);
			$obj->publisherID = $this->author;
			
			// Fetches the details
			$obj->getDetails();
				
			// Give this to the view
			$this->result = $obj;
			
			if (isset($_SESSION['controller']) && $_SESSION['controller'] == "Search"){
				//needed for comments
				$string = $_GET['predicate'];
				preg_match_all('/pred2([a-z0-9-_]+)pred3/', $string, $matches);
				preg_match_all('/pred3([a-zA-Z0-9-_]+)/', $string, $matches2);
				
				$_SESSION['author'] = $this->author;
				$_SESSION['pred2'] = $matches[1][0];
				$_SESSION['pred3'] = $matches2[1][0];
					
				$_SESSION['begin'] = $this->result->begin;
				$_SESSION['end'] = $this->result->end;
				$_SESSION['data1'] = $this->result->data1;
				$_SESSION['data2']= $this->result->data2;
				//end needed for comments
				
			}			
			// Render the view
			//$this->getReputation();
			$this->getMark();
			$this->renderView("details");
			
		}
	}
	public /*void*/ function storeReputation(){
			
		$rep = new Ranking($_SESSION['user']->id,$_SESSION['predicate'], $this->feedback);
		//$rep = new Ranking(array($_SESSION['user']->id,$_SESSION['predicate'], $this->feedback));
		$rep->storeReputation();
			
	}
	public /*void*/ function getMark(){
			
		$rep2 = new Ranking($_SESSION['user']->id,$_SESSION['predicate'],'');
		//$rep = new Ranking(array('name' => 'John'));
		$rep2->getMark();
			
	}

	
	
}
?>