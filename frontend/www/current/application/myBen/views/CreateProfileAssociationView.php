<? include("header.php"); ?>

<div data-role="page" id="association" >	

	<? include("header-bar.php") ?>
	
	<form data-role="content" method="post" data-ajax="false" 
		action="<?= url("extendedProfile:doCreate", array("type" => ASSOCIATION)) ?>" >

		<p>
		Merci de remplir la fiche de l'association. 
		</p>
		
		<? include('ProfileAssociationForm.php') ?>
		
		<input type=submit name="submit" data-role="button" data-theme="g" 
			value="Créer le profil" />

	</form>

</div>

<? include("footer.php"); ?>