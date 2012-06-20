<? include("header.php"); ?>

<div data-role="page">

	<? include("header-bar.php") ?>
	
	<div data-role="content" >
	
		<ul data-role="listview" >
		
			<li data-role="list-divider">Results</li1>
			
			<? foreach($this->result as $item) : ?>
				<li>
					<a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">			
						<b>Publisher ID</b> : <?= $item->publisherID ?><br/>
						<b>Begin</b>: <?= $item->begin ?><br/>
						<b>End</b>: <?= $item->end ?><br/>
						<b>Wrapped1</b>: <?= $item->wrapped1 ?><br/>
						<b>Wrapped2</b>: <?= $item->wrapped2 ?><br/>
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
		
			
	</div>
	

</div>

<? include("footer.php"); ?>