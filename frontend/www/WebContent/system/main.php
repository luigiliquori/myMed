<?php 
ob_start("ob_gzhandler");		// compression des pages
@set_magic_quotes_runtime(false);// ne pas utiliser les magic_quotes
require_once dirname(__FILE__).'/config.php';
ini_set('session.use_trans_sid', 0);
ini_set('session.cookie_path', ROOTPATH);
if(defined('DEBUG')&&DEBUG)
	ini_set('display_errors', 1);
ELSE
	ini_set('display_errors', 0);
// class d'un objet présent en session => doit être définie avant l'initialisation des session
require_once dirname(__FILE__).'/backend/Profile.class.php';
session_name('myMedSession_'.(defined('SESSIONNAME')?SESSIONNAME:'main'));
session_start();

require_once dirname(__FILE__).'/Debug.class.php';
require_once dirname(__FILE__).'/TemplateManager.class.php';
require_once dirname(__FILE__).'/ContentObject.class.php';
require_once dirname(__FILE__).'/library.php';

define('USER_CONNECTED', isset($_SESSION['user']) );
$templateManager = new TemplateManager();
if(defined('CONTENTOBJECT'))
{
	require_once CONTENTOBJECT.'.class.php';
	$contentClass = basename(CONTENTOBJECT);
	$templateManager->setContent(new $contentClass());
}
else if(USER_CONNECTED)
{
	if(!isset($_GET['service']))
		$_GET['service'] = 'Desktop';
	$serviceFile = dirname(__FILE__).'/../services/'.$_GET['service'].'/'.$_GET['service'].'.class.php';
	if(is_file($serviceFile))
	{
		require_once($serviceFile);
		$templateManager->setContent(new $_GET['service']());
	}
	else
	{
		require_once(dirname(__FILE__).'/../services/Dynamic/Dynamic.class.php');
		$templateManager->setContent(new Dynamic());
	}
}
$templateManager->callTemplate();
?>
