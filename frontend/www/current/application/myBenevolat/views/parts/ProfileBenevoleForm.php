<!-- ------------------------------------------------ -->
<!-- ProfileBenevoleForm                              -->
<!-- Implements the volunteer profile creation wizard --> 
<!-- ------------------------------------------------ -->

<? 
// Parameters of the template :
// * $PREFIX_ID : (String) [Optional] Prefix for element IDs (to prevent conflicts in case of several forms (several pages) in the same HTML file.
// * $MODE : "create" or "update"

// Alias
$profile = $this->_extendedProfile;  ?>

<? $TYPE = BENEVOLE ?>

<div data-role="wizard-page" id="perso-info">
	
	<div data-role="header" data-theme="b">
		<h3><?= _("Informations personnelles") ?></h3>
	</div>
	
	<? input("tel", "tel", _("Téléphone"), $profile->tel, "00 00 00 00 00", true) ?>
	
	<? radiobuttons("sexe", CategoriesSexe::$values, $profile->sexe, "Sexe", true) ?>
	<div data-validate="sexe" data-validate-non-empty >
		<?= _("Vous devez renseigner votre sexe.") ?>
	</div>
	
	<? radiobuttons("situation", CategoriesSituation::$values, $profile->situation, _("Situation professionelle")) ?>
	<div data-validate="situation" data-validate-non-empty >
	    <?= _("Vous devez renseigner votre situation professionelle.") ?>
	</div>
	
</div>

<div data-role="wizard-page" id="competences">
	
	<div data-role="header" data-theme="b">
		<h3><?= _("Compétences") ?></h3>
	</div>
	<? checkbox_all("competences"); ?>
	<? checkboxes("competences", CategoriesCompetences::$values, $profile->competences); ?>
	<div data-theme="b" data-validate="competences[]" data-validate-min="1" data-validate-max="4" >
		<?= _("Vous devez sélectionner de 1 à 4 compétences.") ?>
	</div>

</div>

<div data-role="wizard-page" id="type-mission">

	<div data-role="header" data-theme="b">
		<h3><?= _("Types de missions souhaitées") ?></h3>
	</div>
	<div data-theme="b" data-validate="missions[]" data-validate-min="1" >
		Vous devez sélectionner au moins 1 type de missions.
	</div>
	<? checkbox_all("missions"); ?>
	<? checkboxes("missions", CategoriesMissions::$values, $profile->missions); ?>
	
	<div data-role="header" data-theme="b">
		<h3><?= _("Mobilité (quartiers de nice)") ?></h3>
	</div>
	<div data-theme="b" data-validate="mobilite[]" data-validate-min="1" >
		<?= _("Vous devez sélectionner au moins un secteur de mobilité.") ?>
	</div>
	<? checkbox_all("mobilite"); ?>
	<? checkboxes("mobilite", CategoriesMobilite::$values_no_undef, $profile->mobilite); ?>

</div>

<div data-role="wizard-page" id="dispo">

<div data-role="header" data-theme="b">
	<h3><?= _("Disponibilités") ?></h3>
</div>
<div data-theme="b" data-validate="disponibilites[]" data-validate-min="1" >
	<?= _("Vous devez sélectionner au moins un créneau de disponibilités.") ?>
</div>
<? checkbox_all("disponibilites"); ?>
<? checkboxes("disponibilites", CategoriesDisponibilites::$values, $profile->disponibilites); ?>

<input data-theme="e" type="checkbox" name="subscribe" value="true" id="subscribe" <? if (is_true($profile->subscribe)) print "checked='checked'"?> />
<label for="subscribe" data-theme="e"><?= _("Prévenez moi par email en cas de futures offres correspondantes.") ?></label>

</div>
