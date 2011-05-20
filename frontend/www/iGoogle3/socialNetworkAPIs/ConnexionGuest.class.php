<?php
require_once dirname(__FILE__).'/Connexion.class.php';
require_once dirname(__FILE__).'/../system/backend/ProfileRequest.class.php';
/**
 * A class to define an anonymous login
 * @author blanchard
 */
class ConnexionGuest extends Connexion
{
	public function __construct()
	{
		if(isset($_POST["connexion"])&&$_POST["connexion"]=='guest')
		{
			$request = new ProfileRequest;
			$_SESSION['user'] = $request->read('visiteur');
			header('Location:'.$_SERVER["REQUEST_URI"]);
			exit;
		}
	}
	/**
	 * Print the connexion's button
	 */
	public /*void*/ function button()
	{
?>
		<a href="?connexion=guest" class="guest"><span>Visiteur</span></a>
<?php
	}
}
?>