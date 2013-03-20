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
<? include("header.php"); ?>

<div data-role="page" id="profiles">

	
	<? tabs_simple('Profiles list'); ?>
	<? include("notifications.php"); ?>

	<div data-role="content">
		<br>
		<? if ($_SESSION['myEurope']->permission <= 0): ?>
			<div data-role="header" data-theme="e">
			
			<h1 style="white-space: normal;"><?= _("Hello, This is your first time on myEurope! Please register with an already existing profile or create yours") ?></h1>
			</div>
			<br />			
		<? endif; ?>
		<a href="?action=ExtendedProfile&new" type="button" data-inline="true" data-mini="true" data-theme="e" data-icon="pencil" style="float: right;"><?= _("Create") ?></a>
		<div style="margin-top: 30px;"></div>
		<ul data-role="listview" data-filter="true" data-inset="true" data-mini="true" data-filter-placeholder="<?= _("filter") ?>">
		<? foreach ($this->cats as $k=>$v) :?>
			<? if (!empty($v)) :?>
				<li data-role="list-divider"><?= Categories::$roles[$k] ?></li>
				<? foreach ($v as $ki=>$vi) :?>
					<li><a href="?action=ExtendedProfile&id=<?= $vi->id ?>&link"><?= $vi->name ?></a></li>
				<? endforeach ?>
			<? endif ?>
		<? endforeach ?>
		</ul>
	</div>
	
</div>


<? include("footer.php"); ?>

