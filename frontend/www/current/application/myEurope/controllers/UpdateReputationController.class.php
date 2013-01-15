<?php

/**
 *
 * Enter description here ...
 * @author lvanni
 *
 */
class UpdateReputationController extends DetailsController {
	
	public /*void*/ function handleRequest() {
		
		// The reputation in DetailsView ranges from 1 to 5
		// while the system needs valueas from 1 to 10
		$_GET['reputation'] = $_GET['reputation']*2;
		
		
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
					$this->success = _("Thank you for your contribution");
				}
// 			} catch (Exception $e) {
// 				$this->error = "Une erreur interne est survenue, veuillez réessayer plus tard...";
// 			}
		}	
		
		parent::handleRequest();
		
	}

}
?>
