<?php
require_once dirname(__FILE__).'/../system/ContentObject.class.php';
require_once dirname(__FILE__).'/openiddata.tmp.php';
require_once dirname(__FILE__).'/../socialNetworkAPIs/Auth/OpenID.php';
require_once dirname(__FILE__).'/../socialNetworkAPIs/Auth/OpenID/Server.php';
define('PAGE_LOGOUT', 		'logout');
define('PAGE_SUBSCRIBE', 	'subscribe');
define('PAGE_TRUST', 		'trust');
define('PAGE_IDPAGE', 		'idpage');
define('PAGE_IDPXRDS', 		'idpXrds');
define('PAGE_USERXRDS', 	'userXrds');
class OpenIdProvider extends ContentObject
{
	private /*string*/ $httpScriptPath;
	private /*string*/ $server;
	private /*string*/ $user	= null;
	private /*string*/ $path	= null;
	public function __construct()
	{
		$this->httpScriptPath	= 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];//preg_replace('#\.php$#', '', $_SERVER['SCRIPT_NAME']);
		$this->server			= new Auth_OpenID_Server(
						new Auth_OpenID_FileStore('/tmp/oidprovider_store'), 
						$this->httpScriptPath);
		if(isset($_SERVER['PATH_INFO']))
			$this->path			= explode('/', $_SERVER['PATH_INFO']);
		
		
		if(isset($this->path[1]))
		{
			switch($this->path[1])
			{
				case PAGE_LOGOUT	:
				{
					unset($_SESSION['request']);
					authCancel(null);
					$this->internRedirect(PAGE_SUBSCRIBE);
				}break;
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
				}break;
				case PAGE_IDPXRDS	:	$this->getIdpXrds();	exit;
				case PAGE_USERXRDS	:	$this->getIUserXrds();	exit;
				default:				header("Status: 404 Not Found", false, 404);exit;
			}
		}
		else
		{
		    header('X-XRDS-Location: '.$this->httpScriptPath.'/'.PAGE_IDPXRDS);
			$request = $this->server->decodeRequest();
			if($request == null)
				// if no request, it's an user => redirect to subscribe's page
				$this->path = Array('', PAGE_SUBSCRIBE);//$this->internRedirect(PAGE_SUBSCRIBE);
			else
				$this->firstServerRequest($request);
		}
	}
	private /*void*/ function internRedirect(/*PAGE_*/ $page=null)
	{
		header('Location:'.$this->httpScriptPath.($page?'/'.$page:''));
		exit;
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
		return 'Connexion à myMed';
	}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		echo '
		<base href="'.$this->httpScriptPath.'" />
		<link rel="stylesheet" href="OpenIdProvider/style.css" />';
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
		<link rel="openid2.provider openid.server" href="'.$this->httpScriptPath.'" />
		<meta http-equiv="X-XRDS-Location" content="'.$this->httpScriptPath.'/userXrds/'.$this->user.'" />';
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
				require dirname(__FILE__).'/views/subscribe.view.php';
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
				require dirname(__FILE__).'/views/login.view.php';
			}break;
			case PAGE_IDPAGE	:
			{
				$profile	= $this->user;
				require dirname(__FILE__).'/views/profile.view.php';
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
			}break;
			case PAGE_TRUST	:
			{
				$request	= unserialize($_SESSION['request']);
				if(isset($_POST['cancel']))
				{
					unset($_SESSION['request']);
					header('Location:'.$request->getCancelURL());
					exit;
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
				$sregData	= Array();
				$sregData['nickname']	= $profile->name;
				$sregData['fullname']	= $profile->firstName.' '.$profile->lastName;
				$sregData['email']		= $profile->email;
				$sregData['dob']		= $profile->birthday;
				$sregData['gender']		= $profile->gender;//M|F
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