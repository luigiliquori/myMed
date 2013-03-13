<?php

/**
 *
 * Enter description here ...
 * @author lvanni
 *
 */
class SubscribeController extends AuthenticatedController {
	
	public /*void*/ function handleRequest() {
		
		parent::handleRequest();
		
		// Read subscription of the user
		if(isset($_POST['method']) && $_POST['method'] = "Get Subscription") {

			// Get the reputation of the user in each the application
			$request = new Request("SubscribeRequestHandler", READ);
			$request->addArgument("application",  APPLICATION_NAME);
			$request->addArgument("userID",  $_SESSION['user']->id);
			
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			
			if($responseObject->status != 200) {
				$this->subscription = $responsejSon;
			} else {
				$this->subscription = $responsejSon;
			}
			
		}
		
		$this->renderView("profile");
		
	}

}
?>