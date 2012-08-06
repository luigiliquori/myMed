<? 
// Parameters of the template :
// * $PREFIX_ID : (String) [Optional] Prefix for element IDs (to prevent conflicts in case of several forms (several pages) in the same HTML file.
// * $MODE : "create" or "update"

// Alias
$profile = $this->_extendedProfile;  ?>

<? $TYPE = ASSOCIATION ?>
<? include('RegisterForm.php') ?>

<div data-role="wizard-page" id="info-admin" >

	<div data-role="header" data-theme="b">
		<h3><?= _("Information administratives") ?></h3>
	</div>
	
	<? input("tel", "tel", "Téléphone", $profile->tel, "00 00 00 00 00") ?>
	<div data-theme="b" data-validate="tel" data-validate-non-empty >
		<?= _("Vous devez renseigner le téléphone de l'association.") ?>
	</div>
	
	<? input("text", "siret", _("N° SIRET"), $profile->siret) ?>
	
	<? input("url", "site", _("Site web"), $profile->site, "http://...") ?>
	
	<? input("textarea", "adresse", _("Adresse postale"), $profile->adresse) ?>

</div>

<div data-role="wizard-page" id="competences-missions" >
	<div data-role="header" data-theme="b">
		<h3><?= _("Compétences") ?></h3>
	</div>
	<div data-theme="b" data-validate="competences[]" data-validate-min="1" data-validate-max="4" >
		<b><?= _("Vous devez sélectionner de 1 à 4 compétences.") ?></b>
	</div>
	<? checkbox_all("competences"); ?>
	<? checkboxes("competences", CategoriesCompetences::$values, $profile->competences); ?>
	
	<div data-role="header" data-theme="b">
		<h3><?= _("Types de missions proposées") ?></h3>
	</div>
	<div data-theme="b" data-validate="missions[]" data-validate-min="1" >
	    <?= _("	Vous devez sélectionner au moins 1 type de missions.") ?>
	</div>
	<? checkbox_all("missions"); ?>
	<? checkboxes("missions", CategoriesMissions::$values, $profile->missions); ?>
	
</div>
