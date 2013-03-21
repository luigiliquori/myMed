<?

/**
 *  This controller shows the temporary page for future statistical measure
 */

class StatController extends AuthenticatedController {

	/**
	 * Request handler method is calling when we use
	 * ?action=stat in the address 
	 */
	public function handleRequest() {

		parent::handleRequest();
		$this->renderView("stat");
	}


}
?>