<?php
require_once dirname(__FILE__).'/Connexion.class.php';
require_once dirname(__FILE__).'/Auth/OpenID/Consumer.php';
require_once dirname(__FILE__).'/Auth/OpenID/FileStore.php';
require_once dirname(__FILE__).'/Auth/OpenID/SReg.php';
define('OPENID_KEY_ID', 0);
define('OPENID_KEY_FULLNAME', 1);
define('OPENID_KEY_GENDER', 2);
define('OPENID_KEY_LANGUAGE', 3);
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
		$this->consumer = new Auth_OpenID_Consumer(new Auth_OpenID_FileStore('/tmp/oid_store'));
		static::tryConnect();
	}
	/**
	 * Try to connect on mymed with openid.
	 */
	protected /*void*/ function tryConnect()
	{
		if(isset($_REQUEST['connexion'], $_REQUEST['uri'])&&$_REQUEST['connexion']=='openid')
			$this->redirectProvider($_REQUEST['uri']);
		elseif(isset($_GET['janrain_nonce']))
		{
			$data	= $this->connect();
			$_SESSION['user'] = array(
					'id'				=> self::getField($data, OPENID_KEY_ID),
					'name'				=> self::getField($data, OPENID_KEY_FULLNAME),
					'gender'			=> self::getField($data, OPENID_KEY_GENDER),
					'locale'			=> self::getField($data, OPENID_KEY_LANGUAGE),
					'updated_time'		=> null,
					'profile'			=> self::getField($data, OPENID_KEY_ID),
					'profile_picture'	=> null,
					'social_network'	=> $this->social_network);
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
					return 'homme';
				elseif(strcasecmp($return,'F')===0)
					return 'femme';
					
			}break;
			case OPENID_KEY_LANGUAGE:
			{
				if(isset($data['sreg']['language']))
					return $data['sreg']['language'];
				elseif(isset($data['ax']['value.language']))
					return $data['ax']['value.language'];
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
				/*optional*/array('gender', 'language'))); // fonctionne sur myOpenId mais pas google
		// fonctionne sur google mais pas myOpenId
		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'mode', 'fetch_request');
		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'required', 'firstname,lastname,language,gender');
//		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'type.email', 'http://schema.openid.net/contact/email');
		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'type.firstname', 'http://axschema.org/namePerson/first');
		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'type.lastname', 'http://axschema.org/namePerson/last');
		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'type.language', 'http://axschema.org/pref/language');
		$auth->addExtensionArg('http://openid.net/srv/ax/1.0', 'type.gender', 'http://axschema.org/person/gender');
	}
	/**
	 * Redirect to login on provider
	 * @param $loginuri	URI entered by user to login on OpenId
	 */
	protected /*void*/ function redirectProvider($loginuri)
	{
		$auth = $this->consumer->begin($loginuri);
		if(!$auth)
			die('URI invalide !');
		static::initExtensions($auth);
	    
		unset($_GET['connexion']);
		unset($_GET['uri']);
		$callbackUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?'.http_build_query($_GET);
		if($auth->shouldSendRedirect())
		{
			$redirect = $auth->redirectURL('http://'.$_SERVER["HTTP_HOST"], $callbackUrl);
			header('Location: '.$redirect);
			echo '<a href="'.$redirect.'">'.$redirect.'</a>';
		}
		else
		{
			echo $auth->htmlMarkup('http://'.$_SERVER["HTTP_HOST"], $callbackUrl);
		}
		exit;
	}
	/**
	 * Called after connection on provider (isset($_GET['janrain_nonce']))
	 * @return an Array of 3 keys : 'id' (string) for user identifier 'sreg' (Array) for simple OpenId and 'ax' (Array) for advanced openid
	 */
	protected /*Array*/ function connect()
	{
		$response = $this->consumer->complete('http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
		if($response->status !== Auth_OpenID_SUCCESS)
			die('La connexion a échouée');
		return Array(
				'id'	=> $response->getDisplayIdentifier(),
				'sreg'	=> Auth_OpenID_SRegResponse::fromSuccessResponse($response)->contents(),
				'ax'	=> $response->extensionResponse('http://openid.net/srv/ax/1.0', false));
	}
	/**
	 * Permet de rediriger vers la page visitée avant la connexion
	 * Redirect to the page visited before login
	 */
	protected /*void*/ function redirectAfterConnection()
	{
		$encoded = json_encode($_SESSION['user']);
		file_get_contents(trim(BACKEND_URL."ProfileRequestHandler?act=0&user=" . urlencode($encoded)));
		unset($_GET['janrain_nonce']);
		foreach($_GET as $key=>$value)
			if(strrpos($key, 'openid')===0)
				unset($_GET[$key]);
		$query_string	= http_build_query($_GET);
		if($query_string != "")
			$query_string = '?'.$query_string;
		header('Location:'.$_SERVER["SCRIPT_NAME"].$query_string);
		exit;
	}
	/**
	 * Print the connexion's button
	 */
	public /*void*/ function button()
	{
		if(isset($_GET['connexion'])&&$_GET['connexion']=='openid')
		{
?>
		<form method="post" action="">
			<div class="hidden">
				<input type="hidden" name="connexion" value="openid" />
			</div>
			<div title="Entrez l'URI de votre OpenId">
				<label for="uri">URI&nbsp;:</label>
				<input 
					type="text" 
					name="uri" 
					id="uri" 
					placeholder="Entrez l'URI de votre OpenId" 
					size="24" 
					maxsize="255" />
			</div>
			<div class="submitRow">
				<div></div>
				<div>
					<a class="cancel" href="<?=ROOTPATH?>">Annuler</a>
					<button type="submit">Connecter</button>
				</div>
			</div>
		</form>
<?php
		}
		else
		{
?>
		<a href="?connexion=openid" class="openid"><span>OpenId</span></a>
<?php
		}
	}
}
?>
