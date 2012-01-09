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
		if(isset($_POST['connexion'], $_POST['email'], $_POST['password'])&&$_POST['connexion']=='mymed')
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
		if(isset($_GET['connexion'])&&$_GET['connexion']=='mymed'):
?>
		<form method="post" action="">
			<div class="hidden">
				<input type="hidden" name="connexion" value="mymed" />
			</div>
			<div>
				<label for="email">E-mail&nbsp;:</label>						   		
				<input type="email" name="id" name="email" size="24"  maxsize="255" />
			</div>
			<div>
				<label for="password">Mot de Passe&nbsp;:</label>						
				<input type="password" name="password" name="password" />
			</div>
			<div class="submitRow">
				<div></div>
				<div>
					<a class="cancel" href="<?=ROOTPATH?>">Annuler</a>
					<button type="submit">Connecter</button>
				</div>
			</div>
		</form>
<?php else:?>
		<a href="?connexion=mymed" class="mymed"><span>MyMed</span></a>
<?php
		endif;
	}
}
?>