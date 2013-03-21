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
			_("Bénévoles") => null)) ?>-->
			
	<? tab_bar_main("?action=main"); ?>
	<?php  include('notifications.php');?>
	
	<div data-role="content">
	
		<? if ($this->extendedProfile instanceof ProfileNiceBenevolat) : ?>
			<a data-inline="true"data-role="button" data-icon="add" data-theme="g"	data-ajax="false"
				href="<?= url("extendedProfile:create", array("type" => BENEVOLE)) ?>" >
				<?= _("Ajouter un bénévole") ?>
			</a>
		<? endif?>
	
		<div data-role="header" data-theme="e" >
			<h3>
				<?= _("Liste des bénévoles") ?>
			</h3>
		</div>
		
		<? if (sizeof($this->benevoles) == 0) : ?>
			<p>
				<?= _("Aucun bénévole à afficher avec ces critères") ?>
			</p>
			<a  data-role="button" 
				data-inline="true"
				href="<?= url("listBenevoles") ?>" >
				Afficher tous les bénévoles
			</a>
		<? else : ?>
			<ul data-role="listview" data-theme="d" data-inset="true">
				<? foreach ($this->benevoles as $benevole) : ?>
				<li>
					<a 	data-ajax="false"
						href="<?= url("extendedProfile:show", array("id" => $benevole->userID)) ?>" >
						
						<?= empty($benevole->name) ? $benevole->userID : $benevole->name ?>
							
					</a>
				</li>
				<? endforeach ?>
			</ul>
		<? endif ?>
		
	</div>
	
</div>
	
<? include("footer.php"); ?>
