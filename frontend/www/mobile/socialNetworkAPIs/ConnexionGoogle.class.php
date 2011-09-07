<?php
require_once __DIR__.'/ConnexionAutoOpenId.class.php';
require_once __DIR__.'/Auth/OAuth.php';
/**
 * A class to define a Google login
 * @author blanchard
 */
class ConnexionGoogle extends ConnexionAutoOpenId
{
	protected $social_network = 'Google';
	private	$oAuthConsumer;
	public function __construct()
	{
		if(isset($_REQUEST['connexionProvider'])&&$_REQUEST['connexionProvider']==$this->social_network)
		{
			$this->oAuthConsumer	= new OAuthConsumer($_SERVER['HTTP_HOST'], ConnexionGoogle_SECRET);
			parent::__construct();
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
			$_SESSION['user']->birthday			= self::getField($data, OPENID_KEY_DATEOFBIRTH);
			if(isset($_REQUEST['openid_ext2_request_token']))
			{
				$accessToken	= $this->getAccessToken($_REQUEST['openid_ext2_request_token']);
				// for parameters see http://opensocial-resources.googlecode.com/svn/spec/1.1/Core-API-Server.xml#Standard-Request-Parameters
				$friends	= json_decode($this->oauthRequest($accessToken, 'http://www-opensocial.googleusercontent.com/api/people/@me/@all?format=json&count=1000'), true);//@todo if friends > 1000
				$_SESSION['friends']	= $friends['entry'];
				$self		= json_decode($this->oauthRequest($accessToken, 'http://www-opensocial.googleusercontent.com/api/people/@me/@self?format=json'), true);
				$_SESSION['user']->profilePicture	= $self['entry']['thumbnailUrl'];
				$_SESSION['user']->link				= $self['entry']['profileUrl'];
				$_SESSION['user']->firstName		= $self['entry']['name']['givenName'];
				$_SESSION['user']->lastName			= $self['entry']['name']['familyName'];
			}
			else
				trigger_error('DNS not register on Google', E_USER_NOTICE);
			$this->redirectAfterConnection();
		}
	}
	/**
	 * Send an OAuth 1.0 Request
	 * @param OAuthToken $accessToken	OAuth 1.0 access token
	 * @param string $url	URL of request (with http://)
	 * @return string	result of request
	 */
	private /*string*/ function oauthRequest(OAuthToken $accessToken, /*string*/ $url)
	{
		$req = OAuthRequest::from_consumer_and_token($this->oAuthConsumer, $accessToken, 'GET', $url, NULL);
		$req->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $this->oAuthConsumer, $accessToken);
		return $this->send_signed_request($req->get_normalized_http_method(), $url, $req->to_header(), NULL, false);
	}
	/**
	 * Get OAuth 1.0 Access token from OAuth 1.0 request token
	 * @param string $request_token	OAuth 1.0 request token geted from OpenId answer
	 * @return OAuthToken	OAuth 1.0 Access token to be used in oauthRequest()
	 */
	private /*OAuthToken*/ function getAccessToken(/*string*/ $request_token)
	{
		$token			= new OAuthToken($request_token, NULL);
		$token_endpoint	= 'https://www.google.com/accounts/OAuthGetAccessToken';
		$request		= OAuthRequest::from_consumer_and_token($this->oAuthConsumer, $token, 'GET', $token_endpoint);
		$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $this->oAuthConsumer, $token);
		
		$response = $this->send_signed_request($request->get_normalized_http_method(), $token_endpoint, $request->to_header(), NULL, false);
		
		// Parse out oauth_token (access token) and oauth_token_secret
		preg_match('/oauth_token=(.*)&oauth_token_secret=(.*)/', $response, $matches);
		return new OAuthToken(urldecode($matches[1]), urldecode($matches[2]));
	}
	/**
	 * Makes an HTTP request to the specified URL
	 *
	 * @param string $http_method The HTTP method (GET, POST, PUT, DELETE)
	 * @param string $url Full URL of the resource to access
	 * @param string $auth_header (optional) Authorization header
	 * @param string $postData (optional) POST/PUT request body
	 * @param bool $returnResponseHeaders True if resp. headers should be returned.
	 * @return string Response body from the server
	 */
	private /*string*/ function send_signed_request(/*string*/ $http_method, /*string*/ $url, /*string*/ $auth_header=null,	/*string*/ $postData=null, /*bool*/ $returnResponseHeaders=true)
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		if ($returnResponseHeaders)
			curl_setopt($curl, CURLOPT_HEADER, true);
		switch($http_method)
		{
			case 'GET':
				if ($auth_header)
					curl_setopt($curl, CURLOPT_HTTPHEADER, array($auth_header));
				break;
			case 'POST':
				$headers = array('Content-Type: application/atom+xml', $auth_header);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
				break;
			case 'PUT':
				$headers = array('Content-Type: application/atom+xml', $auth_header);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $http_method);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
				break;
			case 'DELETE':
				$headers = array($auth_header);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $http_method);
				break;
		}
		$response = curl_exec($curl);
		if (!$response)
			$response = curl_error($curl);
		curl_close($curl);
		return $response;
	}
	/**
	 * Function called to choose OpenId extensions
	 * @param Auth_OpenID_AuthRequest $auth	variable initialized by $this->consumer->begin()
	 */
	protected /*void*/ function initExtensions(Auth_OpenID_AuthRequest $auth)
	{
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
		// OAuth
		$auth->addExtensionArg('http://specs.openid.net/extensions/oauth/1.0', 'consumer', $_SERVER['HTTP_HOST']);
		$auth->addExtensionArg('http://specs.openid.net/extensions/oauth/1.0', 'scope', 'https://www.google.com/m8/feeds/ http://www-opensocial.googleusercontent.com/api/people/');
	}
	/**
	 * Récupère l'URL à appeler par OpenId pour la connexion
	 * Get provider's URL to be used by OpenID Authentification
	 * @return string	Provider's URL
	 */
	public /*string*/ function getProviderUrl()
	{
		return 'https://www.google.com/accounts/o8/id';
	}
}
?>
