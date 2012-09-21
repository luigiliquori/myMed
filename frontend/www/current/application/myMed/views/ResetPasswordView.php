
<? require_once("header.php"); ?>

<div data-role="page" id="register" >	

	<div data-role="header" data-theme="b">
		<h1>Réinitialisation du mot de passe</h1>
		<? include("notifications.php"); ?>
	</div>

	<div data-role="content">
	
		<!--  Register form -->
		<form action="?action=resetPassword" method="post" data-ajax="false">
				
				<label for="password" >Nouveau mot de passe : </label>
				<input type="password" name="password" />
				<br />
				
				<label for="password" >Confirmation : </label>
				<input type="password" name="confirm" />
				<br />
				
				<center>
					<input type="submit" data-role="button" data-theme="b" data-inline="true" value="Valider" />
				</center>
		
		</form>
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#login" data-transition="none" data-back="true" data-icon="home">Connexion</a></li>
				<li><a href="#register" data-transition="none" data-back="true" data-icon="grid"  class="ui-btn-active ui-state-persist">Inscription</a></li>
				<li><a href="#about" data-transition="none" data-icon="info">A propos</a></li>
			</ul>
		</div>
	</div>
	
</div>

<? require_once("LoginView.php"); ?>

<? require_once("footer.php"); ?>
