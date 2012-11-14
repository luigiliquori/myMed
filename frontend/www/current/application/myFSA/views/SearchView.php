<? include("header.php"); ?>
<div data-role="page" id="Search" data-theme="b">
	<? include("header-bar.php"); ?>
		
		<div data-role="content">
			<br />
			<ul data-role="listview" data-filter="true" data-inset="true" data-filter-placeholder="...">
					
				<? if (count($this->result) == 0) :?>
				<li>
					<h4><?= translate('No results found') ?></h4>
				</li>
				<? endif ?>
				
				<? foreach($this->result as $item) : ?>
				<li><a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">	
					<?=  $item->publisherName ?>, <?= $item->pred2 ?>, <?= $item->pred3 ?>
				</a></li>
				<? endforeach ?>				
			</ul>
				
			<div data-role="collapsible" data-collapsed="true" data-content-theme="d">
				<h3><?= translate('Advanced searching') ?></h3>
				<form action="index.php?action=search" method="POST" data-ajax="false">
			
					<label for="textinputs1"> <?= translate('Cathegory') ?> </label> 
					<input id="textinputs1" name="pred2" placeholder="" type="text" />
					<br>
					
					<label for="textinputs2"> <?= translate('Tittle') ?> </label> 
					<input id="textinputs2"  name="pred3" placeholder="" type="text" />
					<br>
					
					<input type="hidden" name="method" value="Rechercher" />
					<center><input type="submit" value="<?= translate('Search') ?>" data-icon="search" data-inline="true" data-theme="b"/></center>
				</form>
			</div>	
				
		</div>
	
<? include("footer.php"); ?>
</div>
</body>