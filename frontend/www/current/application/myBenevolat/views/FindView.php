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
<!-- ------------------------------------ -->
<!-- Find Main View                       -->
<!-- Here you can search for publications -->
<!-- ------------------------------------ -->


<!-- Page view -->
<div data-role="page" id="find" >

	<!-- Header bar -->
	<? $title = _("Search"); 
	   print_header_bar("?action=main", "findViewHelpPopup", $title); ?>

	
	<!-- Page content -->
	<div data-role="content">
		
		<form action="index.php?action=find" method="POST" data-ajax="false">
			<input type="hidden" id="find_competence" name="competence" value="" />
			<input type="hidden" id="find_quartier" name="quartier" value="" />
			<input type="hidden" id="find_mission" name="mission" value="" />
			
			<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
				<h3><?= _("Search capsule title") ?></h3>
				<?= _("Search capsule text")?>
			</div>
			
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Find an announcement by criteria') ?> :</h3>
				
	   			<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">
					<div class="ui-block-a">
						<input type="checkbox" name="competenceBox" id="check-view-e" onclick="toggle(this, '#find_skill_content')"/> 
						<label for="check-view-e"><?= _("Skill")?></label>
					</div>
					<div class="ui-block-b">
						<select disabled name="competence" id="find_skill_content" data-native-menu="false" data-overlay-theme="d">
							<option value=""><?= _("Select skill") ?></option>
							<? foreach (Categories::$competences as $k=>$v) :?>
								<option value="<?= $k ?>"><?= $v ?></option>
							<? endforeach ?>
						</select>
					</div>
				</div>
				<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">
					<div class="ui-block-a">
						<input type="checkbox" name="mobilityBox" id="check-view-a" onclick="toggle(this, '#find_quartier_content')"/> 
						<label for="check-view-a"><?= _("District")?></label>
					</div>
					<div class="ui-block-b">
						<select disabled name="mobility" id="find_quartier_content" data-native-menu="false" data-overlay-theme="d">
							<option value=""><?= _('Select district') ?></option>
							<? foreach (Categories::$mobilite as $k=>$v) :?>
								<option value="<?= $k ?>"><?= $v ?></option>
							<? endforeach ?>
						</select>
					</div>
				</div>
				<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
					<div class="ui-block-a">
						<input type="checkbox" onclick="toggle(this, '#find_mission_content')" name="missionBox" id="check-view-b"/> 
						<label for="check-view-b"><?= _("Mission type")?></label>
					</div>
					<div class="ui-block-b">
						<select disabled name="mission" id="find_mission_content" data-native-menu="false" data-overlay-theme="d">
							<option value=""><?= _('Select mission type') ?></option>
							<? foreach (Categories::$missions as $k=>$v) :?>
								<option value="<?= $k ?>"><?= $v ?></option>
							<? endforeach ?>
						</select>
					</div>
				</div>
				<script type="text/javascript"> 	
					function toggle(chkbox, id) {
					    if(chkbox.checked){
						    $(id).selectmenu('enable');
						}else {
							$(id).selectmenu("disable");
						}
					}

					function toggleText(chkbox, id) {
					    if(chkbox.checked){
						    $(id).textinput('enable');
						}else {
							$(id).textinput('disable'); 
						}
					}
				</script>
				<div style="text-align: center;">
					<input type="submit" data-icon="search" data-theme="g" value="<?=_('Search') ?>"  data-iconpos="right" data-inline="true" onclick="
						$('#find_competence').val($('#find_competence_content').val());
						$('#find_quartier').val($('#find_quartier_content').val());
						$('#find_mission').val($('#find_mission_content').val());
					"/>
				</div>
			</div>
		</form>
		
		<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
			<h3><?= _('Last announcements') ?> :</h3>
			<ul data-role="listview" data-filter="true" >
			<? if (count($this->result) == 0) :?>
				<li>
					<h4><?= _("No result found")?></h4>
				</li>
			<? endif ?>
			<? foreach($this->result as $item) : ?>
				<li>
					<!-- Print Publisher reputation -->
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
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:130px; margin-top:3px;"/>
									<?php } ?>
								<?php else: ?>
									<?php for($i=1 ; $i <= 5 ; $i++) { ?>
										<?php if($i*20-20 < $this->reputationMap[$item->id.$item->publisherID] ) { ?>
											<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:130px; margin-top:3px;" />
										<?php } else { ?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:130px; margin-top:3px;"/>
										<?php } ?>
									<? } ?>
								<?php endif; ?>
							</p>
							<p style="display:inline; font-size:80%; margin-left:70px;"> <?php echo $this->noOfRatesMap[$item->id.$item->publisherID] ?> <?= _("rates")?> </p>
						</p>			
					</a>
				</li>
			<? endforeach ?>
			</ul>
		</div>
	</div>
	
	<!-- Help popup -->
	<div data-role="popup" id="findViewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<p> <?= _("Search help text") ?></p>
	</div>
	
</div>

