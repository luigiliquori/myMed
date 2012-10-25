<? include("header.php"); ?>
<div data-role="page" id="Search">
	<div class="wrapper">
	<? include("header-bar.php"); ?>
		
		<div data-role="content" data-theme="b">
			<br />
			<ul data-role="listview" data-filter="true" data-inset="true" data-filter-placeholder="...">
				<!-- petla koncze! -->		
				<? if (count($this->result) == 0) :?>
				<li>
					<h4>No result found</h4>
				</li>
				<? endif ?>
				<?// $_SESSION['publication'] = $this->result; ?>
				<? foreach($this->result as $item) : ?>
				<li><a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">	
					<?php $truncated = substr($item->publisherID, 6)	?>	
					<!--<b>Publisher ID</b> : --><?=  $truncated ?>,
					<!--<b>Pred1</b>: --><?//= $item->pred1 ?>
					<!--<b>Pred2</b>: --><?= $item->pred2 ?>,
					<!--<b>Pred3</b>: --><?= $item->pred3 ?>
					<!--<b>Begin</b>: --><?//= $item->begin ?>
					<!--<b>End</b>: --><?//= $item->end ?>
					<!--<b>Wrapped1</b>: --><?//= $item->wrapped1 ?><br/>
					<!--<b>Wrapped2</b>: --><?//= $item->wrapped2 ?><br/>
				</a></li>
				<? endforeach ?>
				<!-- petla koncze! -->
			</ul>
				
				<div data-role="collapsible" data-collapsed="true" data-theme="b" data-icon="plus">
				<h3><?= translate('Advanced searching') ?></h3>
				<form action="index.php?action=publish" method="POST" data-ajax="false">
				
					<div data-role="fieldcontain" style="margin-left: auto;margin-right: auto;">
						<fieldset data-role="controlgroup" >
							<label for="textinputs1"> <?= translate('Cathegory') ?> </label> 
							<input id="textinputs1" name="pred2" placeholder="" type="text" />
						</fieldset>
					</div>
					
					<div data-role="fieldcontain" style="margin-left: auto;margin-right: auto;">
						<fieldset data-role="controlgroup" >
						<label for="textinputs2"> <?= translate('Tittle') ?> </label> 
						<input id="textinputs2"  name="pred3" placeholder="" type="text" />
						</fieldset>
					</div>
						
						<input type="hidden" name="method" value="Rechercher" />
						<input type="submit" value="<?= translate('Search') ?>" data-icon="gear"/>
						</form>
				</div>	
				
				
				<div class="push"></div>
				</div>
			</div>
<? include("footer.php"); ?>
</div>
