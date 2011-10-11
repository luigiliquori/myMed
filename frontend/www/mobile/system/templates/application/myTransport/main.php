<?php 
	define('APPLICATION_NAME', "myTransport"); 
	
	// IMPORT THE MAIN VIEW
	require_once dirname(__FILE__).'/views/tabbar/MapView.class.php';
	require_once dirname(__FILE__).'/views/tabbar/PublishView.class.php';
	require_once dirname(__FILE__).'/views/tabbar/FindView.class.php';
	// IMPORT DIALOG VIEW
	require_once dirname(__FILE__).'/views/dialog/Contact.class.php';
	// IMPORT THE RESULT VIEW
	require_once dirname(__FILE__).'/views/result/ResultView.class.php';
	require_once dirname(__FILE__).'/views/result/DetailView.class.php';
	// IMPORT AND DEFINE THE REQUEST HANDLER
	require_once dirname(__FILE__).'/handler/MyApplicationHandler.class.php';
	$handler = new MyApplicationHandler();
	$handler->handleRequest();

	
	if(isset($_POST['method'])) { 				// Print The Results View
		if($_POST['method'] == 'getDetail') {
			$details = new DetailView($handler);
			$details->printTemplate();
			
			$contact = new Contact();
			$contact->printTemplate();
		} else {
			$result = new ResultView($handler);
			$result->printTemplate();
		}
	} else {								// Print The Default Views
		$map = new MapView();
		$map->printTemplate();
		
		$publish = new PublishView();
		$publish->printTemplate();
		
		$find = new FindView();
		$find->printTemplate();
	}
?>
