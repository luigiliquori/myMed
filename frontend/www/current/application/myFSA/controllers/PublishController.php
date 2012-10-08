<? 

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class PublishController extends AuthenticatedController {
	
	
// 	$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
// 	$debugtxt  .= var_export($this->result, TRUE);
// 	$debugtxt .= "</pre>";
// 	debug($debugtxt);
	
	public function handleRequest() {
	
		parent::handleRequest();
			
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Publish") {
				
			
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
			$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR publish";
			$debugtxt  .= var_export($_SESSION['author'], TRUE);
			$debugtxt  .= var_export($_SESSION['pred2'], TRUE);
			$debugtxt  .= var_export($_SESSION['pred3'], TRUE);
			$debugtxt  .= var_export($_SESSION['begin'], TRUE);
			$debugtxt  .= var_export($_SESSION['end'], TRUE);
			$debugtxt  .= var_export($_SESSION['data1'], TRUE);
			$debugtxt  .= var_export($_SESSION['data2'], TRUE);
			$debugtxt .= "</pre>";
			debug($debugtxt);
			$this->renderView("details");
			

				
		}	elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Search") {
			
			//this field is to get info if DetailsView is redirect from publish controller or details controller
			$_SESSION['controller'] = "Search";
	
			// -- Search
			$this->search();	
			$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR search";
			$debugtxt  .= var_export($_SESSION['author'], TRUE);
			$debugtxt  .= var_export($_SESSION['pred2'], TRUE);
			$debugtxt  .= var_export($_SESSION['pred3'], TRUE);
			$debugtxt  .= var_export($_SESSION['begin'], TRUE);
			$debugtxt  .= var_export($_SESSION['end'], TRUE);
			$debugtxt  .= var_export($_SESSION['data1'], TRUE);
			$debugtxt  .= var_export($_SESSION['data2'], TRUE);
			$debugtxt .= "</pre>";
			debug($debugtxt);
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
			$_REQUEST['rate']/5;
			$obj = new PublishObject();
				
			// Fill the object
			$this->fillObj_rep($obj);
			$obj->publish();
			
			$this->result = $obj;
			$this->result->publisherID = $_SESSION['author'];			

			$this->renderView("details");
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
		
		if(isset($_POST['begin']) && 
		   isset($_POST['end']) &&
		   isset($_POST['pred2']) &&
		   isset($_POST['pred3']) &&
		   isset($_POST['data1'])
			){
		
			$obj->begin = $_POST['begin'];
			$obj->end = $_POST['end'];
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
		}  
		else {
			$obj->pred1 = "FSApublication";
		}
		$debugtxt  =  "<pre>I am in the fillObj1";
		$debugtxt .= "</pre>";
		debug($debugtxt);
	

	
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
				
			$obj->wrapped1 ="";
			$obj->wrapped2 ="";		
	}
	
 	
}
?>