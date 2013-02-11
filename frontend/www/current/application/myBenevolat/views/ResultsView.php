<!-- ------------------- -->
<!-- ResultsView         -->
<!-- Show search results -->
<!-- ------------------- -->


<!-- Page view -->
<div data-role="page">
	
	<!-- Header bar -->
	<? $title=_("Results");
	print_header_bar(true, false, $title); ?>
	
	
	<!-- Page content -->
	<div data-role="content" >
		<? include_once 'notifications.php'; ?>
	
		<ul data-role="listview" >	
			<? if (count($this->result) == 0) :?>
			<li>
				<h4><?= _("No result found")?></h4>
			</li>
			<? endif ?>
			
			<? foreach($this->result as $item) : ?>
				<li>
					<!-- Print Publisher reputation -->
					<a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">	
						<h3><?= _("Title")?> : <?= $item->title ?></h3>
						<!-- Publication fields-->
						<p style="position: relative; margin-left: 30px;">
							<b><?= _('Date of publication') ?></b>: <?= $item->begin ?><br/>
							<b><?= _('Date of expiration') ?></b>: <?= $item->end ?><br/><br/>
							<b><?= _("Mission type") ?></b>: <?= Categories::$missions[$item->typeMission] ?><br/>
							<b><?= _("Quartier") ?></b>: <?= Categories::$mobilite[$item->quartier] ?><br/>
							<b><?= _("Competences") ?></b>: 
						 <? if(gettype($item->competences)=="string"){ ?> <!-- only 1 skill -> string and not array -->
								<?= Categories::$competences[$item->competences]?><br/><br/>
						 <? }else{ ?>
								<? foreach($item->competences as $competences): echo Categories::$competences[$competences]." - "; endforeach;?><br/><br/>
						 <? } ?>
						 	<b>Association ID:</b><?= $item->publisherID?><br/> 
							
							<!-- Project reputation-->	
							<p style="display:inline; margin-left: 30px;" > <b><?= _("Reputation")?>:</b> </p>  
							<p style="display:inline; " >
								<?php
									// Disable reputation stars if there are no votes yet 
									if($this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] == '0') : ?> 
									<?php for($i=1 ; $i <= 5 ; $i++) {?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:120px; margin-top:3px;"/>
									<?php } ?>
								<?php else: ?>
									<?php for($i=1 ; $i <= 5 ; $i++) { ?>
										<?php if($i*20-20 < $this->reputationMap[$item->getPredicateStr().$item->publisherID] ) { ?>
											<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:120px; margin-top:3px;" />
										<?php } else { ?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:120px; margin-top:3px;"/>
										<?php } ?>
									<? } ?>
								<?php endif; ?>
							</p>
							<p style="display:inline; color: #2489CE; font-size:80%; margin-left:70px;"> <?php echo $this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] ?> rates </p>
						</p>
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
	</div>
</div>

<? include_once 'MainView.php'; ?>
