<? 
class UnsubscribeController extends AuthenticatedController {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		/*
		 * Try to get the User ExtendedProfile if it exist
		 * ExtendedProfile stored in the $_SESSION while using the app
		 */
		
		/*$req =  new Subscribev2(DELETE, $_GET, $this);
		$this->res = $req->send();
		
		*/
		
		$request = new SimpleRequestv2($_GET);
		$request->addArgument("application", "myEurope:users");
		
		$res = $request->send();
		if (!empty($res)){
			$rep =  new Reputationv2($_GET['id']);
			$res->reputation = $rep->send();
		
		}
		debug_r($res);
		
		
		$this->renderView("Unsubscribe");
			
		
	}
	
	
	
	
}
?>