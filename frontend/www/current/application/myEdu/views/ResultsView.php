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

<div data-role="page">
	<? $title=_("Results");
	print_header_bar("?action=Find&search=true", false, $title); ?>
	
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
						
						<p style="position: relative; margin-left: 30px;">
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
							<b><?= _('Deadline') ?></b>: <?= $item->end ?><br/>
						</p>
						
						<br/>
						
						<p>
							Publisher ID: <?= $item->publisherID ?><br/>
							<p style="display:inline;" > <?= _("Reputation")?>: </p>  
							<p style="display:inline;" >
								<?php
									// Disable reputation stars if there are no votes yet 
									if($this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] == '0') : ?> 
									<?php for($i=1 ; $i <= 5 ; $i++) {?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:85px; margin-top:3px;"/>
									<?php } ?>
								<?php else: ?>
									<?php for($i=1 ; $i <= 5 ; $i++) { ?>
										<?php if($i*20-20 < $this->reputationMap[$item->getPredicateStr().$item->publisherID] ) { ?>
											<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:85px; margin-top:3px;" />
										<?php } else { ?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:85px; margin-top:3px;"/>
										<?php } ?>
									<? } ?>
								<?php endif; ?>
								<p style="display:inline; margin-left:55px;  color: #2489CE; font-size:80%;"> <?php echo $this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] ?> <?= _("rates")?> </p>
							</p>
						</p>
						
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
	</div>
</div>

<? include_once 'MainView.php'; ?>
