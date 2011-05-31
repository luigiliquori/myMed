<?php
require_once dirname(__FILE__).'/ConnexionOpenId.class.php';
/**
 * A class to define a myMed login
 * @author blanchard
 */
class ConnexionMyMed extends ConnexionOpenId
{
	protected $social_network = 'myMed';
	public function __construct()
	{
		if(!isset($_REQUEST['connexionProvider'])
			||$_REQUEST['connexionProvider']=='myMed'
			||$this->social_network!='myMed')
		{
			$this->consumer = new Auth_OpenID_Consumer(new Auth_OpenID_FileStore('/tmp/oid_store'));
			static::tryConnect();
		}
	}
	/**
	 * Print the connexion's button
	 */
	public /*void*/ function button()
	{
?>
		<form method="post" action="?connexionProvider=myMed">
			<div>
				<input type="hidden" name="connexion" value="openid" />
				<input type="hidden" name="connexionProvider" value="myMed" />
				<input type="hidden" name="uri" value="http://<?=$_SERVER["HTTP_HOST"].ROOTPATH?>openid.php" />
				<button type="submit" class="mymed"><span>MyMed</span></button>
			</div>
		</form>
<?php
	}
}
?>