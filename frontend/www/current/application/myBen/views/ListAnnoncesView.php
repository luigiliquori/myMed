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

<div
	data-role="page" id="login">

	<!-- main action is different for admin. for tab bar admin will have action?=main as admin tab-->
	<?php if(isset($this->extendedProfile) && $this->extendedProfile instanceof ProfileNiceBenevolat){
		tab_bar_main("?action=listAnnonces");
	}
	else{
		tab_bar_main("?action=main");
	}
	?>
	<?php  include('notifications.php');?>

	<div data-role="content" class="content">
		<?php if(!isset($_SESSION['user'])):?>
		<div data-role="content" class="content">
			<img alt="myBenevolat" src="img/icon.png" width="110">
			<!-- Login form -->
			<form data-role="content" action="<?= url("login:doLogin") ?>"
				method="post" data-ajax="false">

				<input type="text" name="login" placeholder="Login (email)" /> <input
					type="password" name="password" placeholder="Password" /> <input
					type="submit" value="<?= _("Connexion") ?>" data-role="button"
					data-inline="true" data-theme="b" data-icon="signin" />
			</form>
		</div>
		<?endif?>
		<? if (isset($_SESSION['user']) && !isset($this->extendedProfile)) : ?>
		<?= _("Veuillez indiquer dans votre profil si vous êtes un bénévole ou une association") ?>
		<? endif ?>
		<? if ($this->canPost()) : ?>
		<a data-inline="true" data-role="button" data-icon="add"
			data-theme="g" data-ajax="false" href="<?= url("annonce:create") ?>">
			<?= _("Poster une annonce") ?>
		</a>
		<?php endif;?>
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
		<!--<? if ($this->canPost()) : ?>
		<div class="ui-grid-a">
			<div class="ui-block-a">
				<? endif?>-->
				<a data-role="button" data-inline="true"
					href="<?= url("listAnnonces", array("filter" => ANN_ALL)) ?>"> <?= _("Afficher toutes les annonces") ?>
				</a>
				<!--<? if ($this->canPost()) : ?>
			</div>
			<div class="ui-block-b">
				<a data-inline="true" data-role="button" data-icon="add"
					data-theme="g" data-ajax="false"
					href="<?= url("annonce:create") ?>"> <?= _("Poster une annonce") ?>
				</a>
			</div>
			<? endif?>
		</div>-->
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
