
<div data-role="page">
  <? $title = _("Results");
	 print_header_bar(true, false, $title); ?>
	
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
	
		<ul data-role="listview" >
		
			<li data-role="list-divider"><?= _("Results") ?></li>
			
			<? if (count($this->result) == 0) :?>
			<li>
				<h4>No result found</h4>
			</li>
			<? endif ?>
			
			<? foreach($this->result as $item) : ?>
				<li>
					<!-- Print Publisher reputation -->
					<a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3><?= _("Title")?> : <?= $item->title ?></h3>
						
						<p style="position: relative; margin-left: 30px;">
							<b><?= _("Themes") ?></b>: <?= Categories::$themes[$item->theme] ?><br/>
							<b><?= _("Programme") ?></b>: <?= Categories::$calls[$item->other] ?><br/><br/>
							<b><?= _('Date of expiration') ?></b>: <?= $item->end ?><br/>
						</p>
						
						<br/>
						
						<p>
							Publisher ID: <?= $item->publisherID ?><br/>
							reputation: 
							<?php for($i=1 ; $i <= 5 ; $i++) { ?>
								<?php if($i*20-20 < $this->reputationMap[$item->getPredicateStr().$item->publisherID] ) { ?>
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
	
</div>

<? include_once 'MainView.php'; ?>
