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

<div data-role="page" id="GoingBack">
	
	
	<!-- Header -->
	<div data-role="header" data-position="inline">
		<a href="?action=main"  data-role="button" class="ui-btn-left" data-icon="back"><?= _("Back"); ?></a>
	 	<h1><?= _("GoingBack"); ?></h1>
	 	<a href="#goingBackViewHelpPopup" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info" data-rel="popup"><?= _("Help"); ?></a>
	</div>



	<div data-role="content" data-theme="a">
	
		<div class="description-box">
		<?= _("MyMemory_GoingBackDesc") ?>
		</div>
	
	
	
		<ul data-role="listview" class="ui-listview" data-theme="b" data-inset="true" >
			<li data-icon="home" class="ui-btn ui-btn-icon-right ul-li-has-arrow ui-li" style="padding-bottom:1em;">
				<a href="?action=itineraire&amp;address=<?= $_SESSION['ExtendedProfile']->home?>" class="ui-link-inherit" onclick="goingBack(document.getElementById('address_home').innerHTML)">
				<h3 class="ui-li-heading"><?= _("Domicile"); ?></h3>
				<p class="ui-li-desc" id="address_home" ><?= $_SESSION['ExtendedProfile']->home?></p>
				</a>
			</li>
			<?php
			$i = 0;
			foreach($_SESSION['ExtendedProfile']->callingList as $data) {
				if($data->type == "emergency" || $data->nickname == "Emergency") continue;
				?>
				<li class="ui-btn ui-btn-icon-right ul-li-has-arrow ui-li" style="padding-bottom:1em;">
					<a href="?action=itineraire&amp;address=<?= $data->address; ?>" class="ui-link-inherit" data-ajax="false">
					<h3 class="ui-li-heading"><?= $data->nickname; ?></h3>
					<?= '<p class="ui-li-desc" id="address'.$i.'" >'. $data->address . '</p>'; ?>
					</a>
				</li>
				
			<?php
			$i++; 	
			}
			?>
			
		</ul>
		
		
		
		<!-- ------------------ -->
		<!-- HELP POPUP -->
		<!-- ------------------ -->
		
		<div data-role="popup" id="goingBackViewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px; margin-top:50px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<h3><?= _("How it works") ?></h3>
			<?= _("MyMemory_GoingBackHelp"); ?>
		</div>	
		
	</div>
	
	

	<input type='hidden' id='userID' value='<?= $_SESSION['user']->id ?>' />
	<input type='hidden' id='applicationName' value='myMemory' />
	<input type='hidden' id='accessToken' value='<?= $_SESSION['accessToken'] ?>' />

	


</div>