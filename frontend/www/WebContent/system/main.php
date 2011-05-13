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

define('USER_CONNECTED', isset($_SESSION['user']) );
if(USER_CONNECTED)
{
	if(!isset($_GET['service']))
		$_GET['service'] = 'Desktop';
	$serviceFile = dirname(__FILE__).'/../services/'.$_GET['service'].'/'.$_GET['service'].'.class.php';
	if(is_file($serviceFile))
	{
		require_once($serviceFile);
		$templateManager = new TemplateManager(new $_GET['service'](), "home");
	}
	else
	{
		require_once(dirname(__FILE__).'/../services/Dynamic/Dynamic.class.php');
		$templateManager = new TemplateManager(new Dynamic(), "home");
	}
}
else
	$templateManager = new TemplateManager(null, "connect");
$templateManager->callTemplate();
?>