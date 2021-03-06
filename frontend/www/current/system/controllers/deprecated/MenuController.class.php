<?php
require_once MYMED_ROOT. 'lib/dasp/request/Request.class.php';
require_once MYMED_ROOT. 'lib/dasp/request/IRequestHandler.php';

/**
 *
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class MenuController implements IRequestHandler {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*string*/ $error;
	private /*string*/ $success;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function handleRequest() {
		if(isset($_POST['disconnect']) || isset($_GET['disconnect'])) {
			
			// DELETE BACKEND SESSION
			$request = new Request("SessionRequestHandler", DELETE);
			$request->addArgument("accessToken", $_SESSION['user']->session);
			$request->addArgument("socialNetwork", $_SESSION['user']->socialNetworkName);

			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
				
			if($responseObject->status != 200) {
				$this->error = $responseObject->description;
			}
				
			// DELETE FRONTEND SESSION
			session_destroy();
			if (isset($_SESSION['wrapper'])){
				header("Refresh:0;url=".$_SESSION['wrapper']->getLogoutUrl());
				header("Refresh:0;url=".$_SERVER['PHP_SELF']);
			} else {
				header("Refresh:0;url=".$_SERVER['PHP_SELF']);
			}
		} 
	}

	public /*String*/ function getError(){
		return $this->error;
	}

	public /*String*/ function getSuccess(){
		return $this->success;
	}
}
?>
