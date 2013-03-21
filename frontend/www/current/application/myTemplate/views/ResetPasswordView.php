
<? require_once("header.php"); ?>

<div data-role="dialog" data-overlay-theme="b" id="resetpass" >	

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
	
</div>

<? require_once("footer.php"); ?>
