<div data-role="page">
 <? $title = _("Projects list");
	 print_header_bar(true, false, $title); ?>
	
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
	
		<ul data-role="listview" >
		
			<li data-role="list-divider"><?= _("Results") ?></li>
			
			<? if (count($this->result) == 0) :?>
			<li>
				<h4><?= _("No result found")?></h4>
			</li>
			<? endif ?>
			
			<? foreach($this->result as $item) : ?>
				<li>
					<!-- Print Publisher reputation -->
					<a href="?action=modify&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3><?= _("Title")?> : <?= $item->title ?></h3>
						
						<p style="position: relative; margin-left: 30px;">
							<b><?= _("Themes") ?></b>: <?= $item->theme ?><br/>
							<b><?= _("Programme") ?></b>: <?= $item->other ?><br/><br/>
							<b><?= _('Date of expiration') ?></b>: <?= $item->end ?><br/>
						</p>
						
						<br/>
						
						<p>
							Publisher ID: <?= $item->publisherID ?><br/>
						</p>
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
		
			
	</div>
	
</div>