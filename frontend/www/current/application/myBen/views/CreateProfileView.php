<? 
//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); ?>

<div data-role="page" id="switch">

	<? include("header-bar.php") ?>
	
	<div data-role="content">

		<p>
			Bonjour,<br/>
			<br/>
			C'est la première fois que vous venez sur MyBénévolat.<br/>
			Merci de renseigner votre profil.<br/>
			<br/>
			Vous êtes ?<br/>
			<br/>
			<ul data-role="listview" >
				<li><a href="#benevole">Un bénévole</a></li>
				<li><a href="#association">Une association</a></li>
			</ul> 			
		</p>
		
	</div>
	
</div>

<div data-role="page" id="benevole" >	

	<? include("header-bar.php") ?>
	<? global $PREFIX_ID; $PREFIX_ID="bene-" ?>
	
	<p>
		Merci de remplir vos critères de recherche pour trouver des offres de bénévolat.
	</p>
		
	<form data-role="content" method="post" data-ajax="false" 
		action="<?= url("extendedProfile:create", array("type" => "benevole")) ?>" >
		
		<? include('ProfileBenevoleForm.php') ?>
	
		<input type=submit name="submit" data-role="button" data-theme="g" 
			value="Créer le profil" />

	</form>
	
</div>

<div data-role="page" id="association" >	

	<? include("header-bar.php") ?>
	<? global $PREFIX_ID; $PREFIX_ID="asso-" ?>
	
	<p>
		Merci de remplir la fiche de l'association. 
	</p>
	
	<form data-role="content" method="post" data-ajax="false" 
		action="<?= url("extendedProfile:create", array("type" => "association")) ?>" >
	
		<? include('ProfileAssociationForm.php') ?>
		
		<input type=submit name="submit" data-role="button" data-theme="g" 
			value="Créer le profil" />

	</form>

</div>


<? include("footer.php"); ?>