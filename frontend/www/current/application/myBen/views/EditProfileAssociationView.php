<? require("header.php"); ?>

<div data-role="page"  >	

	<? require("header-bar.php") ?>

	<form data-role="content" method="post" data-ajax="false" 
		action="<?= url("extendedProfile:update", array("type" => "association")) ?>" >
		
		<? require('ProfileAssociationForm.php') ?>
	
		<input type=submit name="submit" data-role="button" data-theme="g" 
			value="Mettre à jour le profil" />

	</form>
	
</div>