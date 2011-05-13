<?php
require_once dirname(__FILE__).'/Connexion.class.php';
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
			$_SESSION['user'] = array(
					'id'				=> 'guest',
					'name'				=> null,
					'gender'			=> null,
					'locale'			=> substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, strcspn($_SERVER['HTTP_ACCEPT_LANGUAGE'], ',;')),
					'updated_time'		=> null,
					'profile'			=> null,
					'profile_picture'	=> null,
					'social_network'	=> 'Guest');
			$encoded = json_encode($_SESSION['user']);
			file_get_contents(trim(BACKEND_URL."ProfileRequestHandler?act=0&user=" . urlencode($encoded)));
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
		<form method="post" action="">
			<div>
				<input type="hidden" name="connexion" value="guest" />
				<button type="submit" class="guest"><span>Visiteur</span></button>
			</div>
		</form>
<?php
	}
}
?>