<? include("header.php"); ?>

<div data-role="page" id="benevole" >	

	<? tab_bar_main("?action=extendedProfile"); ?>
	
	<!--<? if ($this->getUserType() == USER_NICE_BENEVOLAT): ?>
		<? header_bar(array(
			_("Bénévoles") => url("listBenevoles"),
			_("Nouveau bénévole") => null)) ?>
	<? else: ?>
		<? header_bar(array(
			_("Création de profil bénévole") => null)) ?>
	<? endif ?>-->
	
	<form data-role="content" method="post" data-ajax="false" id="benForm"
		action="<?= url("extendedProfile:doCreate", array("type" => BENEVOLE)) ?>" >
		
		<p>
			<? if ($this->getUserType() == USER_GUEST) : ?>
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
