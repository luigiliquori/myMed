<?php
require_once dirname(__FILE__).'/ConnexionOpenId.class.php';
/**
 * A class to define a OpenId login
 * @author blanchard
 */
class ConnexionGoogle extends ConnexionOpenId
{
	/**
	 * Print the connexion's button
	 */
	public /*void*/ function button()
	{
?>
	<form method="post" action="">
		<div>
			<input type="hidden" name="connexion_openid" />
			<input type="hidden" name="uri" value="https://www.google.com/accounts/o8/id" />
			<button type="submit" class="google"><span>Google</span></button>
		</div>
	</form>
<?php
	}
}
?>