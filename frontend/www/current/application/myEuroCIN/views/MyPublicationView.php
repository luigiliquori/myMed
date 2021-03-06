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
<div data-role="page" id ="mypublicationview">


	<!-- Page header bar -->
	<? $title = _("My publications");
	   print_header_bar("?action=main", false, $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<!-- Notification pop up -->		
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("My publications") ?></h3>
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
					<a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3><?= _("Title")?> : <?= $title = $item->data; ?></h3>
							<p style="position: relative; margin-left: 30px;">
								<b><?= _('Deadline') ?></b>: <?= $item->expire_date ?>
							 <? if(!empty($item->expire_date) && $item->expire_date!="--"){
							 		$date = strtotime(date('d-m-Y'));
									$expiration_date = strtotime($item->expire_date);
									if($date > $expiration_date){
										echo _("<b style='color:red;margin-left:10px'>PUBLICATION EXPIRED</b>");
									}
							 	} ?>
								<br/><br/>
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
								<br><br>
								<b>Publisher ID:</b><?= $item->publisherID ?><br/> 
								<!-- Project reputation-->	
								<p style="display:inline; margin-left: 30px;" > <b><?= _("Project reputation")?>:</b> </p>  
								<p style="display:inline; " >
									<?php
										// Disable reputation stars if there are no votes yet 
										if($this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] == '0') : ?> 
										<?php for($i=1 ; $i <= 5 ; $i++) {?>
												<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px;margin-left:185px; margin-top:3px;"/>
										<?php } ?>
									<?php else: ?>
										<?php for($i=1 ; $i <= 5 ; $i++) { ?>
											<?php if($i*20-20 < $this->reputationMap[$item->getPredicateStr().$item->publisherID] ) { ?>
												<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:185px; margin-top:3px;" />
											<?php } else { ?>
												<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px;margin-left:185px; margin-top:3px;"/>
											<?php } ?>
										<? } ?>
									<?php endif; ?>
								</p>
								<p style="display:inline; font-size:80%; margin-left:70px;"> <?php echo $this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] ?> <?= _("rates")?> </p>
							
						</p>			
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
			
	</div>
	<!-- END Page content -->
	
</div>
<!-- END Page MyPublicationView-->