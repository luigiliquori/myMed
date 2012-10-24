<? include("header.php"); ?>

<div data-role="page" id="association" >

	<? tab_bar_main("?action=extendedProfile"); ?>
	<?php  include('notifications.php');?>

	<!--<? if ($this->getUserType() == USER_NICE_BENEVOLAT): ?>
		<? header_bar(array(
			_("Associations") => url("listAssociations"),
			_("Nouvelle association") => null)) ?>
	<? else: ?>
		<? header_bar(array(
			_("Création de profil association") => null)) ?>
	<? endif ?>-->
			
	<form data-role="content" method="post" data-ajax="false" id="benForm"
		action="<?= url("extendedProfile:doCreate", array("type" => ASSOCIATION)) ?>" >
				
		<p>
			<? if ($this->getUserType() == USER_GUEST) :?>
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

<? include("footer.php"); ?>
