<? include("header.php"); ?>

<div data-role="page" id="results">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3empty(
				"Résultats") ?>
		<? include("notifications.php")?>
	</div>
	
	<div data-role="content" >
	
		<ul data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="filtrer parmi les résultats">
			
			<select data-theme="c" data-mini="true" name="slider2" id="flip-a2" data-role="slider"
				onchange="">
				<option value="3">Trier par réputation</option>
				<option value="0">Trier par nom de partenaire</option>
			</select>
			
			<select data-theme="e"  data-mini="true" name="slider" id="flip-a" data-role="slider"
				onchange="$.get('../../lib/dasp/ajax/Subscribe', { code: $(this).val(), application: '<?= APPLICATION_NAME ?>' ,namespace: '<?= $_GET['namespace'] ?>' ,data: '' } );">
				<option value="3">Souscrire aux futurs contenus correspondant à cette recherche</option>
				<option value="0">Désabonner</option>
			</select>
			
			<? if (count($this->result) == 0) :?>
			<li>
				<h4>Votre recherche n'a abouti à aucun résultat.</h4>
			</li>
			<? endif ?>
			
			<? foreach($this->result as $item) : ?>
				<li>
					<a href="./?action=details&namespace=<?= $_GET['namespace'] ?>&id=<?= urlencode($item->id) ?>" data-ajax="false">		
						<span style="font-weight: lighter;">Offre publiée par </span>  <?= $item->user ?> <span style="font-weight: lighter;">(26/07/2012), réputation </span> 100% <span style="font-weight: lighter;">, id# </span><?= $item->id ?>
 					</a>
				</li>
			<? endforeach ?>
			
			<? if (!empty($this->suggestions)) :?>
				<li data-role="list-divider">Suggestions:</li>
			<? foreach($this->suggestions as $item) : ?>
				<li>
					<a href="./?action=details&namespace=<?= $_GET['namespace'] ?>&id=<?= urlencode($item->id) ?>" data-ajax="false">		
						<b>...</b> : <?= print_r($item) ?><br/>
 					</a>
				</li>
			<? endforeach ?>
			<? endif ?>
			
		</ul>
		
			
	</div>
	

</div>

<? include("footer.php"); ?>