
<div data-theme="b" data-role="header" >
	<h3>Description de l'annonce</h3>
</div>

<? input("text", "titre", "Titre", $annonce->titre) ?>
<div data-theme="b" data-validate="titre" data-validate-non-empty >
	Le titre est vide.
</div>

<? input("textarea", "description", "Description", $annonce->description) ?>
<div data-theme="b" data-validate="description" data-validate-non-empty >
	La description est vide.
</div>

<div data-role="header" data-theme="b">
	<h3>Compétences requises</h3>
</div>
<? checkbox_all("competences"); ?>
<? checkboxes("competences", CategoriesCompetences::$values, $annonce->competences); ?>
<div data-theme="b" data-validate="competences[]" data-validate-min="1" data-validate-max="4" >
	<b>Vous devez sélectionner de 1 à 4 compétences.</b>
</div>

<div data-role="header" data-theme="b">
	<h3>Informations pratiques</h3>
</div>
<? select("quartier", "Quartier", CategoriesMobilite::$values, $annonce->quartier); ?>

<? select("typeMission", "Type de mission", CategoriesMissions::$values, $annonce->typeMission) ?>
<? input("date", "begin", "Date de parution", $annonce->begin) ?>
<? input("date", "end"  , "Date de fin",   $annonce->end) ?>
