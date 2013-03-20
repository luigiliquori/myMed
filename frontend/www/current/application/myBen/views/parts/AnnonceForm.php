<!--
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->

<div data-theme="b" data-role="header" >
	<h3><?= _("Description de l'annonce") ?></h3>
</div>

<? if ($this->extendedProfile instanceof ProfileNiceBenevolat) : ?>
	<? select("associationID", _("Association"), $this->associations, $annonce->associationID); ?>
	<div data-theme="b" data-validate="associationID" data-validate-non-empty >
	<?= _("Vous devez choisir une association.") ?>
</div>
<? endif ?>

<? input("text", "titre", _("Titre"), $annonce->titre, "", true) ?>

<? input("textarea", "description", _("Description"), $annonce->description, "", true) ?>

<div data-role="header" data-theme="b">
	<?= _("<h3>Compétences requises</h3>") ?>
</div>
<? checkbox_all("competences"); ?>
<? checkboxes("competences", CategoriesCompetences::$values, $annonce->competences); ?>
<div data-theme="b" data-validate="competences[]" data-validate-min="1" data-validate-max="4" >
	<b>Vous devez sélectionner de 1 à 4 compétences.</b>
</div>

<div data-role="header" data-theme="b">
	<h3><?= _("Informations pratiques") ?></h3>
</div>

<? select("quartier", _("Quartier"), CategoriesMobilite::$values, $annonce->quartier); ?>
<? select("typeMission", _("Type de mission"), CategoriesMissions::$values, $annonce->typeMission) ?>

<? input("date", "begin", _("Date de parution"), $annonce->begin) ?>
<? input("date", "end"  , _("Date de fin"),   $annonce->end) ?>
