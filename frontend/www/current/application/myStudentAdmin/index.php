<?php
	// page compression
	ob_start("ob_gzhandler");	
	// for the magic_quotes
	@set_magic_quotes_runtime(false);	
	
	// DEBUG
	ini_set('display_errors', 1);
	
	session_start();
	
	// GET ALL THE API KEYs AND ADDRESS
	require_once dirname(__FILE__).'/../../system/config.php';
	
	define('APPLICATION_NAME', "myStudent");
	define('USER_CONNECTED', isset($_SESSION['user']));
	
	// CREATE THE HTML HEADER
	require_once dirname(__FILE__).'/TemplateManager.class.php';
	$template = new TemplateManager();
	$template->getHeader();
	
	// IMPORTS ALL THE HANDLER
	require_once dirname(__FILE__).'/controller/MyApplicationHandler.class.php';	$application = new MyApplicationHandler();
	
	// IMPORTS ALL THE VIEWS	
	require_once dirname(__FILE__).'/views/AbstractView.class.php';	
	require_once dirname(__FILE__).'/views/home/MainView.class.php';
	require_once dirname(__FILE__).'/views/home/ArticleView.class.php';			new ArticleView($application);
	require_once dirname(__FILE__).'/views/home/EditArticleView.class.php';		new EditArticleView($application);
	require_once dirname(__FILE__).'/views/home/CommentView.class.php';			new CommentView($application);
	require_once dirname(__FILE__).'/views/home/EditCommentView.class.php';		new EditCommentView($application);
	require_once dirname(__FILE__).'/views/home/SubscribeView.class.php';		new SubscribeView($application);
	
	// CLOSE THE HTML PAGE
	$template->getFooter();
	
?>
