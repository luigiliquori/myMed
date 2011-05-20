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
session_start();

require_once dirname(__FILE__).'/Debug.class.php';
require_once dirname(__FILE__).'/TemplateManager.class.php';
require_once dirname(__FILE__).'/ContentObject.class.php';

header("Content-Script-Type:text/javascript");
if(!isset($_SESSION['user']))
{//*
	$_POST["connexion"] = 'guest';/*/
	$_SESSION['user'] = array(
			'id'				=> 'visiteur',
			'name'				=> null,
			'gender'			=> null,
			'locale'			=> null,
			'updated_time'		=> null,
			'profile'			=> null,
			'profile_picture'	=> null,
			'social_network'	=> null);
	$encoded = json_encode($_SESSION['user']);
	file_get_contents(trim(BACKEND_URL."ProfileRequestHandler?act=0&user=" . urlencode($encoded)));//*/
}
define('USER_CONNECTED', $_SESSION['user']->socialNetworkName != '' );
$templateManager = new TemplateManager(null, isset($_GET['ajax'])?'serviceajax':'home');
if(isset($_GET['service']))
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