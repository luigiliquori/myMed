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
<!-- --------------------------------- -->
<!-- MyCandidatureView                 -->
<!-- Show the status of user's applies -->
<!-- --------------------------------- -->


<!-- page view View -->
<div data-role="page" id ="mycandidatureesview">


	<!-- Page header bar -->
	<? $title = _("My candidatures");
	   print_header_bar("?action=main", false, $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("<<<<< Little help >>>>>") ?> ?</h3>
			<p><?= _("Here is the space summarizing all of your candidatures.")?></p>
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
								<?= _("Author email") ?>: <b><?= $item->author ?></b>
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
			
	</div>
	<!-- END Page content -->
	
</div>
<!-- END Page MyPublicationView-->