<? 
class MainController extends AuthenticatedController {

//$this->renderView("Main");
//$this->renderView("search");
// 	$this->redirectTo("publish");
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		/*
		 * Try to get the User ExtendedProfile if it exist
		 * ExtendedProfile stored in the $_SESSION while using the app
		 */
		if( !isset($_SESSION['ExtendedProfile'])){
			$this->fetchExtendedProfile();
			$this->redirectTo("ExtendedProfile");

		}
		else
			$this->renderView("main");
			//$p = new PublishController();
			//$p->search();
			//$this->renderView("search");
					
	}
	

	public /*void*/ function fetchExtendedProfile(){
	
		$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
		$debugtxt  .= var_export("przed", TRUE);
		$debugtxt .= "</pre>";
		debug($debugtxt);
		$result = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);

		$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
		$debugtxt  .= var_export("po", TRUE);
		$debugtxt .= "</pre>";
		debug($debugtxt);
		if (empty($result)){
			$this->error = "";
			$this->renderView("ExtendedProfileNeeded");
		}
		else {
			$_SESSION['ExtendedProfile'] = $result;
			$this->success = "";
			$this->renderView("main");
		}

	}
	
// 	$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
// 	$debugtxt  .= var_export("bla", TRUE);
// 	$debugtxt .= "</pre>";
// 	debug($debugtxt);
	
}
?>