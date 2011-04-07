<?php
require_once dirname(__FILE__).'/Connexion.class.php';
/**
 * A class to define a myMed login
 * @author blanchard
 */
class ConnexionMyMed extends Connexion
{
	public function __construct()
	{
		if(isset($_POST["connexion_mymed"], $_POST["email"], $_POST["password"]))
		{
			$isAuthenticated = file_get_contents(trim(BACKEND_URL."RequestHandler?act=12&email=" . $_POST["email"] . "&password=" . $_POST["password"]));
			if($isAuthenticated)
				$_SESSION['user'] = json_decode($isAuthenticated, true);
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
		<form method="get" action="">
			<div>
				<input type="hidden" name="connexion" value="mymed" />
				<button type="submit" class="mymed"><span>MyMed</span></button>
			</div>
		</form>
<?php
	}
}
?>