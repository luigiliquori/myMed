<? 
	// Alias
 	$profile = $this->profileBenevole; 
?>
<form data-role="content" method="post" data-ajax="false" action="<?= url("fillProfile", array("type"=>"benevole")) ?>" >

	<div data-role="header" data-theme="b">
		<h3>Informations personnelles</h3>
	</div>
	
	<? input("tel", "tel", "Téléphone", $profile->tel, "00 00 00 00 00") ?>
	<div data-theme="b" data-validate="tel" data-validate-non-empty >
		Vous devez renseigner votre téléphone.
	</div>
	
	<? input("date", "dateNaissance", "Date de naissance", $profile->dateNaissance) ?>
	<div data-theme="b" data-validate="dateNaissance" data-validate-non-empty >
		Vous devez renseigner votre date de naissance
	</div>
		
	<? radiobuttons("sexe", CategoriesSexe::$values, $profile->sexe, "Sexe", true) ?>
	<div data-theme="b" data-validate="sexe" data-validate-non-empty >
		Vous devez renseigner votre sexe.
	</div>
	
	<? radiobuttons("situation", CategoriesSituation::$values, $profile->situation, "Situation professionelle") ?>
	<div data-theme="b" data-validate="situation" data-validate-non-empty >
		Vous devez renseigner votre situation professionelle.
	</div>

	<div data-role="header" data-theme="b">
		<h3>Compétences</h3>
	</div>
	<div data-theme="b" data-validate="competences[]" data-validate-min="1" data-validate-max="4" >
		<b>Vous devez sélectionner de 1 à 4 compétences.</b>
	</div>
	<? checkox_all("competences"); ?>
	<? checkboxes("competences", CategoriesCompetences::$values, $profile->competences); ?>
	
	<div data-role="header" data-theme="b">
		<h3>Types de missions souhaitées</h3>
	</div>
	<div data-theme="b" data-validate="missions[]" data-validate-min="1" >
		Vous devez sélectionner au moins 1 type de missions.
	</div>
	<? checkox_all("missions"); ?>
	<? checkboxes("missions", CategoriesMissions::$values, $profile->missions); ?>
	
	<div data-role="header" data-theme="b">
		<h3>Mobilité (quartiers de nice)</h3>
	</div>
	<div data-theme="b" data-validate="mobilite[]" data-validate-min="1" >
		Vous devez sélectionner au moins un secteur de mobilité.
	</div>
	<? checkox_all("mobilite"); ?>
	<? checkboxes("mobilite", CategoriesMobilite::$values, $profile->mobilite); ?>

	<div data-role="header" data-theme="b">
		<h3>Disponibilités (quartiers de nice)</h3>
	</div>
	<div data-theme="b" data-validate="disponibilites[]" data-validate-min="1" >
		Vous devez sélectionner au moins un créneau de disponibilités.
	</div>
	<? checkox_all("disponibilites"); ?>
	<? checkboxes("disponibilites", CategoriesDisponibilites::$values, $profile->disponibilites); ?>

	<input data-theme="e" type="checkbox" name="alert" value="true" id="alert" checked="checked" />
	<label for="alert" data-theme="e">Prévenez moi par email en cas de futures offres correspondantes.</label>
	
	<input type=submit name="submit" data-role="button" data-theme="g" value="Enregister mon profil" />

</form>