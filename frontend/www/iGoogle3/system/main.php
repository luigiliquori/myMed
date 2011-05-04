<?php 
require_once dirname(__FILE__).'/config.php';
set_magic_quotes_runtime(false);// ne pas utiliser les magic_quotes
ob_start("ob_gzhandler");		// compression des pages
ini_set('session.use_trans_sid', 0);
ini_set('session.cookie_path', ROOTPATH);
if(defined('DEBUG')&&DEBUG)
	ini_set('display_errors', 1);
ELSE
	ini_set('display_errors', 0);
session_start();

require_once dirname(__FILE__).'/Debug.class.php';
require_once dirname(__FILE__).'/TemplateManager.class.php';
require_once dirname(__FILE__).'/ContentObject.class.php';

if(isset($_POST["logout"])) //@todo warning was a $_GET var
{
	session_destroy();
	header('Location:'.$_SERVER["REQUEST_URI"]);
	exit;
}
if(!isset($_SESSION['user']))
	$_POST["connexion_guest"] = '';
if(!isset($_GET['service']))
	$templateManager = new TemplateManager();
else
{
	$serviceFile = dirname(__FILE__).'/../services/'.$_GET['service'].'/'.$_GET['service'].'.class.php';
	if(is_file($serviceFile))
	{
		require_once($serviceFile);
		$templateManager = new TemplateManager(new $_GET['service'](), "serviceajax");
	}
	else
	{/*
		require_once(dirname(__FILE__).'/../services/dynamic/Dynamic.class.php');
		$templateManager = new TemplateManager(new Dynamic(), "home");//*/
		header("Status: 404 Not Found", false, 404);
		die('<div xmlns="http://www.w3.org/1999/xhtml">Service Not Found !</div>');
	}
}
$templateManager->callTemplate();
?>