<?php
	// page compression
	ob_start("ob_gzhandler");	
	// for the magic_quotes
	@set_magic_quotes_runtime(false);	
	
	// DEBUG
	ini_set('display_errors', 1);
	
	session_start();
	
	// GET ALL THE API KEYs AND ADDRESS
	require_once dirname(__FILE__).'/../../../system/config.php';
	
	// IMPORT DICIONARY
	require_once dirname(__FILE__).'/dictionary.php';
	
	define('APPLICATION_NAME', "myTemplate");
	define('USER_CONNECTED', isset($_SESSION['user']));
	
	// CREATE THE HTML HEADER
	require_once dirname(__FILE__).'/TemplateManager.class.php';
	$template = new TemplateManager();
	$template->getHeader();
	
	// IMPORTS ALL THE HANDLER
	require_once dirname(__FILE__).'/controller/MyApplicationHandler.class.php';	$application = new MyApplicationHandler();
	require_once dirname(__FILE__).'/controller/LoginHandler.class.php';			$login = new LoginHandler();
	require_once dirname(__FILE__).'/controller/InscriptionHandler.class.php';		$inscription = new InscriptionHandler();
	require_once dirname(__FILE__).'/controller/MenuHandler.class.php';				new MenuHandler();
	
	// IMPORTS ALL THE VIEWS	
	require_once dirname(__FILE__).'/views/AbstractView.class.php';	
	require_once dirname(__FILE__).'/views/home/MainView.class.php';
	require_once dirname(__FILE__).'/views/home/ProfileView.class.php';			new ProfileView($login, $inscription);
	require_once dirname(__FILE__).'/views/home/UpdateProfileView.class.php';	new UpdateProfileView();
	require_once dirname(__FILE__).'/views/home/InscriptionView.class.php';		new InscriptionView();
	require_once dirname(__FILE__).'/views/home/FindView.class.php';			new FindView($application);
	require_once dirname(__FILE__).'/views/home/ResultView.class.php';			new ResultView($application);
	require_once dirname(__FILE__).'/views/home/DetailView.class.php';			new DetailView($application);
	require_once dirname(__FILE__).'/views/home/PublishView.class.php';			new PublishView($application);

	// CLOSE THE HTML PAGE
	$template->getFooter();
	
?>
