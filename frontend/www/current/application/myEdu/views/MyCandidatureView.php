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

<div data-role="page" id ="mycandidatureesview">

	<!-- Page header bar -->
	<? $title = _("My offers adhesions");
	   print_header_bar("?action=main", "helpPopup", $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("My offers adhesions") ?></h3>
			<p><?= _("Check your adhesions and see whether you have been accepted.")?></p>
		</div>
		<br />
	
		<!-- List of student courses -->
		<ul data-role="listview" >
		
			<li data-role="list-divider"><?= _("Results") ?></li>
			
			<? if (count($this->result) == 0) :?>
				<li>
					<h4><?= _("No result found")?></h4>
				</li>
			<? endif ?>

			<? foreach($this->result as $item) : ?>
				<li>
					<a data-ajax="false" href="?action=details&predicate=<?= $item->pred3 ?>&author=<?= $item->author ?>">			
						<div class="ui-grid-b">
							<div class="ui-block-a">
								<?= _("Publication name") ?>: <b><?= $item->title ?></b>
							</div>
							<div class="ui-block-b">
								<?= _("Author email") ?>: <b><?= substr($item->author,6) ?></b>
								
							</div>
							<div class="ui-block-c">
								<div style="float: right;">
									<?= _("Status") ?>: <b><?= _($item->accepted) ?></b>
								</div>
							</div>
						</div>
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
		<!-- HELP POPUP -->
		<!-- ----------------- -->
		<div data-role="popup" id="helpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<p><?= _("Check your adhesions and see whether you have been accepted.")?></p>
		</div>	
	</div>
	<!-- END Page content -->
	
</div>
<!-- END Page MyPublicationView-->