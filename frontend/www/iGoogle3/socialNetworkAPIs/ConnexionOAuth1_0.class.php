<?php
require_once dirname(__FILE__).'/Connexion.class.php';
require_once dirname(__FILE__).'/Auth/OAuth.php';
/**
 * A class to define a OAuth 1.0 login
 * @author blanchard
 */
abstract class ConnexionOAuth1_0 extends Connexion
{
	private		/*OAuthToken*/		$accessToken;
	private		/*OAuthToken*/		$requestToken;
	protected	/*string*/			$scope	= null;
	public function __construct()
	{
		
		if(isset($_GET['connexion'])&&$_GET['connexion']==static::getSocialNetworkName()) // si l'utilisateur a cliqué sur le bouton Myspace
			$this->redirect();
		if(isset($_GET['state']) && $_GET['state']===static::getSocialNetworkName())
		{
			if(isset($_SESSION['requestToken']))
				$this->requestToken	= new OAuthToken($_SESSION['requestToken']['key'], $_SESSION['requestToken']['secret']);
			if(isset($_SESSION['accessToken']))
				$this->accessToken	= new OAuthToken($_SESSION['accessToken']['key'], $_SESSION['accessToken']['secret']);
			elseif(!USER_CONNECTED && $accessToken = $this->connect())
			{
				static::createProfileAndFriendsInstances();
				$this->redirectAfterConnection();
			}
		}
	}
	protected /*void*/ function cleanGetVars()
	{
		unset($_GET['oauth_token']);
		unset($_GET['oauth_verifier']);
		unset($_GET['state']);
		unset($_SESSION['accessToken']);// access token not use after
	}
	private /*void*/ function getRequestToken(/*string*/ $callbackUrl)// throws CUrlException, HttpException
	{
		$params = Array('oauth_callback' => $callbackUrl);
		if($this->scope)
			$params['scope']	= $this->scope;
		$response	= $this->httpSignedGet(static::getRequestTokenUrl(), null, $params);
		$token = OAuthUtil::parse_parameters($response);
		$_SESSION['requestToken']['key']	= $token['oauth_token'];
		$_SESSION['requestToken']['secret']	= $token['oauth_token_secret'];
		$this->requestToken	= new OAuthToken($token['oauth_token'], $token['oauth_token_secret']);
	}
	private /*void*/ function getAccessToken(/*string*/ $verifier)// throws CUrlException, HttpException
	{
		$response	= $this->httpSignedGet(static::getAccessTokenUrl(), null, Array('oauth_verifier'=>$verifier));
		$token = OAuthUtil::parse_parameters($response);
		unset($_SESSION['requestToken']);
		$_SESSION['accessToken']['key']		= $token['oauth_token'];
		$_SESSION['accessToken']['secret']	= $token['oauth_token_secret'];
		$this->accessToken	= new OAuthToken($token['oauth_token'], $token['oauth_token_secret']);
	}
	private /*string*/ function getCallBackUrl()
	{
		//return 'http://'.$_SERVER["HTTP_HOST"].ROOTPATH.'?state='.urlencode(static::getSocialNetworkName());
		$get	= $_GET;
		unset($get['oauth_token']);
		unset($get['oauth_verifier']);
		unset($get['connexion']);
		$get['state']	= static::getSocialNetworkName();
		$query_string	= http_build_query($get);
		if($query_string != "")
			$query_string = '?'.$query_string;
		return 'http://'.$_SERVER['HTTP_HOST'].preg_replace('#\\?.*$#', '', $_SERVER['REQUEST_URI']).$query_string;
	}
	private /*void*/ function redirect()
	{
		try
		{
			$callbackUrl = $this->getCallBackUrl();
			$this->getRequestToken($callbackUrl);
			$redirection	= OAuthRequest::from_request('GET', static::getAuthorizeUrl(), Array('oauth_token'=>$this->requestToken->key, 'oauth_callback'=>$callbackUrl));
			$redirection->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), static::getOAuthConsumer(), $this->requestToken);
			header('Location: '.$redirection->to_url());
			exit;
		}
		catch(Exception $ex)
		{
			if(defined('DEBUG')&&DEBUG)
				throw $ex;	// renvoyer l'erreur pour le débuggage
			else
				sendError('Impossible de se connecter à '.static::getSocialNetworkName().'... Merci de renouveler votre demande plus tard.', true);
		}
	}
	private /*bool*/ function connect()
	{
		if(isset($this->accessToken))
			return true;
		elseif(isset($_GET['oauth_token']) && $this->requestToken && $this->requestToken->key === urldecode($_GET['oauth_token']))
		{
			$this->getAccessToken($_GET['oauth_verifier']);
			return true;
		}
		else
			return false;
	}
	protected /*string*/ function httpSignedGet(/*string*/ $url, array $getParams = Array(), array $authParam=Array())
	{
		if(isset($this->accessToken))
			$token	= $this->accessToken;
		elseif(isset($this->requestToken))
			$token	= $this->requestToken;
		else
			$token	= null;
		$request	= OAuthRequest::from_consumer_and_token(static::getOAuthConsumer(), $token, 'GET', $url, $authParam);
		$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), static::getOAuthConsumer(), $token);
		return $this->send_signed_request($request, $getParams);
	}
	/**
	 * Makes an HTTP request to the specified URL
	 *
	 * @param OAuthRequest $request signed request
	 * @param array $getParams additinnal parametters to add to url
	 * @return string Response body from the server
	 */
	private /*string*/ function send_signed_request(OAuthRequest $request, array $getParams = Array())// throws CUrlException, HttpException
	{
		$http_method	= $request->get_normalized_http_method();
		$url			= $request->get_normalized_http_url();
		$auth_header	= $request->to_header(null);
		$postData		= $request->to_postdata();
		$curl = curl_init($url.($getParams?'?'.http_build_query($getParams):''));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

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
			$ex = new CUrlException(curl_error($curl), curl_errno($curl));
		$httpCode	= curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($httpCode<200 || $httpCode>=300)
			$ex = new HttpException($httpCode, $response);
		curl_close($curl);
		if(isset($ex))
			throw $ex;
		return $response;
	}
	/**
	 * Print the connexion's button
	 */
	public /*void*/ function button()
	{
		// remove parasit GET vars
		$get	= $_GET;
		unset($get['connexion']);
		$query_string	= http_build_query($get);
		if($query_string != "")
			$query_string = '&'.$query_string;
?><a href="?connexion=<?=urlencode(static::getSocialNetworkName())?><?=htmlspecialchars($query_string)?>" class="<?=preg_replace('#[^a-z0-9]#','',strtolower(static::getSocialNetworkName()))?>"><span><?= static::getSocialNetworkName()?></span></a><?php
	}
	protected abstract /*string*/ function getSocialNetworkName();
	protected abstract /*OAuthConsumer*/ function getOAuthConsumer();
	protected abstract /*string*/ function getRequestTokenUrl();
	protected abstract /*string*/ function getAccessTokenUrl();
	protected abstract /*string*/ function getAuthorizeUrl();
	protected abstract /*void*/ function createProfileAndFriendsInstances();
}
?>
