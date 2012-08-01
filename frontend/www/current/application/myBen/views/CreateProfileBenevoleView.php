<? include("header.php"); ?>

<div data-role="page" id="benevole" >	

	<? include("header-bar.php") ?>
		
	<form data-role="content" method="post" data-ajax="false" 
		action="<?= url("extendedProfile:doCreate", array("type" => BENEVOLE)) ?>" >
		
		<? include('ProfileBenevoleForm.php') ?>
	
		<input type=submit name="submit" data-role="button" data-theme="g" 
			value="Créer le profil" />

	</form>
	
</div>

<? include("footer.php"); ?>