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
			
					$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR publish comment";
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

	

	
	}
	
	// Fill object with POST values
	private function fillObj2($obj) {
				
			$obj->begin = $_SESSION['begin'];
			$obj->end = $_SESSION['end'];
			$obj->pred1 = "FSApublication";
			$obj->pred2 = $_SESSION['pred2'];
			$obj->pred3 = $_SESSION['pred3'];
				
			//main text
			$obj->data1 = $_SESSION['data1'];
			
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
	
	}
	
	
	// Fill object with POST values
	private function rep($obj) {
	
		if(isset($_SESSION['begin']) &&
				isset($_SESSION['end']) &&
				isset($_SESSION['pred2']) &&
				isset($_SESSION['pred3'])
		){
	
			$obj->begin = $_SESSION['begin'];
			$obj->end = $_SESSION['end'];
			$obj->pred1 = "FSApublication";
			$obj->pred2 = $_SESSION['pred2'];
			$obj->pred3 = $_SESSION['pred3'];
	
			//main text
			$obj->data1 = $_SESSION['data1'];
			//coments
			$obj->data2 = $_SESSION['data2'];

			//reputation
			if (isset($_SESSION['data3'])){
				$obj->data2 = $_SESSION['data2'].$_POST['data2']."#";
			}
			else $obj->data2 = "#".$_POST['data2']."#";
			$obj->data3 = "";
	
			
			$obj->wrapped1 ="";
			$obj->wrapped2 ="";
		}
	
	}
	
 	
}
?>