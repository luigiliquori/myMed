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
<? include("notifications.php")?>
<div data-role="page" id="ExtendedProfileView">
	<!-- Header -->
	<div data-role="header" data-position="inline">
	 	<h1><?= _("Profile"); ?></h1>
		<a href="?action=main"  data-role="button" class="ui-btn-left" data-icon="back"><?= _("Back"); ?></a>
		<a href="#displayProfileHelpPopup" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info" data-rel="popup"><?= _("Help"); ?></a>
	</div>
	
	<div data-role="content" data-theme="a">
		
		<div class="description-box">
		<?= _("MyMemory_ViewProfileDesc"); ?>
		</div>
		
		<br />
		
		<div data-role="collapsible" data-collapsed="false" data-content-theme="d">
				<h3><?= _("MyInfos");?></h3>
				<h2><?=$_SESSION['user']->name?></h2>
				<p><?=$_SESSION['user']->login?></p>
				<p><?=$_SESSION['ExtendedProfile']->home?></p>
				<p><?= _("DiseaseLevel"); ?> : <strong><?
				/*This is ugly, but needed for the gettext.*/
				switch($_SESSION['ExtendedProfile']->diseaseLevel){
					case 1 :
						echo _('DiseaseLevel1');
						break;
					case 2 :
						echo _('DiseaseLevel2');
						break;
					case 3 :
						echo _('DiseaseLevel3');
						break;
				}?></strong></p>
		</div>	

		<div data-role="collapsible" data-collapsed="false"  data-content-theme="d">
				<h3><?= _("MyCaregiver"); ?></h3>
				<h2><?= $_SESSION['ExtendedProfile']->careGiver->nickname?></h2>
				<p><?= _("Phone") ?> : <a href="tel:<?= $_SESSION['ExtendedProfile']->careGiver->phone?>"><?= $_SESSION['ExtendedProfile']->careGiver->phone?></a></p>
				<p><?= _("Address") ?> : <?= $_SESSION['ExtendedProfile']->careGiver->address?></p>
				<p><?= _("Email") ?> : <a href="mailto:<?= $_SESSION['ExtendedProfile']->careGiver->email?>"><?= $_SESSION['ExtendedProfile']->careGiver->email?></a></p>
		</div>
		<div data-role="collapsible" data-content-theme="d">
				<h3><?= _("MyDoctor"); ?></h3>
				<h2><?= $_SESSION['ExtendedProfile']->doctor->nickname?></h2>
				<p><?= _("Phone") ?> : <a href="tel:<?= $_SESSION['ExtendedProfile']->doctor->phone?>"><?= $_SESSION['ExtendedProfile']->doctor->phone?></a></p>
				<p><?= _("Address") ?> : <?= $_SESSION['ExtendedProfile']->doctor->address?></p>
				<p><?= _("Email") ?> : <a href="mailto:<?= $_SESSION['ExtendedProfile']->doctor->email?>"><?= $_SESSION['ExtendedProfile']->doctor->email?></a></p>
		</div>
			
		<div data-role="collapsible"  data-content-theme="d">
			<h3><?= _("CallingList");?></h3>
			
			<?php foreach($_SESSION['ExtendedProfile']->callingList as $callingSlot=>$data) {?>
			
			<div data-role="collapsible" data-content-theme="d">
				<h3><?
				/* This is ugly but needed for the gettext*/
				switch($callingSlot){
					case 0:
						echo _('callingslot0');
						break;
					case 1:
						echo _('callingslot1');
						break;
					case 2:
						echo _('callingslot2');
						break;
					case 3:
						echo _('callingslot3');
						break;
				}
				?></h3>
				<h2><?= $data->nickname?></h2>
				<p><?= _("Phone") ?> : <a href="tel:<?= $data->phone?>"><?= $data->phone?></a></p>
				<?php
				if (!empty($data->address)) {
					echo "<p>" . _("Address") . " : " . $data->address . "</p>";
				}
				?>
				<p><?= _("Email") ?> : <a href="mail:<?= $data->email?>"><?= $data->email?></a></p>
			</div>
			<?php }?>
		</div>
		
		<?php if ($_SESSION['ExtendedProfile']->diseaseLevel == '3') {?>
		
		<div data-role="collapsible"  data-content-theme="d">
			<h3><?= _("AutoCall");?></h3>
			
				<div data-role="collapsible"  data-content-theme="d">
					<h3><?= _("MyMemory_PerimeterHome"); ?></h3>
					<p><?= _("MyMemory_CallThisNumber"); ?> : <strong><?= $_SESSION['ExtendedProfile']->callingListAutoCall['0']; ?></strong> <?= _("MyMemory_IfOutsidePerimeter"); ?></label>
							<?= $_SESSION['ExtendedProfile']->perimeter_home; ?> m.</p>
				</div>
				<div data-role="collapsible"  data-content-theme="d">
					<h3><?= _("MyMemory_PerimeterNearby"); ?></h3>
					<p><?= _("MyMemory_CallThisNumber"); ?> : <strong><?= $_SESSION['ExtendedProfile']->callingListAutoCall['1']; ?></strong> <?= _("MyMemory_IfOutsidePerimeter"); ?></label>
							<?= $_SESSION['ExtendedProfile']->perimeter_nearby; ?> m.</p>
				</div>
				<div data-role="collapsible"  data-content-theme="d">
					<h3><?= _("MyMemory_PerimeterFar"); ?></h3>
					<p><?= _("MyMemory_CallThisNumber"); ?> : <strong><?= $_SESSION['ExtendedProfile']->callingListAutoCall['2']; ?></strong> <?= _("MyMemory_IfOutsidePerimeter"); ?></label>
							<?= $_SESSION['ExtendedProfile']->perimeter_far; ?> m.</p>
				</div>
				<br />
				<p><?= _("MyMemory_AutoCallFrequency"); ?> : <?= $_SESSION['ExtendedProfile']->autocall_frequency; ?> min.</p>
		</div>
		<?php }?>
		
		<a href="?action=ExtendedProfile&edit=false" data-role="button" data-rel="dialog" data-transition="pop" data-theme="b" data-icon="gear"><?= _("EditProfile"); ?></a>
	</div>
	
	<!-- ------------------ -->
	<!-- HELP POPUP -->
	<!-- ------------------ -->
	
	<div data-role="popup" id="displayProfileHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px; margin-top:50px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("How it works") ?></h3>
		<?= _("MyMemory_DisplayProfileHelp"); ?>
	</div>

	
</div>
