<?

/** 
 *  Abstract class that renders the view <ActionName>View. 
 *  This controller requires authentication.
 */
abstract class SimpleViewController extends AuthenticatedController {

	public /*string*/ $error;
	public /*string*/ $success;
	
	public function handleRequest() {
		
		// Inherited authentication check
		parent::handleRequest();
		
		// Build the action name from the name of the controller
		$actionName = get_class($this);
		$actionName = str_replace("Controller", "", $actionName);
		$actionName = lcfirst($actionName);
		
		// Render the corresponding view
		$this->renderView($actionName);
	}
}

?>