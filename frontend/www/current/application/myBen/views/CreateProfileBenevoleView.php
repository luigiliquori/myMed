<? include("header.php"); ?>

<div data-role="page" id="benevole" >	

	<? include("header-bar.php") ?>
		
	<form data-role="content" method="post" data-ajax="false" 
		action="<?= url("extendedProfile:doCreate", array("type" => BENEVOLE)) ?>" >
		
		<p>
			<? if (!isset($this->extendedProfile)) :?>
			Vous avez déjà un compte bénévole ?
			<a href="<?= url("login") ?>">connectez vous</a><br/>
			<? endif ?>
			Merci de remplir votre profil.
		</p>
		
		<? include('ProfileBenevoleForm.php') ?>
	
		<input type=submit name="submit" data-role="button" data-theme="g" 
			value="Créer le profil" />

	</form>
	
</div>

<? include("footer.php"); ?>