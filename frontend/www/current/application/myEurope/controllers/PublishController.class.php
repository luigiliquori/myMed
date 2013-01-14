<?php 

// TODO: Should be a common controller in /system/controllers/
class PublishController extends ExtendedProfileRequired {
	
	/**
	 * This will create a temporary Profile with the informations submited by POST.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() { 

		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			
			if( empty($_POST['title']) ){
				$this->error = _("Title field can't be empty");
				$this->renderView("publish");
			}else if( empty($_POST['text']) ){
				$this->error = _("Text field can't be empty");
				$this->renderView("publish");
			}
			else{
				
				$obj = new Partnership();
				
				// Fill the object
				$_POST['publisher'] = $_SESSION['user']->id;
				$this->fillObj($obj);
				$obj->publish();
				
				$this->success = _("Your partnership offer has been successfully published");
				
				//$_POST["fromPublish"]=$this->success;
				//$action="Main";
				//$className = "MainController";
				//$controller = new $className($action);
				// Set succes/error
				//$controller->setSuccess($this->success);
				//$controller->handleRequest();
				//$this->redirectTo("main");
				//$_REQUEST['method']=$this->success;
				//$_POST["fromPublish"]=$this->success;
				$this->forwardTo("main");
				//$this->redirectTo("main", $_REQUEST);
				//$this->redirectTo("main");
			}
		}
	}
	
	private function fillObj($obj) {
	
		$obj->publisher = $_POST['publisher'];
	
		$obj->theme = $_POST['theme'];
		$obj->other = $_POST['other'];
	
		$obj->end 	= $_POST['date'];
	
		$obj->title = $_POST['title'];
		$obj->text 	= $_POST['text'];
	}
}

?>