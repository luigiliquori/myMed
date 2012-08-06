<? include("header.php") ?>

<div data-role="page" id="association" >	

	<? include("header-bar.php") ?>
	
	<form data-role="content" method="post" data-ajax="false" 
		action="<?= url("extendedProfile:doCreate", array("type" => ASSOCIATION)) ?>" >
				
		<p>
			<? if (!isset($this->extendedProfile)) :?>
			    <?= _("Vous avez déjà un compte association ?") ?>
			    <a href="<?= url("login") ?>">
                    <?= _("connectez vous") ?>
                </a><br/>
			<? endif ?>
			<?= _("Merci de remplir la fiche de l'association.") ?>
		</p>
		
		<? include('ProfileAssociationForm.php') ?>
		
		<? wizard_footbar(_("Créer le profil")) ?>

	</form>

</div>

<? include("footer.php") ?>
