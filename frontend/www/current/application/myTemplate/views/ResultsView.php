
<div data-role="page">

	<? print_header_bar(true, false); ?>
	
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
	
		<ul data-role="listview" >
		
			<li data-role="list-divider">Results</li>
			
			<? if (count($this->result) == 0) :?>
			<li>
				<h4>No result found</h4>
			</li>
			<? endif ?>
			
			<? foreach($this->result as $item) : ?>
				<li>
					<a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">			
						<b>Publisher ID</b> : <?= $item->publisherID ?><br/>
						<b>Pred1</b>: <?= $item->pred1 ?><br/>
						<b>Pred2</b>: <?= $item->pred2 ?><br/>
						<b>Pred3</b>: <?= $item->pred3 ?><br/>
						<b>Begin</b>: <?= $item->begin ?><br/>
						<b>End</b>: <?= $item->end ?><br/>
						<b>Wrapped1</b>: <?= $item->wrapped1 ?><br/>
						<b>Wrapped2</b>: <?= $item->wrapped2 ?><br/>
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
		
			
	</div>
	
	<? print_footer_bar_main("#find"); ?>

</div>

<? include_once 'MainView.php'; ?>
<? include_once 'FindView.php'; ?>
<? include_once 'DetailsView.php'; ?>
<? include_once 'ProfileView.php'; ?>
<? include_once 'UpdateProfileView.php'; ?>
