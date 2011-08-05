<?php
require_once __DIR__.'/../ContentObject.class.php';
require_once __DIR__.'/openiddata.tmp.php';
require_once __DIR__.'/../../socialNetworkAPIs/Auth/OpenID.php';
require_once __DIR__.'/../../socialNetworkAPIs/Auth/OpenID/Server.php';
define('PAGE_LOGOUT', 		'logout');
define('PAGE_SUBSCRIBE', 	'subscribe');
define('PAGE_TRUST', 		'trust');
define('PAGE_IDPAGE', 		'idpage');
define('PAGE_IDPXRDS', 		'idpXrds');
define('PAGE_USERXRDS', 	'userXrds');
/**
 * A class to define template for /openid
 */
class TemplateOpenid extends ContentObject
{
	private /*string*/ $httpScriptPath;
	private /*string*/ $httpsScriptPath;
	private /*string*/ $scriptPath;
	private /*string*/ $server;
	private /*string*/ $user	= null;
	private /*string*/ $path	= null;
	public function __construct()
	{
		$this->scriptPath	= //*
						$_SERVER['SCRIPT_NAME'];//*/preg_replace('#\.php$#', '', $_SERVER['SCRIPT_NAME']);
		$this->httpScriptPath	= 'http://'.$_SERVER['SERVER_NAME'].$this->scriptPath;
		$this->httpsScriptPath	= 'https://'.$_SERVER['SERVER_NAME'].$this->scriptPath;
		$this->server			= new Auth_OpenID_Server(
						new Auth_OpenID_FileStore('/tmp/oidprovider_store'), 
						$this->httpScriptPath);
		if(isset($_SERVER['PATH_INFO']))
			$this->path			= explode('/', $_SERVER['PATH_INFO']);
		
		
		switch(@$this->path[1])
		{
			case PAGE_IDPXRDS	:	
			case PAGE_USERXRDS	:	break;
			default:
				header('X-XRDS-Location: '.$this->httpScriptPath.'/'.PAGE_IDPXRDS);
		}
		if(isset($this->path[1]))
		{
			switch($this->path[1])
			{/*
				case PAGE_LOGOUT	:
				{
					unset($_SESSION['request']);
					===DELETE SESSION===
					$this->internRedirect(PAGE_SUBSCRIBE);
				}break;//*/
				case PAGE_TRUST	:
				{
					if(!isset($_SESSION['request']))
						$this->internRedirect();
				}break;
				case PAGE_IDPAGE	:
				{
					$this->user	= getUserByLogin(@$this->path[2]);
					if($this->user===null)
					{
						header("Status: 404 Not Found", false, 404);
						exit;
					}
					else
						header('X-XRDS-Location: '.$this->httpScriptPath.'/'.PAGE_USERXRDS.'/'.$this->user);
				}break;
				case PAGE_SUBSCRIBE	:	break;
				case PAGE_IDPXRDS	:	$this->getIdpXrds();	exit;
				case PAGE_USERXRDS	:	$this->getIUserXrds();	exit;
				default:				header("Status: 404 Not Found", false, 404);die('<html><head><title>404 Not Found</title></head><body><h1>404 Not Found</h1><p>Your request ressource that never exists or that has been remved&nbsp;!</p><a href="'.ROOTPATH.'">Home</a></body></html>');
			}
		}
		else
		{
			$request = $this->server->decodeRequest();
			if($request == null)
				// if no request, it's an user => redirect to subscribe's page
				//$this->path = Array('', PAGE_SUBSCRIBE);//$this->internRedirect(PAGE_SUBSCRIBE);
				die('<!DOCTYPE html>'."\n"
				.'<html xmlns="http://www.w3.org/1999/xhtml">'."\n"
				.'	<head>'."\n"
				.'		<title>OpenId myMed</title>'."\n"
				.'		<meta http-equiv="refresh" content="0; url='.$this->httpsScriptPath.'/'.PAGE_SUBSCRIBE.'" />'."\n"
				.'	</head>'."\n"
				.'	<body>'."\n"
				.'		<a href="'.$this->httpsScriptPath.'/'.PAGE_SUBSCRIBE.'">S\'inscrire</a>'."\n"
				.'	</body>'."\n"
				.'</html>');// redirection HTML => les robot openid ne son pas redirigé
				//$this->internRedirect(PAGE_SUBSCRIBE);
			else
				$this->firstServerRequest($request);
		}
	}
	private /*void*/ function internRedirect(/*PAGE_*/ $page=null)
	{
		switch($page)
		{
			case PAGE_SUBSCRIBE:
			case PAGE_TRUST:
					$basePath = $this->httpsScriptPath;break;
			default: 
					$basePath = $this->httpScriptPath;break;
		}
		httpRedirect($basePath.($page?'/'.$page:''));
	}
	private /*void*/ function firstServerRequest(/*retour de server->decodeRequest()*/ $request)
	{
		$_SESSION['request'] = serialize($request);
		if($request->mode=='checkid_immediate'||$request->mode=='checkid_setup')
		{
			if ($request->idSelect())
			{
				if ($request->mode == 'checkid_immediate')
					$response = $request->answer(false);
				else
					$this->internRedirect(PAGE_TRUST);
			}
			else if(!$request->identity)
			{
				header("Status: 404 Not Found", false, 404);
				echo 'You did not send an identifier with the request, and it was not an identifier selection request. Please return to the relying party and try again.';
				exit;
			}
			else if ($request->immediate)
				$response = $this->httpScriptPath;
			else
				$this->internRedirect(PAGE_TRUST);
		}
		else
			$response = $this->server->handleRequest($request);
		
		$webresponse = $this->server->encodeResponse($response);
		
		if ($webresponse->code != AUTH_OPENID_HTTP_OK)
			header('HTTP/1.1 '.$webresponse->code.' ', true, $webresponse->code);
		
		foreach ($webresponse->headers as $key => $value)
			header("$key: $value");
		echo $webresponse->body;
		exit;
	}
	private /*void*/ function getIdpXrds()
	{
		header('Content-type: application/xrds+xml');
		echo '<?xml version="1.0" encoding="UTF-8"?>
<xrds:XRDS xmlns:xrds="xri://$xrds" xmlns="xri://$xrd*($v*2.0)">
	<XRD>
		<Service priority="0">
			<Type>'.Auth_OpenID_TYPE_2_0_IDP.'</Type>
			<URI>'.$this->httpScriptPath.'</URI>
		</Service>
	</XRD>
</xrds:XRDS>';
		exit;
	}
	private /*void*/ function getIUserXrds()
	{
		header('Content-type: application/xrds+xml');
		echo '<?xml version="1.0" encoding="UTF-8"?>
<xrds:XRDS xmlns:xrds="xri://$xrds" xmlns="xri://$xrd*($v*2.0)">
	<XRD>
		<Service priority="0">
			<Type>'.Auth_OpenID_TYPE_2_0.'</Type>
			<Type>'.Auth_OpenID_TYPE_1_1.'</Type>
			<URI>'.$this->httpScriptPath.'</URI>
		</Service>
	</XRD>
</xrds:XRDS>';
		exit;
	}
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle()
	{
		return 'Authentification OpenId';
	}
	/**
	 * Print content's tags to be put inside \<head\> tag
	 */
	public /*void*/ function headTags()
	{
		echo '
		<link rel="stylesheet" href="'.ROOTPATH.'style/OpenIdProvider/style.css" />';
		switch($this->path[1])
		{
			case PAGE_SUBSCRIBE	:
			{
			}break;
			case PAGE_TRUST	:
			{
			}break;
			case PAGE_IDPAGE	:
			{
				echo '
		<link rel="openid2.provider openid.server" href="'.$this->httpScriptPath.'" />';
			}break;
		}
			
	}
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags(){}
	/**
	 * Print page's main content when page called with GET method
	 */
	public /*void*/ function contentGet()
	{
		switch($this->path[1])
		{
			case PAGE_SUBSCRIBE	:
			{
				if(isset($_SESSION['OpenIdProvider_error']))
				{
					echo '<p class="error">'.$_SESSION['OpenIdProvider_error'].'</p>';
					unset ($_SESSION['OpenIdProvider_error']);
				}
				require __DIR__.'/openid-views/subscribe.view.php';
			}break;
			case PAGE_TRUST	:
			{
				if(isset($_SESSION['OpenIdProvider_error']))
				{
					$error	= $_SESSION['OpenIdProvider_error'];
					unset ($_SESSION['OpenIdProvider_error']);
					echo '<p class="error">'.$error.'</p>';
				}
				$request	= unserialize($_SESSION['request']);
				if(!$request->idSelect())
				{
					$loginattribut	= ' value="'.basename($request->identity).'"';
					if(!isset($error))
						$loginattribut	.=' readonly="readonly"';
				}
				else
					$loginattribut = '';
				$showReqOptFields = $request->message->getArg('http://specs.openid.net/auth/2.0', 'realm') !== 'http://'.$_SERVER['SERVER_NAME'];
				$reqFields	= array_map('trim', explode(',', $request->message->getArg('http://openid.net/extensions/sreg/1.1', 'required')));
				$optFields	= array_map('trim', explode(',', $request->message->getArg('http://openid.net/extensions/sreg/1.1', 'optional')));
				require __DIR__.'/openid-views/login.view.php';
			}break;
			case PAGE_IDPAGE	:
			{
				$profile	= $this->user;
				require __DIR__.'/openid-views/profile.view.php';
			}break;
		}
	}
	/**
	 * Called page called with POST method, Can't print anything
	 * After : redirect to GET
	 * default : do nothing
	 */
	public /*void*/ function contentPost()
	{
	
		switch($this->path[1])
		{
			case PAGE_SUBSCRIBE	:
			{
				$_SESSION['OpenIdProvider_error'] = 'Les inscriptions sont désactivés';
			}break;
			case PAGE_TRUST	:
			{
				$request	= unserialize($_SESSION['request']);
				if(isset($_POST['cancel']))
				{
					unset($_SESSION['request']);
					httpRedirect($request->getCancelURL());
				}
				$urlID	= $request->identity;
				if($request->idSelect())
				{
					if(!isset($_POST['login'])||!$_POST['login'])
					{
						$_SESSION['OpenIdProvider_error']	= 'Login requit';
						$this->internRedirect(PAGE_TRUST);
						exit;
					}
					else
					{
						$urlID = $this->httpScriptPath.'/'.PAGE_IDPAGE.'/'.$_POST['login'];
					}
				}
				$profile	= getUserByLogin(basename($urlID));
				if($profile== null || $profile->password !== $_POST['password'])
				{
					$_SESSION['OpenIdProvider_error']	= 'Mauvais Identifiant / Mot de Passe';
					$this->internRedirect(PAGE_TRUST);
					exit;
				}
				$response = $request->answer(true, null, $urlID);
				// @todo gérer les choix de l'utilisateur
				$reqFields	= array_map('trim', explode(',', $request->message->getArg('http://openid.net/extensions/sreg/1.1', 'required')));
				foreach($reqFields as $field)
					$_POST[$field] = 'on';
				$sregData	= Array();
				if(isset($_POST['nickname']))	$sregData['nickname']	= $profile->name;
				if(isset($_POST['fullname']))	$sregData['fullname']	= $profile->firstName.' '.$profile->lastName;
				if(isset($_POST['email']))		$sregData['email']		= $profile->email;
				if(isset($_POST['dob']))		$sregData['dob']		= $profile->birthday;
				if(isset($_POST['gender']))		$sregData['gender']		= $profile->gender;//M|F
				//$sregData['postcode']	= ;
				//$sregData['country']	= ;
				//$sregData['language']	= ;
				//$sregData['timezone']	= ;
				$sregResponse = Auth_OpenID_SRegResponse::extractResponse(
										Auth_OpenID_SRegRequest::fromOpenIDRequest($request), $sregData);
				$sregResponse->toMessage($response->fields);
				$webresponse = $this->server->encodeResponse($response);
				foreach ($webresponse->headers as $key => $value)
					header("$key: $value");
				echo $webresponse->body;
				exit;
			}break;
			case PAGE_IDPAGE	:
			{
			}break;
		}
	}
}
?>
