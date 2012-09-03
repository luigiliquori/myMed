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
	
	
		<? if (!isset($this->extendedProfile)) : ?>
			<div class="mm-welcome"><p>
				Bonjour,<br/>
				<br/>
				Bienvenue sur <strong>MyBénévolat</strong>, le service de mise en relation des bénévoles et des associations, dans Nice et sa région via <strong>Nice Bénévolat</strong>.<br/>
				<br/>
					Vous êtes une association ? Vous vous recherchez des bénévoles ? <br/>
						<a 
							data-role="button" class="mm-left" data-theme="e" data-inline="true" data-ajax="false"
							href="<?= url("ExtendedProfile:create", array("type" => "association")) ?>">
							Créez un compte "association" et déposez vos offres
						</a>
						<br/>
						Vous êtes un particulier et souhaitez offrir votre temps libre ? <br/>
						<a  data-role="button" class="mm-left" data-theme="g" data-inline="true" data-ajax="false"
							href="<?= url("ExtendedProfile:create", array("type" => "benevole")) ?>">
							Créez un compte "bénévole" pour répondre aux offres
						</a>
			</p></div>
		<? endif ?>
	
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
				href="<?= url("listAnnonces", array("filter" => ANN_ALL)) ?>" >
				Afficher toutes les annonces
			</a>
		<? else : ?>
		<ul data-role="listview" data-theme="d" data-inset="true">
			<?  foreach ($this->annonces as $annonce) : ?>
				<li>
					<a 	data-ajax="false"
						href="<?= url("annonce:details", array("id" => $annonce->id)) ?>">
						
						<h3>
							<?= $annonce->titre ?>
							<? if (is_true($annonce->promue)) : ?>
								<span class="mm-tag mm-warn" >déjà promue</span>
							<? endif ?>
							<? if ($annonce->isPassed()) : ?>
								<span class="mm-tag mm-warn" >passée</span>
							<? endif ?>			
						</h3>
						
						<p>
							<? if (!empty($annonce->quartier)) : ?>				
								<strong>Lieu: </strong>
								<?= CategoriesMobilite::$values[$annonce->quartier] ?>
								<br/>
							<? endif ?>
							
							<strong>Compétences: </strong>
							<? foreach($annonce->competences as $competence): ?>
								<?= CategoriesCompetences::$values[$competence] ?>,
							<? endforeach ?>
							<br/>
							
							<b>Parution:</b> <?= $annonce->begin ?><br/>
							<? if (!empty($annonce->end)) : ?>
								<b>Fin:</b> <?= $annonce->end ?>
								<br/>
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