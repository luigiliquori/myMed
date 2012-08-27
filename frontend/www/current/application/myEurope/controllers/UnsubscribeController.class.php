<? 
class UnsubscribeController extends AuthenticatedController {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		/*
		 * Try to get the User ExtendedProfile if it exist
		 * ExtendedProfile stored in the $_SESSION while using the app
		 */
		
		unset($_GET['action']);
		$req =  new RequestJson($this, $_GET, DELETE, "v2/SubscribeRequestHandler");
		$req->addArgument("user", $_SESSION['user']->id);
		try{
			$this->res = $req->send();
		} catch(Exception $e){
			
		}
		
		$this->renderView("Unsubscribe");
			
		
	}
	
	
	
	
}
?>