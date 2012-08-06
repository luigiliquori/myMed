<? include("header.php"); ?>

<div data-role="page" id="benevole" >	

	<? include("header-bar.php") ?>
		
	<form data-role="content" method="post" data-ajax="false" 
		action="<?= url("extendedProfile:doCreate", array("type" => BENEVOLE)) ?>" >
		
		<p>
			<? if (!isset($this->extendedProfile)) :?>
			    <?= _("Vous avez déjà un compte bénévole ?") ?>
			    <a href="<?= url("login") ?>">
                    <?= _("connectez vous") ?>
                </a><br/>
			<? endif ?>
		    <?= _("Merci de remplir votre profil.") ?>
		</p>
		
		<? include('ProfileBenevoleForm.php') ?>
	
		<? wizard_footbar(_("Créer le profil")) ?>
		
	</form>
	
</div>

<? include("footer.php"); ?>
