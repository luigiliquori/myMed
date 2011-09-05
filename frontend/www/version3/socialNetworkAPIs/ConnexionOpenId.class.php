<?php
require_once __DIR__.'/Connexion.class.php';
require_once __DIR__.'/Auth/OpenID/Consumer.php';
require_once __DIR__.'/Auth/OpenID/FileStore.php';
require_once __DIR__.'/Auth/OpenID/SReg.php';
require_once __DIR__.'/../system/backend/ProfileRequest.class.php';
define('OPENID_KEY_ID', 0);
define('OPENID_KEY_FULLNAME', 1);
define('OPENID_KEY_GENDER', 2);
define('OPENID_KEY_LANGUAGE', 3);
define('OPENID_KEY_DATEOFBIRTH', 4);
define('OPENID_KEY_URLAVATAR', 5);
/**
 * A class to define a OpenId login
 * @author blanchard
 */
class ConnexionOpenId extends Connexion
{
	protected $consumer;
	protected $social_network = 'OpenId';
	public function __construct()
	{
		if(!isset($_REQUEST['connexionProvider'])
			||$_REQUEST['connexionProvider']=='OpenId'
			||$this->social_network!='OpenId')
		{
			$this->consumer = new Auth_OpenID_Consumer(new Auth_OpenID_FileStore('/tmp/oid_store'));
			static::tryConnect();
		}
	}
	/**
	 * Try to connect on mymed with openid.
	 */
	protected /*void*/ function tryConnect()
	{
		if(isset($_REQUEST['connexion'], $_REQUEST['oidUri'])&&$_REQUEST['connexion']=='openid')
			$this->redirectProvider($_REQUEST['oidUri']);
		elseif(isset($_GET['janrain_nonce']))
		{
			$data	= $this->connect();
			$_SESSION['user'] = new Profile;
			$_SESSION['user']->id			= $this->social_network.self::getField($data, OPENID_KEY_ID);
			$_SESSION['user']->socialNetworkID	= self::getField($data, OPENID_KEY_ID);
			$_SESSION['user']->socialNetworkName= $this->social_network;
			$_SESSION['user']->name				= self::getField($data, OPENID_KEY_FULLNAME);
			$_SESSION['user']->gender			= self::getField($data, OPENID_KEY_GENDER);
			$_SESSION['user']->link				= self::getField($data, OPENID_KEY_ID);
			$_SESSION['user']->birthday			= self::getField($data, OPENID_KEY_DATEOFBIRTH);
			$_SESSION['user']->profilePicture	= self::getField($data, OPENID_KEY_URLAVATAR);
			//var_dump($data);var_dump($_SESSION);exit;
			$this->redirectAfterConnection();
		}
	}
	protected static /*string*/ function getField(/*Array(form connect())*/ $data, /*KEY*/ $key, /*string*/ $default=null)
	{
		switch($key)
		{
			case OPENID_KEY_ID:
			{
				return $data['id'];
			}break;
			case OPENID_KEY_FULLNAME:
			{
				if(isset($data['sreg']['fullname']))
					return $data['sreg']['fullname'];
				else if(isset($data['ax']['value.fullname']))
					return $data['ax']['value.fullname'];
				else
				{
					$return = @$data['ax']['value.firstname'];
					if($return)
						$return .= ' ';
					$return .= @$data['ax']['value.lastname'];
					if($return)
						return $return;
				}
			}break;
			case OPENID_KEY_GENDER:
			{
				$return = '';
				if(isset($data['sreg']['gender']))
					$return = $data['sreg']['gender'];
				elseif(isset($data['ax']['value.gender']))
					$return = $data['ax']['value.gender'];
				if(strcasecmp($return,'M')===0)
					return 'M';
				elseif(strcasecmp($return,'F')===0)
					return 'F';
					
			}break;
			case OPENID_KEY_LANGUAGE:
			{
				if(isset($data['sreg']['language']))
					return $data['sreg']['language'];
				elseif(isset($data['ax']['value.language']))
					return $data['ax']['value.language'];
			}break;
			case OPENID_KEY_DATEOFBIRTH:
			{
				if(isset($data['sreg']['dob']))
					return $data['sreg']['dob'];
				elseif(isset($data['ax']['value.dob']))
					return $data['ax']['value.dob'];
			}break;
			case OPENID_KEY_URLAVATAR:
			{
				if(isset($data['ax']['value.image']))
					return $data['ax']['value.image'];
			}break;
		}
		return $default;
	}
	/**
	 * Function called to OpenId extensions
	 * @param $auth variable initialized by $this->consumer->begin()
	 */
	protected /*void*/ function initExtensions(/*Auth_OpenID_AuthRequest*/ $auth)
	{
		$auth->addExtension(Auth_OpenID_SRegRequest::build(
				/*required*/array('fullname'), 
				/*optional*/array('gender', 'dob'))); // fonctionne sur myOpenId mais pas google
		// fonctionne sur google mais pas myOpenId
		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'mode', 'fetch_request');
		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'required', 'fullname,firstname,lastname,gender,dob,image');
//		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'type.email', 'http://schema.openid.net/contact/email');
		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'type.fullname', 'http://axschema.org/namePerson');
		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'type.firstname', 'http://axschema.org/namePerson/first');
		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'type.lastname', 'http://axschema.org/namePerson/last');
//		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'type.language', 'http://axschema.org/pref/language');
		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'type.gender', 'http://axschema.org/person/gender');
		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'type.dob', 'http://axschema.org/birthDate');
		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'type.image', 'http://axschema.org/media/image/default');
	}
	/**
	 * Redirect to login on provider
	 * @param $loginuri	URI entered by user to login on OpenId
	 */
	protected /*void*/ function redirectProvider($loginuri)
	{
		$auth = $this->consumer->begin($loginuri);
		if(!$auth)
			sendError('URI invalide !', true);
		static::initExtensions($auth);
	    
		unset($_GET['connexion']);
		unset($_GET['oidUri']);
		$callbackUrl = 'https://'.$_SERVER['HTTP_HOST'].preg_replace('#\\?.*$#', '', $_SERVER['REQUEST_URI']).'?'.http_build_query($_GET);
		if($auth->shouldSendRedirect())
		{
			$redirect = $auth->redirectURL('https://'.$_SERVER["HTTP_HOST"], $callbackUrl);
			header('Location: '.$redirect);
			echo '<a href="'.$redirect.'">'.$redirect.'</a>';
		}
		else
		{
			echo $auth->htmlMarkup('https://'.$_SERVER["HTTP_HOST"], $callbackUrl);
		}
		exit;
	}
	/**
	 * Called after connection on provider (isset($_GET['janrain_nonce']))
	 * @return an Array of 3 keys : 'id' (string) for user identifier 'sreg' (Array) for simple OpenId and 'ax' (Array) for advanced openid
	 */
	protected /*Array*/ function connect()
	{
		$response = $this->consumer->complete('https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
		if($response->status !== Auth_OpenID_SUCCESS)
			sendError('La connexion a échouée', true);
		return Array(
				'id'	=> $response->getDisplayIdentifier(),
				'sreg'	=> Auth_OpenID_SRegResponse::fromSuccessResponse($response)->contents(),
				'ax'	=> $response->extensionResponse('http://openid.net/srv/ax/1.0', false));
	}
	
	protected /*void*/ function cleanGetVars()
	{
		unset($_GET['connexionProvider']);
		unset($_GET['janrain_nonce']);
		foreach($_GET as $key=>$value)
			if(strrpos($key, 'openid')===0)
				unset($_GET[$key]);
	}
	private /*int*/ $buttonDisplayedNumber = 0;
	/**
	 * Print the connexion's button
	 */
	public /*void*/ function button()
	{
?><form method="post" action="" class="openid"><!--
			--><div class="hidden"><!--
				--><input type="hidden" name="connexion" value="openid" /><!--
			--></div><!--
			--><div title="Entrez l'URI de votre OpenId"><!--
				--><label for="oidUri<?=$this->buttonDisplayedNumber?>"><span>URI&#160;:</span></label><!--
				--><input 
					type="text" 
					name="oidUri" 
					id="oidUri<?=$this->buttonDisplayedNumber?>" 
					placeholder="Entrez votre URI OpenId" 
					size="24" 
					maxsize="255" /><!--
			--></div><!--
			--><div class="submitRow"><!--
				--><div></div><!--
				--><div><!--
					<a class="cancel" href="<?=ROOTPATH?>">Annuler</a>
					--><button type="submit"><span>Connecter</span></button><!--
				--></div><!--
			--></div><!--
		--></form><?php
		$this->buttonDisplayedNumber++;
	}
}
?>
