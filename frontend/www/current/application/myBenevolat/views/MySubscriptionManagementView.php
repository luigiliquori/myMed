<!--
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
 -->
<div data-role="page" id =myopportunitymanagement>

	<!-- Page header bar -->
 <? $title = _("Manage subscriptions");
	if(strpos($_SERVER['HTTP_REFERER'],"?action=extendedProfile&method=show_user_profile"))
		print_header_bar('back', "helpPopup", $title);
	else
	   print_header_bar("?action=mySubscription&subscriptions=true", "helpPopup", $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		<?print_notification($this->success.$this->error);?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Manage subscriptions capsule title") ?></h3>
			<p><?= _("Manage subscriptions capsule text")?></p>
		</div>
		<br />
	</div>
	
	<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Add a new subscription') ?> :</h3>
				
				<form action="index.php?action=mySubscriptionManagement" method="POST" data-ajax="false">
					<input type="hidden" id="addSubscription" name="addSubscription" value="" />

					<!-- subscription name -->
	   				<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">
	   					<div class="ui-block-a">
	   						<input type="checkbox" onclick="toggleText(this, '#nameSub')" name="nameSubCheck" id="check-view-d"/> 
	   						<label for="check-view-d"><?= _("Subscription name")?></label>
	   					</div>
	   					<div class="ui-block-b">
							<input disabled type="text" name="nameSub" id="nameSub" value=""/>
						</div>
					</div>
		   			<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">
						<div class="ui-block-a">
							<input type="checkbox" name="competenceBox" id="check-view-e" onclick="toggle(this, '#find_category_content')"/> 
							<label for="check-view-e"><?= _("Skill")?></label>
						</div>
						<div class="ui-block-b">
							<select disabled name="competence" id="find_category_content" data-native-menu="false" data-overlay-theme="d">
								<option value=""><?= _("Select skill") ?></option>
								<? foreach (Categories::$competences as $k=>$v) :?>
									<option value="<?= $k ?>"><?= $v ?></option>
								<? endforeach ?>
							</select>
						</div>
					</div>
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">
							<div class="ui-block-a">
								<input type="checkbox" name="mobilityBox" id="check-view-a" onclick="toggle(this, '#find_organization_content')"/> 
								<label for="check-view-a"><?= _("District")?></label>
							</div>
							<div class="ui-block-b">
								<select disabled name="mobility" id="find_organization_content" data-native-menu="false" data-overlay-theme="d">
									<option value=""><?= _('Select district') ?></option>
									<? foreach (Categories::$mobilite as $k=>$v) :?>
										<option value="<?= $k ?>"><?= $v ?></option>
									<? endforeach ?>
								</select>
							</div>
						</div>
						<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
							<div class="ui-block-a">
								<input type="checkbox" onclick="toggle(this, '#find_locality_content')" name="missionBox" id="check-view-b"/> 
								<label for="check-view-b"><?= _("Mission type")?></label>
							</div>
							<div class="ui-block-b">
								<select disabled name="mission" id="find_locality_content" data-native-menu="false" data-overlay-theme="d">
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
						<input type="submit" data-icon="plus" data-theme="g" value="<?=_('Subscribe') ?>"  data-iconpos="left" data-inline="true"/>
					</div>
				</form>
			</div>
			
			<?php 
				function createlist($val){
					$result="";
					if(isset($val->competence)){
						$result .= Categories::$competences[$val->competence];
					}
					if(isset($val->mobility)){
						$result .= ", ".Categories::$mobilite[$val->mobility];
					}
					if(isset($val->mission)){
						$result .= ", ".Categories::$missions[$val->mission];
					}
					return $result;
				}
			?>
			
			<!-- Subscription list -->
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('My subscriptions') ?> :</h3>
				<?php
					echo '<ul data-role="listview" data-filter="true" >';
					foreach ($this->response as $val){
						echo '<li><div class="ui-grid-a">';
						echo '<div class="ui-block-a">';
						echo _("Subscription ").$val->pubTitle.": <br/>";
						echo createlist($val);
						$predicate="competences".$val->competence."quartier".$val->mobility."typeMission".$val->mission;
						echo '</div><div class="ui-block-b">';
						//echo '<a href="#" onclick=\'generate_delete_popup("'.$predicate.'","'.$val->pubTitle.'")\' data-icon="delete" type="button" data-rel="popup" data-theme="r" data-inline="true" style="float: right;">'._("Delete subscription").'</a>';
						echo '<a href="#" onclick=\'generate_delete_popup("'.$predicate.'","'.$val->pubTitle.'")\' data-icon="delete" type="button" data-theme="r" data-inline="true" style="float: right;">'._("Delete subscription").'</a>';
						echo '</div></div></li>';
					}
					echo '</ul>';
				?>	
			</div>
			
			<script type="text/javascript">
				function generate_delete_popup(predicate,pubTitle){
					//$("#deletePopup").html("ça marche");
					//$("#deletePopup").html(eval('<a href="#" data-type="button" data-theme="g">hello</a>'));
					$("#deletePopup").html("<?= _("Are you sure ?")?>"+'\
						<div class="ui-grid-a">\
							<div class="ui-block-a">\
								<form action="?action=mySubscriptionManagement" method="POST" data-ajax="false">\
									<input type="hidden" name="removeSubscription" value="true" />\
									<input type="hidden" name="predicate" value="'+predicate+'"/>\
									<input type="hidden" name="publicationTitle" value="'+pubTitle+'"/>\
									<input type="submit" data-theme="g" data-icon="ok" data-inline="true" value="<?= _("Yes")?>" />\
								</form>\
							</div>\
							<div class="ui-block-b">\
								<form action="?action=mySubscriptionManagement" method="POST" >\
							 		<input type="submit" data-theme="r" data-icon="delete" data-inline="true" value="<?= _("No")?>" />\
							 	</form>\
							</div>\
						</div>');
					$("#deletePopup").trigger("create");
					$("#deletePopup").popup("open");
				}
			</script>
			
			<div data-role="popup" class="ui-content" id="deletePopup" style="text-align:center"></div>
			
			
			<!-- Help popup -->
			<div data-role="popup" id="helpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
				<a href="#" data-rel="back" data-role="button" data-theme="a"  data-iconpos="notext" class="ui-btn-right">Close</a>
				<p> <?= _("Manage subscriptions help text") ?></p>
			</div>
</div>