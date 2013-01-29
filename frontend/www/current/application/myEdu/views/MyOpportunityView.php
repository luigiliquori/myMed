<!-- ------------------ -->
<!-- MyOpportunity View -->
<!-- ------------------ -->

<div data-role="page" id =myopportunity>

	<!-- Page header bar -->
	<? $title = _("My opportunities");
	   print_header_bar(true, false, $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Litle help") ?> ?</h3>
			<p><?= _("Few words ")?></p>
		</div>
		<br />
	
		<!-- New subscription button -->
		<a href="?action=myOpportunityManagement" data-icon="pencil" data-role="button" data-inline="true" data-theme="e" style="float: right;"><?= _("New subscription") ?></a><br />	
		<br />
		<br />
		<br />
		<br />
		
		<?php 
			echo $this->result;
		?>
		
		<!-- List of user publications -->
		<ul data-role="listview" >
		
			<li data-role="list-divider"><?= _("Results") ?></li>
			
			<? if (count($this->result) == 0) :?>
			<li>
				<h4><?= _("No result found")?></h4>
			</li>
			<? endif ?>

			<? foreach($this->result as $item) : ?>
				<li>
					<a href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3><?= _("Title")?> : <?= $item->title ?></h3>
						<!-- Publication fields-->
						<p style="position: relative; margin-left: 30px;">
							<b><?= _('Date of expiration') ?></b>: <?= $item->end ?><br/><br/>
							<b><?= _("Area") ?></b>: <?= $item->area ?><br/>
							<b><?= _("Category") ?></b>: <?= $item->category ?><br/>
							<b><?= _("Locality") ?></b>: <?= $item->locality ?><br/><br/>
							<b>Publisher ID:</b><?= $item->publisherID ?><br/> 
								<!-- Project reputation-->	
								<p style="display:inline; margin-left: 30px;" > <b>Project Reputation:</b> </p>  
								<p style="display:inline; margin-left: 30px;" >
									<?php
										// Disable reputation stars if there are no votes yet 
										if($this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] == '0') : ?> 
										<?php for($i=1 ; $i <= 5 ; $i++) {?>
												<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:175px; margin-top:3px;"/>
										<?php } ?>
									<?php else: ?>
										<?php for($i=1 ; $i <= 5 ; $i++) { ?>
											<?php if($i*20-20 < $this->reputationMap[$item->getPredicateStr().$item->publisherID] ) { ?>
												<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:175px; margin-top:3px;" />
											<?php } else { ?>
												<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:175px; margin-top:3px;"/>
											<?php } ?>
										<? } ?>
									<?php endif; ?>
									<p style="display:inline; margin-left:35px;  color: #2489CE; font-size:80%;"> <?php echo $this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] ?> rates </p>
								</p>
						</p>			
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
			
	</div>
	<!-- END Page content -->
	
</div>
<!-- END Page MyOpportunityView-->