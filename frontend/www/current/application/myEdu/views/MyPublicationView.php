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

<div data-role="page" id ="mypublicationview">

	<!-- Page header bar -->
	<? $title = _("My offers");
	   print_header_bar("?action=main", "helpPopup", $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Offers management") ?></h3>
			<p><?= _("Check and create your offers.")?></p>
		</div>
		<br />
	
		<!-- New publication button -->
		<a href="index.php?action=publish&method=new_publication" data-icon="pencil" data-role="button" ><?= _("New offer") ?></a><br />	
		<br />
		
		<!-- List of user publications -->
		<ul data-role="listview" >
		
			<li data-role="list-divider"><?= _("Your offers") ?></li>
			
			<? if (count($this->result) == 0) :?>
			<li>
				<h4><?= _("No result found")?></h4>
			</li>
			<? endif ?>

			<? foreach($this->result as $item) : ?>
				<li>
					<a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3><?= _("Title")?> : <?= $item->title ?></h3>
						<!-- Publication fields-->
						<p style="position: relative; margin-left: 30px;">
							<b><?= _('Deadline') ?></b>: <?= $item->end ?><br/><br/>
						 <? $domain="Not Defined";
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
							<p style="display:inline; margin-left: 30px;" > <b><?= _("Reputation")?>:</b> </p>  
							<p style="display:inline; " >
								<?php
									// Disable reputation stars if there are no votes yet 
									if($this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] == '0') : ?> 
									<?php for($i=1 ; $i <= 5 ; $i++) {?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:135px; margin-top:3px;"/>
									<?php } ?>
								<?php else: ?>
									<?php for($i=1 ; $i <= 5 ; $i++) { ?>
										<?php if($i*20-20 < $this->reputationMap[$item->getPredicateStr().$item->publisherID] ) { ?>
											<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:135px; margin-top:3px;" />
										<?php } else { ?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:135px; margin-top:3px;"/>
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
		<!-- HELP POPUP -->
		<!-- ----------------- -->
		<div data-role="popup" id="helpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<p><?= _("Display and create your offers by clicking on the button \"New offer\".")?></p>
		</div>	
	</div>
	<!-- END Page content -->
	
</div>
<!-- END Page MyPublicationView-->