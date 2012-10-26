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

		}elseif($_SESSION["profileFilled"] == "guest"){
			$this->renderView("main2");
		}
		else{
			$this->redirectTo("search");
			//$p = new PublishController();
			//$p->search();
			//$this->renderView("search");
		}

					
	}
	

	public /*void*/ function fetchExtendedProfile(){
	
		$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
		$debugtxt  .= var_export("przed", TRUE);
		$debugtxt .= "</pre>";
		debug($debugtxt);
		
		$result = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);


		
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