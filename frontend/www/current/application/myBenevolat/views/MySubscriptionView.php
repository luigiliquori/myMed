<!-- ------------------ -->
<!-- MyOpportunity View -->
<!-- ------------------ -->

<div data-role="page" id =myopportunity>

	<!-- Page header bar -->
	<? $title = _("My subscriptions");
	   print_header_bar("?action=main", "defaultHelpPopup", $title); ?>

	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("My subscriptions capsule title") ?> ?</h3>
			<p><?= _("My subscriptions capsule text")?></p>
		</div>
		<br />
		
		<script type="text/javascript">
				
		</script>
	
		<!-- New subscription button -->
		<div class="ui-grid-a">
			<div class="ui-block-a" style="float: left;">
				<?php if(count($this->subscriptions_name)!=0):?>
				<form action=?action=mySubscription&subscriptions=true method="POST" data-ajax="false"/>
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
				<a href="?action=mySubscriptionManagement" data-icon="pencil" data-role="button" data-inline="true" data-theme="e" style="float: right;"><?= _("Manage subscriptions") ?></a><br />	
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
					<!-- Print Publisher reputation -->
					<a data-ajax="false" href="?action=details&id=<?= $item->id ?>">			
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
								<? foreach($item->competences as $competences): echo Categories::$competences[$competences]." , "; endforeach;?><br/><br/>
						 <? } ?> 
							
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
		<!-- Help popup -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<p> <?= _("My subscriptions help text") ?></p>
	</div>
</div>
<!-- END Page MyOpportunityView-->
