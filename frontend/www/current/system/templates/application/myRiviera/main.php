<?php 
	// NAME OF THE APPLICATION
	define('APPLICATION_NAME', "myRiviera");

	// DEFINE ATTRIBUTES FOR THE JAVASCRIPT PART (AJAX CALL)
	echo "<input type='hidden' id='applicationName' value='" . APPLICATION_NAME . "' />";
	echo "<input type='hidden' id='accessToken' value='" . $_SESSION['accessToken'] . "' />";
	
	// IMPORT THE MAIN VIEW
	require_once dirname(__FILE__).'/views/tabbar/FindView.class.php';
	require_once dirname(__FILE__).'/views/tabbar/OptionView.class.php';
	
	// IMPORT DIALOG
	require_once dirname(__FILE__).'/views/dialog/EditDialog.class.php';
	
	// IMPORT AND DEFINE THE REQUEST HANDLER
	require_once dirname(__FILE__).'/handler/MyApplicationHandler.class.php';
	$handler = new MyApplicationHandler();
	$handler->handleRequest();

	// DISCONNECT FORM
	require_once 'system/templates/handler/MenuHandler.class.php';
	$menuHandler = new MenuHandler();
	$menuHandler->handleRequest();
	?>
	<!-- Disconnect the user -->
	<form action="?application=0" method="post" name="disconnectForm" id="disconnectForm">
	<input type="hidden" name="disconnect" value="1" />
	</form>
	<?php 

	// VIEW
	$find = new FindView($handler);
	$find->printTemplate();
	
	$option = new OptionView($handler);
	$option->printTemplate();
	
	// DIALOG
	$edit = new EditDialog();
	$edit->printTemplate();
	
?>
