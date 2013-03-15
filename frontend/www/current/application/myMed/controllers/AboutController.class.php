<?php

class AboutController extends AbstractController {

	/**
	 * This will create a temporary Profile with the informations submited by POST.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() {
		
		switch ($_GET['method']){
			case 'show_aboutView':
				$this->renderView("about");
				break;
		}
	}
}
?>
