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
		if(isset($_POST["connexion_guest"])) //@todo warning was a $_GET['try'] var (see button())
		{
			$_SESSION['user'] = array(
					'id'				=> 'visiteur',
					'name'				=> 'Visiteur',
					'gender'			=> 'something',
					'locale'			=> 'somewhere',
					'updated_time'		=> 'now',
					'profile'			=> 'http://www.facebook.com/profile.php?id=007',
					'profile_picture'	=> 'http://graph.facebook.com//picture?type=large',
					'social_network'	=> 'unknown');
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
				<input type="hidden" name="connexion_guest" />
				<button type="submit" class="guest"><span>Visiteur</span></button>
			</div>
		</form>
<?php
	}
}
?>