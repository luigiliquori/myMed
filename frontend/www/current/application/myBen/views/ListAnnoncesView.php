<? include("header.php"); 

// Prepare filters
$filters = array();
if ($this->extendedProfile instanceof ProfileBenevole) {
	$filters[ANN_CRITERIA] = _("mes critères");
}

$filters[ANN_VALID] = _("valides");
$filters[ANN_PAST] = _("passées / promues");
$filters[ANN_ALL] = _("toutes");
?>

<div data-role="page" id="login">

	<? tab_bar_main("?action=main"); ?>

		<div data-role="content">

			<? if (!isset($this->extendedProfile)) : ?>
			<div class="mm-welcome">
				<p>
					<?= _("Bonjour,") ?>
					<br /> <br />
					<?= _("Bienvenue sur <strong>MyBénévolat</strong>,") ?>
					<?= _("le service de mise en relation des bénévoles et des associations, dans Nice et sa région via <strong>Nice Bénévolat</strong>.") ?>
					<br /> <br />
					<?= _("	Vous êtes une association ? Vous vous recherchez des bénévoles ?") ?>
					<br /> <a data-role="button" class="mm-left" data-theme="e"
						data-inline="true" data-ajax="false"
						href="<?= url("ExtendedProfile:create", array("type" => "association")) ?>">
						<?= _("Créez un compte 'association' et déposez vos offres") ?>
					</a> <br />
					<?= _("Vous êtes un particulier et souhaitez offrir votre temps libre ?") ?>
					<br /> <a data-role="button" class="mm-left" data-theme="g"
						data-inline="true" data-ajax="false"
						href="<?= url("ExtendedProfile:create", array("type" => "benevole")) ?>">
						<?= _("Créez un compte 'bénévole' pour répondre aux offres") ?>
					</a>
				</p>
			</div>
			<? endif ?>

			<? if ($this->canPost()) : ?>
			<a data-inline="true" data-role="button" data-icon="add"
				data-theme="g" data-ajax="false" href="<?= url("annonce:create") ?>">
				<?= _("Poster une annonce") ?>
			</a>
			<? endif?>

			<div data-role="header" data-theme="e">
				<div style="display: inline-block">
					<h3 style="margin-left: 1em">
						<?= _("Liste des annonces") ?>
						<? if ($this->getUserType() == USER_ASSOCIATION): ?>
						<?= _("de") . " " . $this->extendedProfile->name ?>
						<? endif ?>
					</h3>
				</div>
				<div style="display: inline-block">
					<? filters("listAnnonces", $this->filter, $filters) ?>
				</div>
			</div>

			<? if (sizeof($this->annonces) == 0) : ?>
			<p>
				<?= _("Aucune annonce à afficher avec ces critères") ?>
			</p>
			<a data-role="button" data-inline="true"
				href="<?= url("listAnnonces", array("filter" => ANN_ALL)) ?>"> <?= _("Afficher toutes les annonces") ?>
			</a>
			<? else : ?>
			<ul data-role="listview" data-theme="d" data-inset="true">
				<?  foreach ($this->annonces as $annonce) : ?>
				<li><a data-ajax="false"
					href="<?= url("annonce:details", array("id" => $annonce->id)) ?>">

						<h3>
							<?= $annonce->titre ?>
							<? if (is_true($annonce->promue)) : ?>
							<span class="mm-tag mm-warn"><?= _("déjà promue") ?> </span>
							<? endif ?>
							<? if ($annonce->isPassed()) : ?>
							<span class="mm-tag mm-warn"><?= _("passée") ?> </span>
							<? endif ?>
						</h3>

						<p>
							<? if (!empty($annonce->quartier)) : ?>
							<strong>Lieu: </strong>
							<?= CategoriesMobilite::$values[$annonce->quartier] ?>
							<br />
							<? endif ?>

							<strong><?= _("Compétences") ?>: </strong>
							<? foreach($annonce->competences as $competence): ?>
							<?= CategoriesCompetences::$values[$competence] ?>
							,
							<? endforeach ?>
							<br /> <b><?= _("Parution") ?>:</b>
							<?= $annonce->begin ?>
							<br />
							<? if (!empty($annonce->end)) : ?>
							<b><?= _("Fin") ?>:</b>
							<?= $annonce->end ?>
							<br />
							<? endif ?>
						</p>
				</a>
				</li>
				<? endforeach ?>
			</ul>
			<? endif ?>

		</div>
	</div>


<?php include("footer.php"); ?>
