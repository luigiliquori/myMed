<? require("header.php"); ?>

<div data-role="page">
	<? tab_bar_main("?action=createMyMedProfile"); ?>
	<?php $this->mode = MODE_CREATE?>
	<div data-role="content" class="content">
		<form data-role="content" method="post" data-ajax="false" id="benForm" action="<?= url('createMyMedProfile:create') ?>" >
			<?php include("ProfileMyMedForm.php")?>
			<? wizard_footbar(_("Créer le profil")) ?>
		</form>
	</div>
</div>
