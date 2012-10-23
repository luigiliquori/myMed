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

	function generateGraph(){
		echo "generate graph";
		//My brain to me :"use JSON"
		//Yep good idea brain
		$this->response = '{"name" : "curve1","type" : "month", "curve" : [[1,1],[2,2],[3,3],[4,4]]}';
		$this->renderView("main");
	}
}
?>