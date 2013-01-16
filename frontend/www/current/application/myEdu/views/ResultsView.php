
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
					<!-- Print Publisher reputation -->
					<a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3>Wrapped: <?= $item->wrapped1 ?> - <?= $item->wrapped2 ?></h3>
						
						<p style="position: relative; margin-left: 30px;">
							<b>Pred1</b>: <?= $item->pred1 ?><br/>
							<b>Pred2</b>: <?= $item->pred2 ?><br/>
							<b>Pred3</b>: <?= $item->pred3 ?><br/><br/>
							<b>Begin</b>: <?= $item->begin ?><br/>
							<b>End</b>: <?= $item->end ?><br/>
						</p>
						
						<br/>
						
						<p>
							Publisher ID: <?= $item->publisherID ?><br/>
							reputation: 
							<?php for($i=1 ; $i <= 5 ; $i++) { ?>
								<?php if($i*20-20 < $this->reputationMap[$item->publisherID] ) { ?>
									<img alt="rep" src="img/yellowStar.png" width="10" Style="left: <?= $i ?>0px; margin-left: 80px; margin-top:3px;" />
								<?php } else { ?>
									<img alt="rep" src="img/grayStar.png" width="10" Style="left: <?= $i ?>0px; margin-left: 80px; margin-top:3px;"/>
								<?php } ?>
							<? } ?>
						</p>
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
