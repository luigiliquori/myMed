<? 
// Parameters of the template :
// * $PREFIX_ID : (String) [Optional] Prefix for element IDs (to prevent conflicts in case of several forms (several pages) in the same HTML file.
// * $MODE : "create" or "update"

// Alias
$profile = $this->_extendedProfile;  ?>

<? $TYPE = BENEVOLE ?>
<? include('RegisterForm.php') ?>

<div data-role="header" data-theme="b">
	<h3>Informations personnelles</h3>
</div>

<? input("tel", "tel", "Téléphone", $profile->tel, "00 00 00 00 00", true) ?>

<? radiobuttons("sexe", CategoriesSexe::$values, $profile->sexe, "Sexe", true) ?>
<div data-validate="sexe" data-validate-non-empty >
	Vous devez renseigner votre sexe.
</div>

<? radiobuttons("situation", CategoriesSituation::$values, $profile->situation, "Situation professionelle") ?>
<div data-validate="situation" data-validate-non-empty >
	Vous devez renseigner votre situation professionelle.
</div>

<div data-role="header" data-theme="b">
	<h3>Compétences</h3>
</div>
<? checkbox_all("competences"); ?>
<? checkboxes("competences", CategoriesCompetences::$values, $profile->competences); ?>
<div data-theme="b" data-validate="competences[]" data-validate-min="1" data-validate-max="4" >
	<b>Vous devez sélectionner de 1 à 4 compétences.</b>
</div>

<div data-role="header" data-theme="b">
	<h3>Types de missions souhaitées</h3>
</div>
<div data-theme="b" data-validate="missions[]" data-validate-min="1" >
	Vous devez sélectionner au moins 1 type de missions.
</div>
<? checkbox_all("missions"); ?>
<? checkboxes("missions", CategoriesMissions::$values, $profile->missions); ?>

<div data-role="header" data-theme="b">
	<h3>Mobilité (quartiers de nice)</h3>
</div>
<div data-theme="b" data-validate="mobilite[]" data-validate-min="1" >
	Vous devez sélectionner au moins un secteur de mobilité.
</div>
<? checkbox_all("mobilite"); ?>
<? checkboxes("mobilite", CategoriesMobilite::$values_no_undef, $profile->mobilite); ?>

<div data-role="header" data-theme="b">
	<h3>Disponibilités</h3>
</div>
<div data-theme="b" data-validate="disponibilites[]" data-validate-min="1" >
	Vous devez sélectionner au moins un créneau de disponibilités.
</div>
<? checkbox_all("disponibilites"); ?>
<? checkboxes("disponibilites", CategoriesDisponibilites::$values, $profile->disponibilites); ?>

<input data-theme="e" type="checkbox" name="subscribe" value="true" id="subscribe" <? if (is_true($profile->subscribe)) print "checked='checked'"?> />
<label for="subscribe" data-theme="e">Prévenez moi par email en cas de futures offres correspondantes.</label>

