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
<? include("header.php"); ?>

<div data-role="page" id="login">

	<!--<? header_bar(array(
			_("Associations") => null)) ?>-->
			
	<? tab_bar_main("?action=main"); ?>
	<?php  include('notifications.php');?>
	
	<div data-role="content">
	
		<? if ($this->extendedProfile instanceof ProfileNiceBenevolat) : ?>
			<a data-inline="true"data-role="button" data-icon="add" data-theme="g"	data-ajax="false"
				href="<?= url("extendedProfile:create", array("type" => ASSOCIATION)) ?>" >
				<?= _("Ajouter une association") ?>
			</a>
		<? endif?>
	
		<div data-role="header" data-theme="e" >
			<div style="display:inline-block">
				<div style="display:inline-block">
					<h3 style="margin-left:1em"><?= _("Liste des associations") ?></h3>
				</div>
				<div style="display:inline-block">
					<? filters(
						"listAssociations", $this->filter,
						array(
							ASS_ALL => _("toutes"),
							ASS_INVALID => _("à valider"))) ?>
				</div>
			</div>
		</div>
		
		<? if (sizeof($this->associations) == 0) : ?>
			<p>
				<?= _("Aucune association à afficher avec ces critères") ?>
			</p>
			<a  data-role="button" 
				data-inline="true"
				href="<?= url("listAssociations") ?>" >
				<?= _("Afficher toutes les associations") ?>
			</a>
		<? else : ?>
			<ul data-role="listview" data-theme="d" data-inset="true">
				<? foreach ($this->associations as $association) : ?>
				<li>
					<a 	data-ajax="false"
						href="<?= url("extendedProfile:show", array("id" => $association->userID)) ?>">
							<?= $association->name ?>
							<? if (!is_true($association->valid)): ?>
								<span class="mm-tag mm-warn" ><?= _("à valider") ?></span>
							<? endif ?>
					</a>
				</li>
				<? endforeach ?>
			</ul>
		<? endif ?>
		
	</div>
	
</div>
	
<?php include("footer.php"); ?>
