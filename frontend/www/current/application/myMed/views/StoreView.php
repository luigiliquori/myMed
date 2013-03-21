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
<?php

require_once("header.php");
define('STORE_PREFIX' , 'store_');

?>

<div data-role="page" id="store" data-dom-cache="true">
	<? if ($_SESSION['user']->is_guest): ?>
		<? tab_bar_main("?action=store", 6); ?>
	<? else: ?>
		<? tab_bar_main("?action=store"); ?>
	<? endif; ?>
	<? include("notifications.php"); ?>

	<div data-role="content" Style="padding: 0px; opacity: 0.9">
		<div class="ui-grid-a" style="position: relative;">
			<?php $column = "a"; ?>
			<?php foreach ($_SESSION['applicationList'] as $applicationName => $status) {  ?>
					<div class="ui-block-<?= $column ?>">
						<div class="ui-bar-c" style="height:120px; /*text-align: justify; overflow: hidden;*/ ">
							<a href="?action=store&applicationStore=<?= $applicationName ?>" rel="external" class="myIcon" style="text-decoration: none;">
								
								<div class="ui-grid-b" style="height:120px;">
								
									<div class="ui-block-a" style="height:120px;">
										<img alt="<?= $applicationName ?>" src="../../application/<?= $applicationName ?>/img/icon.png" style="height:50px;position: relative; left:15px; top:25px;"/>
							    		<div Style="position: relative; left:7px; top:20px;">
									    	<?php for($i=1 ; $i <= 5 ; $i++) { ?>
									    		<?php if($i*20-20 < $_SESSION['reputation'][STORE_PREFIX . $applicationName] ) { ?>
									    			<img alt="rep" src="<?= APP_ROOT ?>/img/yellowStar.png" width="10" Style="left: <?= $i ?>0px;" />
									    		<?php } else { ?>
									    			<img alt="rep" src="<?= APP_ROOT ?>/img/grayStar.png" width="10" Style="left: <?= $i ?>0px;"/>
									    		<?php } ?>
									    	<? } ?>
								    	</div>
							    	</div>
							    	<div class="ui-block-b" style="height:120px;">
										<div style="display:table-cell;vertical-align:middle;height:120px;width: 35%">
											<center><h2><?= $applicationName ?></h2></center>
										</div>
									</div>
									<div class="ui-block-c" style="height:120px;">
										<div style="display:table-cell;vertical-align:bottom;height:120px;width: 279px;text-align:right;">
							    	 <? if($_SESSION['applicationList'][$applicationName]=='on'): ?>
											
											<p style="color: green; font-size: 9pt;" >
												<?= _("Installed")?>
											</p>	
											<?else:?>
											<p style="color: gray; font-size: 9pt;" >
												<?= _("Not installed")?>
											</p>
										<?endif?>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>
					<?php 
			    	if($column == "a") {
			    		$column = "b";
			    	} else if($column == "b") {
			    		$column = "a";
			    	}
			} ?>
		</div>
			
	</div>

</div>

<? include_once 'footer.php'; ?>
