<?

/**
 *  This controller shows the temporary page for future statistical measure
 */

class AnalyticsController extends AuthenticatedController {

	/**
	 * Request handler method is calling when we use
	 * ?action=analytics in the address 
	 */
	public function handleRequest() {

		parent::handleRequest();
		$this->renderView("analytics");
	}


}
?>