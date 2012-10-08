<?php


class ReputationController extends AbstractController
{
	/**
	 * @see IRequestHandler::handleRequest()
	 */
	public function handleRequest() {
	
		parent::handleRequest();
			
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Reputation") {
				//$this->storeReputation();
			
				$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
				$debugtxt  .= var_export($_REQUEST['rate'], TRUE);
				$debugtxt  .= var_export($_SESSION['user'], TRUE);
				$debugtxt .= "</pre>";
				$debugtxt  .= var_export(htmlspecialchars($_GET["action"]), TRUE);
				debug($debugtxt);
	
		}
		else {
	
			// -- Show the form
		}
	}
	
	
	
// 	public /*void*/ function handleRequest(){
		
// 		/*
// 		 * Determine the dehaviour :
// 		 * POST data ->  Store the profile
// 		 * No Post but ExtendedProfile in session -> show profile
// 		 * Nothing -> show the form to fill the profile
// 		 */
// 		if (true)
// 			$this->storeReputation();
// 		else
// 			$this->showProfile();
// 	}	
	
	public /*void*/ function storeReputation(){
		
			
			$rep = new Reputation('myFSA',$_SESSION['user']);
			
			$rep->storeReputation();
			
	}
	
	public /*void*/ function getReputation(){
		
		//ExtendedProfile::getExtendedProfile($_SESSION['user']);
	}
	
	public /*void*/ function showProfile(){

		//$this->renderView("ExtendedProfileDisplay");
	}

}