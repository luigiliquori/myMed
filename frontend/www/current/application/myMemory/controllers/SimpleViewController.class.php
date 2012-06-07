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
		
		// Build the action name
		$actionName = get_class($this);
		$actionName = str_replace("Controller", "", $actionName);
		$actionName = lcfirst($actionName);
		
		// Render its view
		$this->renderView($actionName);
	}
}

?>