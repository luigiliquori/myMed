<!-- ------------------ -->
<!-- MyOpportunity View -->
<!-- ------------------ -->

<div data-role="page" id =myopportunity>

	<!-- Page header bar -->
	<? $title = _("My subscriptions");
	   print_header_bar("?action=main", "helpPopup", $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("My subscriptions") ?></h3>
			<p><?= _("Browse your subscriptions or create a new one, by clicking on the \"Manage subscriptions\" button.")?></p>
		</div>
		<br />
		
		<script type="text/javascript">
				
		</script>
	
		<!-- New subscription button -->
		<div class="ui-grid-a">
			<div class="ui-block-a" style="float: left;">
				<?php if(count($this->subscriptions_name)!=0):?>
				<form action=?action=myOpportunity&opportunities=true method="POST" data-ajax="false"/>
					<select name="Subscription_list" id="Subscription_list" data-inline="true" style="float: left;" onchange="this.form.submit();"> <!--  data-native-menu="false" -->
						<?php foreach($this->subscriptions_name as $value):?>
							<?php if($value == $this->actual_subscription_name):?>
								<option selected value="<?= $value ?>"><?= $value ?></option>
							<?php else :?>
								<option value="<?= $value ?>"><?= $value ?></option>
							<?php endif;?>
						<?php endforeach;?>
					</select>
					<!-- <input type="submit" value="<?= _("OK")?>" data-inline="true"/>-->
				</form>
				<?php endif;?>
			</div>
			<div class="ui-block-b" style="float: right;">
				<a href="?action=myOpportunityManagement" data-icon="pencil" data-role="button" data-inline="true" data-theme="e" style="float: right;"><?= _("Manage subscriptions") ?></a><br />	
			</div>
		</div>
		<br />
		<br />
		
		<!-- List of user publications -->
		<ul data-role="listview" >
		
			<li data-role="list-divider"><?= _("Results") ?></li>
			
			<?php $entries_number = count($this->search_result);?>
			<? foreach($this->search_result as $item) : ?>
				<li>
					<a href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3><?= _("Title")?> : <?= $item->title ?></h3>
						<!-- Publication fields-->
						<p style="position: relative; margin-left: 30px;">
							<b><?= _('Deadline') ?></b>: <?= $item->end ?><br/><br/>
							<? $domain="Not defined";
							foreach(Categories::$areas as $k=>$v) :
								if(in_array($item->area, $v)){
									$domain=$k;
									break;
								}
							endforeach; ?>
							<b><?= _("Topic") ?></b>: <?= _($item->area) ?><br/>
							<b><?= _("Domain")?></b>: <?= _($domain) ?><br/>
							<b><?= _("Category") ?></b>: <?= Categories::$categories[$item->category] ?><br/>
							<b><?= _("Locality") ?></b>: <?= Categories::$localities[$item->locality] ?><br/>
							<b><?= _("Organization") ?></b>: <?= Categories::$organizations[$item->organization] ?><br/><br/>
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
								<p style="display:inline; margin-left:80px;  color: #2489CE; font-size:80%; "> <?php echo $this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] ?> <?= _("rates")?> </p>
						</p>			
					</a>
				</li>
				<? endforeach ?>
			<? //endforeach?>
			<? if($entries_number == 0):?>
				<li>
					<h4><?= _("No result found")?></h4>
				</li>
			<?php endif ?>
			
		</ul>
		
		<!-- HELP POPUP -->
		<!-- ----------------- -->
		<div data-role="popup" id="helpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<p><?= _("Set up parameters of your subscriptions that will allows to interact with the social network myEdu.")?></p>
		</div>
	</div>
	<!-- END Page content -->
	
</div>
<!-- END Page MyOpportunityView-->