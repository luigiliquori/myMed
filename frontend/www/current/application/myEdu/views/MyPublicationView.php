<!-- ------------------ -->
<!-- MyPublication View -->
<!-- ------------------ -->

<div data-role="page" id ="mypublicationview">

	<!-- Page header bar -->
	<? $title = _("My Publication");
	   print_header_bar("?action=main", false, $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("<<<<< Little help >>>>>") ?> ?</h3>
			<p><?= _("Here is the space summarizing all of your publications, as well as a way to create new ones.")?></p>
		</div>
		<br />
	
		<!-- New publication button -->
		<a href="index.php?action=publish&method=new_publication" data-icon="pencil" data-role="button" ><?= _("New publication") ?></a><br />	
		<br />
		
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
					<a href="?action=publish&method=modify_publication&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3><?= _("Title")?> : <?= $item->title ?></h3>
						<!-- Publication fields-->
						<p style="position: relative; margin-left: 30px;">
							<b><?= _('Date of expiration') ?></b>: <?= $item->end ?><br/><br/>
							<b><?= _("Area") ?></b>: <?= $item->area ?><br/>
							<b><?= _("Category") ?></b>: <?= $item->category ?><br/>
							<b><?= _("Locality") ?></b>: <?= $item->locality ?><br/>
							<b><?= _("Organization") ?></b>: <?= $item->organization ?><br/><br/>
							<b>Publisher ID:</b><?= $item->publisherID ?><br/> 
							<!-- Project reputation-->	
							<p style="display:inline; margin-left: 30px;" > <b><?= _("Project reputation")?>:</b> </p>  
							<p style="display:inline; " >
								<?php
									// Disable reputation stars if there are no votes yet 
									if($this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] == '0') : ?> 
									<?php for($i=1 ; $i <= 5 ; $i++) {?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="margin-left: <?= $i ?>0px;  margin-top:3px;"/>
									<?php } ?>
								<?php else: ?>
									<?php for($i=1 ; $i <= 5 ; $i++) { ?>
										<?php if($i*20-20 < $this->reputationMap[$item->getPredicateStr().$item->publisherID] ) { ?>
											<img alt="rep" src="img/yellowStar.png" width="12" Style="margin-left: <?= $i ?>0px;  margin-top:3px;" />
										<?php } else { ?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="margin-left: <?= $i ?>0px; margin-top:3px;"/>
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
	<!-- END Page content -->
	
</div>
<!-- END Page MyPublicationView-->