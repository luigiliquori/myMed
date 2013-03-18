<!-- ------------------- -->
<!-- ResultsView         -->
<!-- Show search results -->
<!-- ------------------- -->


<!-- Page view -->
<div data-role="page">
	
	<!-- Header bar -->
	<? $title=_("Results");
	print_header_bar('?action=Find&search=true', false, $title); ?>
	
	
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
						<h3><?= _("Title")?> : <?= $item->getTitle() ?></h3>
						
						<p style="position: relative; margin-left: 30px;">
							<b><?= _("Locality") ?></b>: <?= Categories::$localities[$item->Nazione] ?><br/>
							<b><?= _("Language") ?></b>: <?= Categories::$languages[$item->Lingua] ?><br/>
							<b><?= _("Categories") ?></b>: 
							<? if( isset($item->Arte_Cultura) ) echo _("Art/Cultur "); ?> 
							<? if( isset($item->Natura) ) echo _("Nature "); ?>
							<? if( isset($item->Tradizioni) ) echo _("Traditions "); ?>
							<? if( isset($item->Enogastronomia) ) echo _("Enogastronimy "); ?>
							<? if( isset($item->Benessere) ) echo _("Wellness "); ?>
							<? if( isset($item->Storia) ) echo _("History "); ?>
							<? if( isset($item->Religione) ) echo _("Religion "); ?>
							<? if( isset($item->Escursioni_Sport) ) echo _("Sport "); ?>
						</p>
						
						<br/>
						
						<p>
							Publisher ID: <?= $item->publisherID ?><br/>
							<p style="display:inline;" > Reputation: </p>  
							<p style="display:inline;" >
								<?php
									// Disable reputation stars if there are no votes yet 
									if($this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] == '0') : ?> 
									<?php for($i=1 ; $i <= 5 ; $i++) {?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:80px; margin-top:3px;"/>
									<?php } ?>
								<?php else: ?>
									<?php for($i=1 ; $i <= 5 ; $i++) { ?>
										<?php if($i*20-20 < $this->reputationMap[$item->getPredicateStr().$item->publisherID] ) { ?>
											<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:80px; margin-top:3px;" />
										<?php } else { ?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:80px; margin-top:3px;"/>
										<?php } ?>
									<? } ?>
								<?php endif; ?>
								<p style="display:inline; margin-left:55px; font-size:80%;"> <?php echo $this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] ?> rates </p>
							</p>
						</p>
						
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
	</div>
</div>

<? include_once 'MainView.php'; ?>
