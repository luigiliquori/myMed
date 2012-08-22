<? 
class UnsubscribeRequired extends AuthenticatedController {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		/*
		 * Try to get the User ExtendedProfile if it exist
		 * ExtendedProfile stored in the $_SESSION while using the app
		 */
		
		$req =  new Subscribev2(DELETE, $_GET, $this);
		$this->res = $req->send();
		
		$this->renderView("Unsubscribe");
			
		
	}
	
	
	
	
}
?>