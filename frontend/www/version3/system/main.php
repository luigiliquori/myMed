<?php 
// INIT
ob_start("ob_gzhandler");		// compression des pages
@set_magic_quotes_runtime(false);// ne pas utiliser les magic_quotes
require_once __DIR__.'/config.php';
ini_set('session.use_trans_sid', 0);
ini_set('session.cookie_path', ROOTPATH);

// Débuggage pour les ordi myMed
$ip = ip2long($_SERVER['REMOTE_ADDR']);
if(ip2long('138.96.242.0')<$ip && $ip<ip2long('138.96.242.255'))
{
	if(isset($_GET['debug']))
	{
		if($_GET['debug'])
		{
			setcookie('debug', 'true');
			$_COOKIE['debug']	= 'true';
		}
		else
		{
			setcookie('debug', '');
			unset($_COOKIE['debug']);
		}
	}
	if(isset($_COOKIE['debug']))
		define('DEBUG', true);
	ini_set('display_errors', 1);
}
else
	ini_set('display_errors', 0);
unset($ip);

//header('Server: mymed'.substr($_SERVER['SERVER_ADDR'], 11));	// impossible à changer (voir le fichier de config d'apache et enlever la version d'apache)
// remove PHP's header for more security
header('X-Powered-By: ');

// Includes
require_once __DIR__.'/backend/model/Profile.class.php';
require_once __DIR__.'/Debug.class.php';
require_once __DIR__.'/library.php';
require_once __DIR__.'/MainTemplate.class.php';
require_once __DIR__.'/ContentObject.class.php';
require_once __DIR__.'/../socialNetworkAPIs/GlobalConnexion.class.php';

// define PATH_INFO contante
$PATH_INFO		= explode('/', isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:null);
$PATH_INFO[0]	= basename($_SERVER['SCRIPT_NAME']);
trace($PATH_INFO,'$PATH_INFO', __FILE__, __LINE__);

//BEGIN
function main()
{
	global $PATH_INFO;
	// Init Sessions
	session_name('myMedSession_'.(defined('SESSIONNAME')?SESSIONNAME:'main'));
	session_start();
	
	// Http Headers
	header('Content-Script-Type:text/javascript');
	
	// Connexion Anonymous
	if(!isset($_SESSION['user']))
	{
		$_SESSION['user'] = new Profile;
		$_SESSION['user']->socialNetworkName	= null;
	}
	trace($_SESSION['user'],'$_SESSION[\'user\']', __FILE__, __LINE__);
	define('USER_CONNECTED', $_SESSION['user']->socialNetworkName!==null );
	
	// Load Template
	$subtemplateName	= 'Template'.ucfirst(basename($_SERVER['SCRIPT_NAME']));
	require_once __DIR__.'/subtemplates/'.$subtemplateName.'.class.php';
	
	if(!isset($_GET['template']) || !is_file(__DIR__.'/templates/'.$_GET['template'].'.php'))
	{
		$template	= 'main';
		unset($_GET['template']);
	}
	else
		$template	= $_GET['template'];
	$mainTemplate	= new MainTemplate(null, $template);
	$template		= new $subtemplateName();
	$mainTemplate->setContent($template);
	
	if(defined('CONTENTOBJECT'))
	{
		require_once CONTENTOBJECT.'.class.php';
		$contentClass = basename(CONTENTOBJECT);
		$template->setContent(new $contentClass());
	}
	else if(isset($PATH_INFO[1])&&$PATH_INFO[1])
	{
		$serviceFile = __DIR__.'/../services/'.$PATH_INFO[1].'/'.$PATH_INFO[1].'.class.php';
		if(is_file($serviceFile))
		{
			require_once($serviceFile);
			$className	= $PATH_INFO[1];
			$template->setContent(new $className());
		}
		else
		{
			require_once __DIR__.'/ServiceNotFound.class.php';
			$template->setContent(new ServiceNotFound());
		}
	}
	
	// Exec
	$mainTemplate->callTemplate();
}
?>
