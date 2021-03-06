<!-- ------------------- -->
<!-- mySubscription View -->
<!-- ------------------- -->


<!-- Page view -->
<div data-role="page" id =mySubscription>


	<!-- Page header bar -->
	<? $title = _("My subscriptions");
	   print_header_bar("?action=main", false, $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<!-- Notification pop up -->
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Litle help") ?> ?</h3>
			<p><?= _("Here you can manage your subsciptions. You will recive all the message releated to the keywords you specify. ")?></p>
		</div>
		<br />
		
		<?php 
			//echo var_dump($this->search_result);
		?>
	
		<!-- New subscription button -->
		<a href="?action=mySubscriptionManagement" data-icon="pencil" data-role="button" data-inline="true" data-theme="e" style="float: right;"><?= _("New subscription") ?></a><br />	
		<br />
		<br />
		<br />
		<br />
		
		<!-- List of user publications -->
		<ul data-role="listview" >
		
			<li data-role="list-divider"><?= _("Results") ?></li>
			
			<? if (count($this->search_result) == 0) :?>
			<li>
				<h4><?= _("No result found")?></h4>
			</li>
			<? endif ?>
			<? foreach($this->search_result as $result):?>
			<? foreach($result as $item) : ?>
				<li>
					<a href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3><?= _("Title")?> : <?= $item->title ?></h3>
						<!-- Publication fields-->
						<p style="position: relative; margin-left: 30px;">
							<b><?= _('Date of expiration') ?></b>: <?= $item->end ?><br/><br/>
							<b><?= _("Area") ?></b>: <?= _($item->area) ?><br/>
							<b><?= _("Category") ?></b>: <?= _($item->category) ?><br/>
							<b>Publisher ID:</b><?= $item->publisherID ?><br/> 
								<!-- Project reputation-->	
								<p style="display:inline; margin-left: 30px;" > <b><?= _("Project reputation")?>:</b> </p>  
								<p style="display:inline;" >
									<?php
										// Disable reputation stars if there are no votes yet 
										if($this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] == '0') : ?> 
										<?php for($i=1 ; $i <= 5 ; $i++) {?>
												<img alt="rep" src="img/grayStar.png" width="12" Style="margin-left: <?= $i ?>0px; margin-top:3px;"/>
										<?php } ?>
									<?php else: ?>
										<?php for($i=1 ; $i <= 5 ; $i++) { ?>
											<?php if($i*20-20 < $this->reputationMap[$item->getPredicateStr().$item->publisherID] ) { ?>
												<img alt="rep" src="img/yellowStar.png" width="12" Style="margin-left: <?= $i ?>0px; margin-top:3px;" />
											<?php } else { ?>
												<img alt="rep" src="img/grayStar.png" width="12" Style="margin-left: <?= $i ?>0px; margin-top:3px;"/>
											<?php } ?>
										<? } ?>
									<?php endif; ?>
								</p>
								<p style="display:inline; margin-left:80px;  color: #2489CE; font-size:80%; "> <?php echo $this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] ?> rates </p>
						</p>			
					</a>
				</li>
			<? endforeach ?>
			<? endforeach?>
			
		</ul>
			
	</div>
	<!-- END Page content -->
	
</div>
<!-- END Page mySubscriptionView-->