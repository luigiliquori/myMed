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
 
 <? $title = _("Projects list");
	 print_header_bar("?action=extendedProfile", false, $title); ?>
	
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
	
		<ul data-role="listview" >
		
			<li data-role="list-divider"><?= _("Results") ?></li>
			
			<? if (count($this->result) == 0) :?>
			<li>
				<h4><?= _("No result found")?></h4>
			</li>
			<? endif ?>
			
			<? foreach($this->result as $item) : ?>
				<li>
					<!-- Print Publisher reputation -->
					<a href="?action=modify&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3><?= _("Title")?> : <?= $item->title ?></h3>
						
						<p style="position: relative; margin-left: 30px;">
							<b><?= _("Themes") ?></b>: <?= $item->theme ?><br/>
							<b><?= _("Programme") ?></b>: <?= $item->other ?><br/><br/>
							<b><?= _('Date of expiration') ?></b>: <?= $item->end ?><br/>
						</p>
						
						<br/>
						
						<p>
							Publisher ID: <?= $item->publisherID ?><br/>
						</p>
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
		
			
	</div>
	
</div>