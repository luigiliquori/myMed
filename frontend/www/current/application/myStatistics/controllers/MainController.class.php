<?

/**
 * This controller is the main controller of the statistics application.
 * it shows the statistics about publish and subscribe in myMed applications
 *
 */
namespace statistic;

require(MYMED_ROOT."/application/myMed/controllers/MainController.class.php");

class MainController extends \AuthenticatedController {
	
	public static function getBootstrapApplication(){
	 	return \MainController::$bootstrapApplication;
	}

	/**
	* Request handler method is calling when we use
	* ?action=main in the address
	*/
	public function handleRequest() {

		parent::handleRequest();
		$this->renderView("main");
	}

	public function generateGraph(){
		echo "generate graph";
		
	}
}
?>