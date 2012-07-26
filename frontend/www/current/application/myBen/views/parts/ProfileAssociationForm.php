<? 
// Parameters of the template :
// * $PREFIX_ID : (String) [Optional] Prefix for element IDs (to prevent conflicts in case of several forms (several pages) in the same HTML file.
// * $MODE : "create" or "update"

// Alias
$profile = $this->profileAssociation; ?>

<div data-role="header" data-theme="b">
	<h3>Information administratives</h3>
</div>

<? input("tel", "tel", "Téléphone", $profile->tel, "00 00 00 00 00") ?>
<div data-theme="b" data-validate="tel" data-validate-non-empty >
	Vous devez renseigner le téléphone de l'association.
</div>

<? input("text", "siret", "N° SIRET", $profile->siret) ?>

<? input("text", "site", "Site web", $profile->site, "http://...") ?>

<? input("textarea", "adresse", "Adresse postale", $profile->adresse) ?>

<div data-role="header" data-theme="b">
	<h3>Compétences</h3>
</div>
<div data-theme="b" data-validate="competences[]" data-validate-min="1" data-validate-max="4" >
	<b>Vous devez sélectionner de 1 à 4 compétences.</b>
</div>
<? checkbox_all("competences"); ?>
<? checkboxes("competences", CategoriesCompetences::$values, $profile->competences); ?>

<div data-role="header" data-theme="b">
	<h3>Types de missions souhaitées</h3>
</div>
<div data-theme="b" data-validate="missions[]" data-validate-min="1" >
	Vous devez sélectionner au moins 1 type de missions.
</div>
<? checkbox_all("missions"); ?>
<? checkboxes("missions", CategoriesMissions::$values, $profile->missions); ?>
