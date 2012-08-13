<? include("header.php"); ?>

<div data-role="page" id="login">

	<? header_bar(array(
			"Accueil" => url("main"),
			"Associations" => null)) ?>
	
	<div data-role="content">
	
		<? if ($this->extendedProfile instanceof ProfileNiceBenevolat) : ?>
			<a data-inline="true"data-role="button" data-icon="add" data-theme="g"	data-ajax="false"
				href="<?= url("extendedProfile:create", array("type" => ASSOCIATION)) ?>" >
				Ajouter une association
			</a>
		<? endif?>
	
		<div data-role="header" data-theme="e" >
			<h3>
				Liste des associations
				<? filters(
						"listAssociations", $this->filter,
						array(
							ASS_ALL => "toutes",
							ASS_INVALID => "à valider"))
				?>
			</h3>
		</div>
		
		<? if (sizeof($this->associations) == 0) : ?>
			<p>
				Aucune association à afficher avec ces critères
			</p>
			<a  data-role="button" 
				data-inline="true"
				href="<?= url("listAssociations") ?>" >
				Afficher toutes les associations
			</a>
		<? else : ?>
			<ul data-role="listview" data-theme="d" data-inset="true">
				<? foreach ($this->associations as $association) : ?>
				<li>
					<a 	data-ajax="false"
						href="<?= url("extendedProfile:show", array("id" => $association->userID)) ?>">
							<?= $association->name ?>
							<? if (!is_true($association->valid)): ?>
								<span class="mm-tag mm-warn" >à valider</span>
							<? endif ?>
					</a>
				</li>
				<? endforeach ?>
			</ul>
		<? endif ?>
		
	</div>
	
</div>
	
<?php include("footer.php"); ?>