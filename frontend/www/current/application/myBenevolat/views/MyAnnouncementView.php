<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
<!-- ------------------ -->
<!-- MyPublication View -->
<!-- ------------------ -->


<!-- Page view -->
<div data-role="page" id ="myannouncementview">


	<!-- Page header bar -->
	<? $title = _("My announcements");
	   print_header_bar("?action=main", "viewHelpPopup", $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<!-- Notification pop up -->		
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error); ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("my announcements capsule title") ?></h3>
			<p><?= _("my announcements capsule text")?></p>
		</div>
		<br />
	
		<!-- New publication button -->
		<a href="index.php?action=publish&method=new_announcement" data-icon="pencil" data-role="button" ><?= _("New announcement") ?></a><br />	
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
					<a data-ajax="false" href="?action=details&id=<?= $item->id ?>">		
						<h3><?= _("Title")?> : <?= $item->title ?></h3>
						<!-- Publication fields-->
						<p style="position: relative; margin-left: 30px;">
							<b><?= _('Publication date') ?></b>: <?= $item->begin ?><br/>
							<b><?= _('Deadline') ?></b>: <?= $item->end ?><br/><br/>
							<b><?= _("Mission type") ?></b>: <?= Categories::$missions[$item->typeMission] ?><br/>
							<b><?= _("District") ?></b>: <?= Categories::$mobilite[$item->quartier] ?><br/>
							<b><?= _("Skills") ?></b>: 
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
									if($this->noOfRatesMap[$item->id.$item->publisherID] == '0') : ?> 
									<?php for($i=1 ; $i <= 5 ; $i++) {?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:120px; margin-top:3px;"/>
									<?php } ?>
								<?php else: ?>
									<?php for($i=1 ; $i <= 5 ; $i++) { ?>
										<?php if($i*20-20 < $this->reputationMap[$item->id.$item->publisherID] ) { ?>
											<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:120px; margin-top:3px;" />
										<?php } else { ?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:120px; margin-top:3px;"/>
										<?php } ?>
									<? } ?>
								<?php endif; ?>
							</p>
							<p style="display:inline; font-size:80%; margin-left:60px;"> <?php echo $this->noOfRatesMap[$item->id.$item->publisherID] ?> rates </p>
						</p>			
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
			
	</div>
	<!-- END Page content -->
	<!-- Help popup -->
	<div data-role="popup" id="viewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<p> <?= _("my announcement aide text") ?></p>
	</div>
</div>
<!-- END Page MyPublicationView-->