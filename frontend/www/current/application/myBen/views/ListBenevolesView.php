<? include("header.php"); ?>

<div data-role="page" id="login">

	<? header_bar(array(
			"Accueil" => url("main"),
			"Bénévoles" => null)) ?>
	
	<div data-role="content">
	
		<? if ($this->extendedProfile instanceof ProfileNiceBenevolat) : ?>
			<a data-inline="true"data-role="button" data-icon="add" data-theme="g"	data-ajax="false"
				href="<?= url("extendedProfile:create", array("type" => BENEVOLE)) ?>" >
				Ajouter un bénévole
			</a>
		<? endif?>
	
		<div data-role="header" data-theme="e" >
			<h3>
				Liste des bénévoles
			</h3>
		</div>
		
		<? if (sizeof($this->benevoles) == 0) : ?>
			<p>
				Aucun bénévole à afficher avec ces critères
			</p>
			<a  data-role="button" 
				data-inline="true"
				href="<?= url("listBenevoles") ?>" >
				Afficher tous les bénévoles
			</a>
		<? else : ?>
			<ul data-role="listview" data-theme="d" data-inset="true">
				<? foreach ($this->benevoles as $benevole) : ?>
				<li>
					<a 	data-ajax="false"
						href="<?= url("extendedProfile:show", array("id" => $benevole->userID)) ?>" >
						
						<?= empty($benevole->name) ? $benevole->userID : $benevole->name ?>
							
					</a>
				</li>
				<? endforeach ?>
			</ul>
		<? endif ?>
		
	</div>
	
</div>
	
<? include("footer.php"); ?>