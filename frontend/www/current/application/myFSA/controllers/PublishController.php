<? 

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class PublishController extends AuthenticatedController {
	
	public /*Array*/ $object;
	public function handleRequest() {
	
		parent::handleRequest();
			
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Publier") {
				
			
			//this field is to get info if DetailsView is redirect from publish controller or details controller
			$_SESSION['controller'] = "Publish";
			
			// -- Publish
			
			$obj = new PublishObject();
				
			// Fill the object
			$this->fillObj($obj);
			$obj->publish();
			
			$this->result = $obj;
			$_SESSION['author'] = $_SESSION['user']->id;
			$this->result->publisherID = $_SESSION['user']->id;
			$this->renderView("details");
			

				
		}	elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Rechercher") {
			
			//this field is to get info if DetailsView is redirect from publish controller or details controller
			$_SESSION['controller'] = "Search";
	
			// -- Search
			$this->search();	
			$this->renderView("search");
			
		} 	elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Comment") {
	
			// -- Publish
			
			$obj = new PublishObject();
				
			// Fill the object
			$this->fillObj2($obj);
			$obj->publish();
			
			$this->result = $obj;

			$this->result->publisherID = $_SESSION['author'];			
			$this->renderView("details");
			
		} elseif(isset($_REQUEST['keyword']) && $_REQUEST['keyword'] == "Reputation") {
// 			$_POST['data3'] = $_REQUEST['rate'];
// 			$obj = new PublishObject();
				
// 			// Fill the object
// 			$this->fillObj_rep($obj);
// 			$obj->publish();
			
// 			$this->result = $obj;
// 			$this->result->publisherID = $_SESSION['author'];

// 			$this->object = array(
// 					"reputation" => $_REQUEST['rate'],
// 					"uselessfield" => '2'
// 			);
// 			$this->storeReputation($this);

// 			$this->getReputation($this, $_SESSION['user']);
// 			$this->renderView("details");
		} elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Delete") {
			
			
		}
		else {
				
			// -- Show the form
		}
	
		$this->renderView("publish");
	}
	
	public function search() {
	
			// -- Search
	
			$search = new PublishObject();
			$this->fillObj($search);
			$this->result = $search->find();			
	}
	
	// Fill object with POST values
	private function fillObj($obj) {
		
		if(
		   isset($_POST['pred2']) &&
		   isset($_POST['pred3']) &&
		   isset($_POST['data1'])
			){
		
			$obj->begin = "";
			$obj->end = "";
			$obj->pred1 = "FSApublication";
			$obj->pred2 = $_POST['pred2'];
			$obj->pred3 = $_POST['pred3'];
			
			//main text
			$obj->data1 = $_POST['data1'];
			$obj->data3 = "";

			$obj->wrapped1 ="";
			$obj->wrapped2 ="";
			
			$_SESSION['begin'] = $obj->begin;
			$_SESSION['end'] = $obj->end;
			$_SESSION['pred2'] = $obj->pred2;
			$_SESSION['pred3'] = $obj->pred3;
			$_SESSION['data1'] = $obj->data1;
			$_SESSION['data2'] = NULL;
		} elseif(isset($_POST['pred2'])&&isset($_POST['pred3'])){
			$obj->pred2 = $_POST['pred2'];
			$obj->pred3 = $_POST['pred3'];
			
			$debugtxt  =  "<pre>I am in the fillObj1 second if";
			$debugtxt .= "</pre>";
			debug($debugtxt);
			
		} 
		else {
			$obj->pred1 = "FSApublication";
		}
	
	}
	
	// Fill object2 is used only for storing comments
	private function fillObj2($obj) {
				
			$obj->begin = $_SESSION['begin'];
			$obj->end = $_SESSION['end'];
			$obj->pred1 = "FSApublication";
			$obj->pred2 = $_SESSION['pred2'];
			$obj->pred3 = $_SESSION['pred3'];
			
			//the field is needed otherwise will sign session user and create new article
			$obj->publisherID = $_SESSION['author'];
				
			//main text
			$obj->data1 = $_SESSION['data1'];
			
			if ($_SESSION['user']->profilePicture == NULL){
				$_SESSION['user']->profilePicture = "http://www.kyivpost.com/static//default_user.png";
			}
			
			//this code is needed for storing user's comment :)
			$_POST['data2'] = $_POST['data2'].'"'.$_SESSION['user']->name.'"'.$_SESSION['user']->profilePicture;			
			if (isset($_SESSION['data2'])){
				$obj->data2 = $_SESSION['data2'].$_POST['data2']."#";
			}
			else {
				$obj->data2 = "#".$_POST['data2']."#";
			}
			$_SESSION['data2'] = $obj->data2;
			$obj->data3 = "";
				
			$obj->wrapped1 ="";
			$obj->wrapped2 ="";	
			
			$debugtxt  =  "<pre>I am in the fillObj2";
			$debugtxt .= "</pre>";
			debug($debugtxt);
	
	}
	
	
	// fillObj_rep is used for adding reputation
	private function fillObj_rep($obj) {
	
			$obj->begin = $_SESSION['begin'];
			$obj->end = $_SESSION['end'];
			$obj->pred1 = "FSApublication";
			$obj->pred2 = $_SESSION['pred2'];
			$obj->pred3 = $_SESSION['pred3'];			
			//the field is needed otherwise will sign session user and create new article
			$obj->publisherID = $_SESSION['author'];				
			//main text
			$obj->data1 = $_SESSION['data1'];
			//comment
			$obj->data2 = $_SESSION['data2'];

			
			//storing user's reputation
			
			//changing value of user for float
			$rep_new = floatval($_POST['data3']);
			if (isset($_SESSION['data3'])){
				/* rep is stored as "rep#vot 
				 * where rep is a float value of reputations of user 
				 * and vot is int value of number of votes
				 * */
				preg_match_all('/"([0-9/./,]+)/', $string, $m);
				preg_match_all('/#([0-9/./,]+)/', $string, $m2);
				
				$rep = floatval($m[1][0]);
				$votes = intval($m2[1][0]);
				
				$rep = $rep+$rep_new;
				$votes = $votes + 1;
				
				$obj->data3 = '"'.$rep."#".$votes;
			}
			else {
				$obj->data3 = '"'.$_POST['data3']."#".'1';
			}
					
			$debugtxt  =  "<pre>REEEEEEPUTAAAATION";
			$debugtxt  .= var_export($_REQUEST['rate'], TRUE);
			$debugtxt  .= var_export($obj->data3, TRUE);
			$debugtxt .= "</pre>";
			debug($debugtxt);
			
	}
 	
}
?>