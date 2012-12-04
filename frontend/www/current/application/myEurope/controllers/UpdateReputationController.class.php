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
		if(isset($_GET['reputation'])) {

			// Get the reputation of the user in each the application
			$request = new Request("InteractionRequestHandler", UPDATE);
			$request->addArgument("application",  APPLICATION_NAME);
			if(isset($_GET['isData'])){
				$request->addArgument("producer", $_GET['predicate'].$_GET['author']);
			} else {
				$request->addArgument("producer",  $_GET['author']);	
			}				
			$request->addArgument("consumer",  $_SESSION['user']->id);
			$request->addArgument("start",  time());
			$request->addArgument("end",  time());
			$request->addArgument("predicate",  $_GET['predicate']);
			$request->addArgument("feedback",  "0.".$_GET['reputation']);

// 			try {
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
				
				if($responseObject->status != 200) {
					$this->error = $responseObject->description;
				} else {
					$this->success = "Merci de votre contribution";
				}
// 			} catch (Exception $e) {
// 				$this->error = "Une erreur interne est survenue, veuillez réessayer plus tard...";
// 			}
		}	
		
		parent::handleRequest();
		
	}

}
?>
