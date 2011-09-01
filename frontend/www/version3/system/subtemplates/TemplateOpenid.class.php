<?php
require_once __DIR__.'/../ContentObject.class.php';
//require_once __DIR__.'/openiddata.tmp.php';
require_once __DIR__.'/../../socialNetworkAPIs/Auth/OpenID.php';
require_once __DIR__.'/../../socialNetworkAPIs/Auth/OpenID/Server.php';
require_once __DIR__.'/../backend/model/Authentication.class.php';
require_once __DIR__.'/../backend/AuthenticationRequest.class.php';
require_once __DIR__.'/../backend/ProfileRequest.class.php';
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
	private /*Auth_OpenID_Server*/ $server;
	private /*string*/ $user	= null;
	private /*array*/ $path	= null;
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
					$request	= new ProfileRequest();
					try
					{
						$this->user	= $request->read('myMed'.$this->httpScriptPath.'/'.PAGE_IDPAGE.'/'.@$this->path[2]);
					}
					catch(BackendRequestException $ex)
					{
						if($ex->getCode() != 404)
							throw $ex;
						
						header("Status: 404 Not Found", false, 404);
						exit;
					}
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
	/**
	 * @param PAGE_ $page	one of PAGE_'s constante
	 */
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
	private /*void*/ function firstServerRequest(Auth_OpenID_Request $request)
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
		<link rel="stylesheet" href="'.ROOTPATH.'style/desktop/design.openid.min.css" />';
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
				$regex_birthYear	= '\d\d\d\d';
				$regex_birthMonth	= '((0[0-9])|(1[0-2]))';
				$regex_birthDay		= '(([0-2][0-9])|(3[01]))';
				
				array_walk($_POST, function(&$value){$value=trim($value);});
				$post	= filter_var_array($_POST, Array(
					'login'		=> 0,
					'password'	=> 0,
					'password2'	=> 0,
					'lastName'	=> 0,
					'firstName'	=> 0,
					'email'		=> FILTER_VALIDATE_EMAIL,
					'gender'	=> array(
						'filter' => FILTER_VALIDATE_REGEXP, 
						'options' => array('regexp' => '#(^M|F$)|(^$)#')
					),
					'city'		=> FILTER_NULL_ON_FAILURE,
					'dob'		=> array(
						'filter' => FILTER_VALIDATE_REGEXP, 
						'options' => array('regexp' => "#(^$regex_birthYear-$regex_birthMonth-$regex_birthDay$)|(^$)#")
					)
				));
				$errors	= '';
				if(!$post['login'])		$errors.= '"Nom d\'utilisateur" invalide, ';
				if(!$post['password'])	$errors.= '"Mot de Passe" invalide, ';
				if(strlen($post['password'])<8)	$errors.= '"Mot de Passe" trop court, ';
				if($post['password']!=$post['password2'])$errors.= 'Les champs de mot de passe sont différent, ';
				if(!$post['lastName'])	$errors.= '"Nom" invalide, ';
				if(!$post['firstName'])	$errors.= '"Prénom" invalide, ';
				if(!$post['email'])		$errors.= '"Courriel" invalide, ';
				if($post['gender'] === false)		$errors.= '"Sexe" invalide, ';
				if($post['dob'] === false)		$errors.= '"Date de Naissance" invalide, ';
				$post['gender']	= $post['gender']?$post['gender']:null;
				$post['city']	= $post['city']?$post['city']:null;
				$post['dob']	= $post['dob']?$post['dob']:null;
				if($post['dob'])
				{
					$dob	= date_parse($post['dob']);
					if(!$dob || ($dob['year']<1880)||($dob['year']>(idate('Y')-6)))	$errors.= '"Date de Naissance" invalide, ';
				}
				trace($post,'$post', __FILE__, __LINE__);
				if($errors)
					$_SESSION['OpenIdProvider_error'] = $errors;
				else
				{
					$profile	= new Profile();
					$profile->socialNetworkID	= 'http://'.$_SERVER['SERVER_NAME'].ROOTPATH.'openid/'.PAGE_IDPAGE.'/'.$post['login'];
					$profile->socialNetworkName	= 'myMed';
					$profile->id	= $profile->socialNetworkName.$profile->socialNetworkID;
					$profile->login	= $post['login'];
					$profile->email	= $post['email'];
					$profile->name	= $post['firstName'].' '.$post['lastName'];
					$profile->firstName	= $post['firstName'];
					$profile->lastName	= $post['lastName'];
					$profile->birthday	= $post['dob'];
					$profile->hometown	= $post['city'];
					$profile->gender	= $post['gender'];
					$authentication	= new Authentication();
					$authentication->login	= $post['login'];
					$authentication->user	= $profile->id;
					$authentication->password	= hash('sha512', $post['password']);
					
					$request	= new AuthenticationRequest();
					$request->create($authentication, $profile);
					
					/*********************TODO!*************************/
					
					
					
					
					$_SESSION['OpenIdProvider_error'] = 'Les inscriptions sont désactivés';
				}
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
				$authenticationRequest	= new AuthenticationRequest();
				try
				{
					$profile			= $authenticationRequest->read(basename($urlID), @$_POST['password']);
				}
				catch(BackendRequestException $ex)
				{
					if($ex->getCode() != 404)
						throw $ex;
					$_SESSION['OpenIdProvider_error']	= 'Mauvais Identifiant / Mot de Passe';
					$this->internRedirect(PAGE_TRUST);
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
