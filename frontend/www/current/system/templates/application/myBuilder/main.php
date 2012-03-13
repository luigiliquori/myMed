<?php 

// NAME OF THE APPLICATION
define('APPLICATION_NAME', "myBuilder");

// LOAD DASP JAVASCRIPT LIBRARY
echo "<script src='lib/dasp/javascript/dasp.js'></script>";

// LOAD THE VIEWs
if(USER_CONNECTED) {
	// HOME PAGE OF THE APPLICATION ---------------------------

	// IMPORT THE HANDLER
	require_once dirname(__FILE__).'/handler/MenuHandler.class.php';
	$menuHandler = new MenuHandler();
	$menuHandler->handleRequest();
	require_once dirname(__FILE__).'/handler/UpdateProfileHandler.class.php';
	$updateHandler = new UpdateProfileHandler();
	$updateHandler->handleRequest();
	require_once dirname(__FILE__).'/handler/MyApplicationHandler.class.php';
	$appHandler = new MyApplicationHandler();
	$appHandler->handleRequest();
	require_once dirname(__FILE__).'/handler/BuilderHandler.class.php';
	$builder = new BuilderHandler();
	$builder->handleRequest();

	// IMPORT THE MAIN VIEW
	require_once dirname(__FILE__).'/views/home/MainView.class.php';
	require_once dirname(__FILE__).'/views/home/BuildView1.class.php';
	require_once dirname(__FILE__).'/views/home/BuildView2.class.php';
	require_once dirname(__FILE__).'/views/home/BuildView3.class.php';
	require_once dirname(__FILE__).'/views/dialog/Option.class.php';
	require_once dirname(__FILE__).'/views/dialog/Validate.class.php';
	require_once dirname(__FILE__).'/views/result/ResultView.class.php';
	require_once dirname(__FILE__).'/views/result/DetailView.class.php';

	// DISCONNECT FORM
	echo '<form action="?application=' . APPLICATION_NAME . '" method="post" name="disconnectForm" id="disconnectForm">';
	echo '<input type="hidden" name="disconnect" value="1" /></form>';

	// DEFINE ATTRIBUTES FOR THE JAVASCRIPT PART (AJAX CALL)
	echo "<input type='hidden' id='userID' value='" . $_SESSION['user']->id . "' />";
	echo "<input type='hidden' id='applicationName' value='" . APPLICATION_NAME . "' />";
	echo "<input type='hidden' id='accessToken' value='" . $_SESSION['accessToken'] . "' />";

	// BUILD THE VIEWs
	if(isset($_POST['method'])) { 				// Print The Results View
		if($_POST['method'] == 'getDetail') {
			$details = new DetailView($appHandler);
			$details->printTemplate();
		} else {
			$result = new ResultView($appHandler);
			$result->printTemplate();
		}
	} else {									// Print The Default Views
		$main = new MainView();
		$main->printTemplate();

		$build1 = new BuildView1();
		$build1->printTemplate();
		
		$build2 = new BuildView2();
		$build2->printTemplate();
		
		$build3 = new BuildView3();
		$build3->printTemplate();
		
		$option = new Option();
		$option->printTemplate();
		
		$validate = new Validate();
		$validate->printTemplate();
	}

} else { // LOGIN PAGE OF THE APPLICATION ---------------------------

	// IMPORT THE MAIN VIEW
	if(TARGET == "mobile") {
		// load the css
		echo '<link href="system/templates/application/' . APPLICATION_NAME . '/views/login/mobile/css/style.css" rel="stylesheet" />';
		require_once dirname(__FILE__).'/views/login/mobile/Login.class.php';
	} else {
		// load the css
		echo '<link href="system/templates/application/' . APPLICATION_NAME . '/views/login/desktop/css/style.css" rel="stylesheet" />';
		require_once dirname(__FILE__).'/views/login/desktop/Login.class.php';
	}

	// BUILD THE VIEWs
	$login = new Login();
	$login->printTemplate();

	include('views/dialog/socialNetwork.php');
	include('views/dialog/condition.php');
}

?>
