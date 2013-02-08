

<div data-role="page" id =myopportunitymanagement>

	<!-- Page header bar -->
	<? $title = _("Manage subscriptions");
	   print_header_bar("?action=myOpportunity&opportunities=true", "helpPopup", $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		<?print_notification($this->success.$this->error);?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("<<<< TITLE >>>>") ?></h3>
			<p><?= _("Here you can manage your subscriptions.")?></p>
		</div>
		<br />
	</div>
	
	<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Add a new subscription') ?> :</h3>
				
				<form action="index.php?action=myOpportunityManagement" method="POST" data-ajax="false">
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
							<input type="checkbox" name="categoryBox" id="check-view-e" onclick="toggle(this, '#find_category_content')"/> 
							<label for="check-view-e"><?= _("Category")?></label>
						</div>
						<div class="ui-block-b">
							<select disabled name="Category" id="find_category_content" data-native-menu="false">
								<option value=""><?= _("Select category") ?></option>
								<? foreach (Categories::$categories as $k=>$v) :?>
									<option value="<?= $k ?>"><?= $v ?></option>
								<? endforeach ?>
							</select>
						</div>
					</div>
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">
							<div class="ui-block-a">
								<input type="checkbox" name="organizationBox" id="check-view-a" onclick="toggle(this, '#find_organization_content')"/> 
								<label for="check-view-a"><?= _("Organization")?></label>
							</div>
							<div class="ui-block-b">
								<select disabled name="organization" id="find_organization_content" data-native-menu="false">
									<option value=""><?= _('Select organization') ?></option>
									<? foreach (Categories::$organizations as $k=>$v) :?>
										<option value="<?= $k ?>"><?= $v ?></option>
									<? endforeach ?>
								</select>
							</div>
						</div>
						<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
							<div class="ui-block-a">
								<input type="checkbox" onclick="toggle(this, '#find_locality_content')" name="localityBox" id="check-view-b"/> <label for="check-view-b"><?= _("Locality")?></label>
							</div>
							<div class="ui-block-b">
								<select disabled name="locality" id="find_locality_content" data-native-menu="false">
									<option value=""><?= _('Select locality') ?></option>
									<? foreach (Categories::$localities as $k=>$v) :?>
										<option value="<?= $k ?>"><?= $v ?></option>
									<? endforeach ?>
								</select>
							</div>
						</div>
						<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
							<div class="ui-block-a">
								<input type="checkbox" onclick="toggle(this, '#find_area_content')" name="areaBox" id="check-view-c"/> 
								<label for="check-view-c"><?= _("Area")?></label>
							</div>
							<div class="ui-block-b">
								<select disabled name="Area" id="find_area_content" data-native-menu="false">
									<option value=""><?= _('Select area') ?></option>
										<? foreach (Categories::$areas as $k=>$v) :?>
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
					if(isset($val->category)){
						$result .= _($val->category);
					}
					if(isset($val->organization)){
						$result .= ", "._($val->organization);
					}
					if(isset($val->locality)){
						$result .= ", "._($val->locality);
					}
					if(isset($val->area)){
						$result .= ", "._($val->area);
					}
					return $result;
				}
			?>
			
			<!-- Subscription list -->
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('My subscriptions') ?> :</h3>
				<?php
					//echo "SUB :".$this->sub."<br/><br/>";
					//echo var_dump($this->response)."<br/><br/>";
					echo '<ul data-role="listview" data-filter="true" >';
					foreach ($this->response as $val){
						echo '<li><div class="ui-grid-a">';
						echo '<div class="ui-block-a">';
						echo _("Subscription ").$val->pubTitle.": <br/>";
						echo createlist($val);
						$predicate="category".$val->category."organization".$val->organization."locality".$val->locality."area".$val->area;
						echo '</div><div class="ui-block-b">';
						echo '<form action="?action=myOpportunityManagement" method="POST" data-ajax="false"  style="float: right;">
								<input type="hidden" name="removeSubscription" value="true" />
								<input type="hidden" name="predicate" value="'.$predicate.'"/>
								<input type="hidden" name="publicationTitle" value="'.$val->pubTitle.'"/>
								<input type="submit" data-icon="delete" data-theme="r" data-inline="true" value="'._("Delete subscription").'" />
							  </form></div></div></li>';
					}
					echo '</ul>';
				?>
			</div>
			
			<!-- Help popup -->
			<div data-role="popup" id="helpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
				<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
				<p> <?= _("Here you can create subscriptions by selecting different parameters related to Universities, Companies, Localities and Themes.") ?></p>
			</div>
</div>