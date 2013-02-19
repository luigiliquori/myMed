<?php

/**
 *
 * Enter description here ...
 * @author lvanni
 *
 */
class UpdateReputationController extends DetailsController {
	
	public /*void*/ function handleRequest() {
		
		// update the reputation of the application
		if(isset($_POST['reputation'])) {
			// Get the reputation of the user in each the application
			$request = new Request("InteractionRequestHandler", UPDATE);
			$request->addArgument("application",  APPLICATION_NAME);
			if(isset($_POST['isData'])){
				$request->addArgument("producer", $_POST['predicate'].$_POST['author']);
			} else {
				$request->addArgument("producer",  $_POST['author']);	
			}				
			$request->addArgument("consumer",  $_SESSION['user']->id);
			$request->addArgument("start",  time());
			$request->addArgument("end",  time());
			$request->addArgument("predicate",  $_POST['predicate']);
			$request->addArgument("feedback",  $_POST['reputation']/10);

			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			
			if($responseObject->status != 200) {
				$this->error = $responseObject->description;
			} else {
				$this->success = _("Thank you for your contribution!");
			}
		}	
		parent::handleRequest();
	}
}
?>
