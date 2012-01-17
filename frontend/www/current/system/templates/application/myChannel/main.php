<?php 
	define('APPLICATION_NAME', "myChannel");
	define('CONNECTED', isset($_GET['category']) && isset($_GET['channel']));
	
	// IMPORT THE MAIN VIEW
	require_once dirname(__FILE__).'/views/tabbar/ChatView.class.php';
	require_once dirname(__FILE__).'/views/tabbar/FindView.class.php';
	require_once dirname(__FILE__).'/views/tabbar/AddView.class.php';

	// IMPORT AND DEFINE THE REQUEST HANDLER
	require_once dirname(__FILE__).'/handler/MyApplicationHandler.class.php';
	$handler = new MyApplicationHandler();
	$handler->handleRequest();

	if(CONNECTED) {
		$chat = new ChatView();
		$chat->printTemplate();
	}
	
	$find = new FindView();
	$find->printTemplate();
	
	$add = new AddView();
	$add->printTemplate();
?>

