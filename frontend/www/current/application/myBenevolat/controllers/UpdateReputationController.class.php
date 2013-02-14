<?php

/**
 *  Update reputation controller
 */
class UpdateReputationController extends DetailsController {
	
	public /*void*/ function handleRequest() {
		
		// update the reputation of the application
		if(isset($_POST['reputation'])) {

			// Get the reputation of the user in each the application
			$request = new Request("InteractionRequestHandler", UPDATE);
			$request->addArgument("application",  APPLICATION_NAME);
			if(isset($_POST['isData'])){
				$request->addArgument("producer", $_SESSION['predicate'].$_SESSION['author']);
			} else {
				$request->addArgument("producer",  $_SESSION['author']);	
			}				
			$request->addArgument("consumer",  $_SESSION['user']->id);
			$request->addArgument("start",  time());
			$request->addArgument("end",  time());
			$request->addArgument("predicate",  $_SESSION['predicate']);
			$request->addArgument("feedback",  $_POST['reputation']/10);

// 			try {
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
				
				if($responseObject->status != 200) {
					$this->error = $responseObject->description;
				} else {
					$this->success = _("Thank you for your contribution!");
				}
// 			} catch (Exception $e) {
// 				$this->error = "Une erreur interne est survenue, veuillez réessayer plus tard...";
// 			}
		}	
		
		// Set the id in GET variables becouse the details controller use them 
		$_GET['id'] = $_POST['id'];
		parent::handleRequest();
		
	}

}
?>
