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

//header('Server: mymed'.substr($_SERVER['SERVER_ADDR'], 11));	// impossible à changer (voir la fichier de config d'apache et elever la version d'apache
// remove PHP's header for more security
header('X-Powered-By: ');

header('Content-Script-Type:text/javascript');
if(session_name()==='myMedSession_main')
{
	if(!isset($_SESSION['user']))
		$_GET["connexion"] = 'guest';
	define('USER_CONNECTED', $_SESSION['user']->socialNetworkName!==null );
}
else
	define('USER_CONNECTED', isset($_SESSION['user']));
$templateManager = new TemplateManager(null, isset($_GET['ajax'])?'serviceajax':'home');
if(defined('CONTENTOBJECT'))
{
	require_once CONTENTOBJECT.'.class.php';
	$contentClass = basename(CONTENTOBJECT);
	$templateManager->setContent(new $contentClass());
}
else if(isset($_GET['service']))
{
	$serviceFile = dirname(__FILE__).'/../services/'.$_GET['service'].'/'.$_GET['service'].'.class.php';
	if(is_file($serviceFile))
	{
		require_once($serviceFile);
		$templateManager->setContent(new $_GET['service']());
	}
	else
	{
		require_once dirname(__FILE__).'/ServiceNotFound.class.php';
		$templateManager->setContent(new ServiceNotFound());
	}
}
else
{
	require_once dirname(__FILE__).'/Desktop.class.php';
	$templateManager->setContent(new Desktop());
}
$templateManager->callTemplate();
?>
