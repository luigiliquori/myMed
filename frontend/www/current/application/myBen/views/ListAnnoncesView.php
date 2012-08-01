<? include("header.php"); 

// Prepare filters
$filters = array();
if ($this->extendedProfile instanceof ProfileBenevole) {
	$filters[ANN_CRITERIA] = "mes critères";
}
if ($this->extendedProfile instanceof ProfileAssociation && !($this->extendedProfile instanceof ProfileNiceBenevolat)) {
	$filters[ANN_MINE] = $this->extendedProfile->name;
}

$filters[ANN_VALID] = "valides";
$filters[ANN_PAST] = "passées / promues";
$filters[ANN_ALL] = "toutes";
?>

<div data-role="page" id="login">

	<? header_bar(array(
			"Accueil" => url("main"),
			"Annonces" => null)) ?>
	
	<div data-role="content">
	
		<? if ($this->canPost()) : ?>
			<a data-inline="true"data-role="button" data-icon="add" data-theme="g"	data-ajax="false"
				href="<?= url("annonce:create") ?>" >
				Poster une annonce
			</a>
		<? endif?>
	
		<div data-role="header" data-theme="e" >
			<h3>Liste des annonces
			<? filters("listAnnonces", $this->filter, $filters) ?>
			</h3>	
		</div>
		
		<? if (sizeof($this->annonces) == 0) : ?>
			<p>
				Aucune annonce à afficher avec ces critères
			</p>
			<a  
				data-role="button" 
				data-inline="true"
				href="<?= url("listAssociations", array("filter" => ANN_ALL)) ?>" >
				Afficher toutes les annonces
			</a>
		<? else : ?>
		<ul data-role="listview" data-theme="d" data-inset="true">
			<?  foreach ($this->annonces as $annonce) : ?>
				<li>
					<a 	data-ajax="false"
						href="<?= url("annonce:details", array("id" => $annonce->id)) ?>">
						<?= $annonce->titre ?>
						<? if (is_true($annonce->promue)) : ?>
							<span class="mm-tag mm-warn" >déjà promue</span>
						<? endif ?>
						<? if ($annonce->isPassed()) : ?>
							<span class="mm-tag mm-warn" >passée</span>
						<? endif ?>
						<p class="ui-li-aside">
							<b>Parution:</b> <?= $annonce->begin ?>
							<? if (!empty($annonce->end)) : ?>
								<br/>
								<b>Fin:</b> <?= $annonce->end ?>
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