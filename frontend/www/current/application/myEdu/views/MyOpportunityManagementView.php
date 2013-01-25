

<div data-role="page" id =myopportunitymanagement>

	<!-- Page header bar -->
	<? $title = _("Managing my opportunities");
	   print_header_bar(true, true, $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Little help") ?> ?</h3>
			<p><?= _("Few words ")?></p>
		</div>
		<br />
	</div>
	
	<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Adding new subscription') ?> :</h3>
					<form action="index.php?action=myOpportunityManagement" method="POST" data-ajax="false">
						<input type="hidden" id="addSubscription" name="addSubscription" value="" />
			
	   			<fieldset data-role="controlgroup">
					<!-- Categoria -->
					<select name="Category" id="sub_category" id="call">
						<option value=""><?= _("Category") ?></option>
						<? foreach (Categories::$categories as $k=>$v) :?>
							<option value="<?= $k ?>"><?= $v ?></option>
						<? endforeach ?>
					</select>
				</fieldset>
					
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
						<div class="ui-block-a">
							<input type="checkbox" name="organizationBox" id="check-view-a"/> <label for="check-view-a"><?= _("Organization")?></label>
						</div>
						<div class="ui-block-b">
							<select name="organization" id="sub_organization" id="call" data-native-menu="false">
								<option value=""></option>
								<? foreach (Categories::$organizations as $k=>$v) :?>
									<option value="<?= $k ?>"><?= $v ?></option>
								<? endforeach ?>
							</select>
						</div>
					</div>
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
						<div class="ui-block-a">
							<input type="checkbox" name="localityBox" id="check-view-b"/> <label for="check-view-b"><?= _("Locality")?></label>
						</div>
						<div class="ui-block-b">
							<select name="locality" id="sub_locality" id="call" data-native-menu="false">
								<option value=""></option>
								<? foreach (Categories::$localities as $k=>$v) :?>
									<option value="<?= $k ?>"><?= $v ?></option>
								<? endforeach ?>
							</select>
						</div>
					</div>
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
						<div class="ui-block-a">
							<input type="checkbox" name="areaBox" id="check-view-c"/> <label for="check-view-c"><?= _("Area")?></label>
						</div>
						<div class="ui-block-b">
							<select name="Area" id="sub_area" id="call">
								<option value=""></option>
									<? foreach (Categories::$areas as $k=>$v) :?>
										<option value="<?= $k ?>"><?= $v ?></option>
									<? endforeach ?>
							</select>
						</div>
				</div>
			
				<div style="text-align: center;">
					<input type="submit" data-icon="plus" data-theme="g" value="<?=_('Subscribe') ?>"  data-iconpos="right" data-inline="true"/>
				</div>
				</form>
			</div>
			
			<!-- Subscription list -->
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('My subscriptions') ?> :</h3>
				<?php
					//echo $this->response."<br/><br/>";
					$responseObject = json_decode($this->response);
					$subscriptions = (array)$responseObject->dataObject->subscriptions;
					$index=1;
					echo '<ul data-role="listview" data-filter="true" >';
					foreach ($subscriptions as $key=>$value){
						//echo $key."    ";
						$preds = explode("pred",$key);
						//echo _("Subscription parameters:")."<br />";
						echo '<li><div class="ui-grid-a">';
						echo '<div class="ui-block-a">';
						echo _("Subscription ").$index.":<br />";
						foreach($preds as $key2){
							echo substr($key2,1)." ";
						}
						echo '</div><div class="ui-block-b">';
						echo '<a href="?action=myOpportunityManagement&removeSubscription=true&predicate='.$key.'"  data-role="button" data-theme="r" data-inline="true" style="float: right;">'._("Delete subscription").'</a></div></div></li>';
						$index++;
					}
					echo '</ul>';
				?>
			</div>
</div>