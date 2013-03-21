<? 
class UnsubscribeController extends AuthenticatedController {
	
	function defaultMethod() {
		
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