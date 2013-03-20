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
<? include("notifications.php")?>

<div data-role="page" id="NeedHelp">

<script type="text/javascript">
	<?php
	if ($_SESSION['isMobile']){
		
		echo 'setTimeout(function() {location.href="/application/'.APPLICATION_NAME.'/index.php?action=main&mobile_binary::call::'.$_SESSION['emergency_next_phonecall'].'";},5000);';
	}	
	else
		echo 'setTimeout(function() {sendEmailsAlerts();},2000);';
	?>

</script>

	<!-- Header -->
	<div data-role="header" data-position="inline">
	 	<h1><?= _("NeedHelp"); ?></h1>
		<a href="?action=main"  data-role="button" class="ui-btn-left" data-icon="back"><?= _("Back"); ?></a>
	 	<a href="#needHelpViewHelpPopup" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info" data-rel="popup"><?= _("Help"); ?></a>
	</div>	
	
	
	<div data-role="content" data-theme="a">
	
		<div class="description-box">
			<?= _("MyMemory_NeedHelpDesc") ?>
		</div>
	
		<a href="?action=StopEmergency" data-rel="dialog" data-transition="pop" data-role="button" class="mymed-big-button" data-inline="false" data-theme="r" data-icon="delete"><?= _('StopNeedHelp'); ?></a>
		
		
		
		<input type="hidden" id="username" value="<?=$_SESSION['user']->name; ?>" />
		<input type="hidden" id="howmany" value="<?
		if ($_SESSION['isMobile'])
			echo sizeof($_SESSION['ExtendedProfile']->callingList);
		else
			echo sizeof($_SESSION['ExtendedProfile']->callingList) -1; ?>" />

		<div>
		<?php
		/*
		 * In case of mobile device.
		 */
		if ($_SESSION['isMobile']) {?>
			<p><?= _("MyMemory_Calling"); ?></p>
			<ul data-role="listview" data-inset="true" data-theme="c">
				<?php 
				$i = 1;
				foreach($_SESSION['ExtendedProfile']->callingList as $entry) {
					echo '<li id="line'.$i.'">';
					echo '<a id="call'.$i.'"href="index.php?action=callEnded&mobile_binary::call" >';
					echo '<h3>'.$i.' - '.$entry->nickname .'</h3>';
					echo '<p id="num'.$i.'">'.$entry->phone.'</p>';
					echo '</a>';
					echo '</li>'; 
					$i++;
				}?>
			</ul>
			<p><?= _("MyMemory_Microphone"); ?></p>
		<?php }
		/*
		 * In case of desktop
		 */
		else {?>
		
			<p><?= _("MyMemory_Mailling"); ?></p>
			<ul data-role="listview" data-inset="true" data-theme="c">
			<?php
			$i = 1;
			foreach($_SESSION['ExtendedProfile']->callingList as $entry) {
				if ($entry->nickname == "Emergency") continue;
				echo '<li id="line'.$i.'">';
				echo '<h3>'.$i.' - '.$entry->nickname .'</h3>';
				echo '<p id="mail'.$i.'">'.$entry->email.'</p>';
				echo '</li>';
				$i++;
			}?>
			</ul>
			<p><?= _("MyMemory_MailSent"); ?></p>		
		<?php 	
		}
		?>
		</div>
		
		
		<!-- ------------------ -->
		<!-- HELP POPUP -->
		<!-- ------------------ -->
		
		<div data-role="popup" id="needHelpViewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px; margin-top:50px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<h3><?= _("How it works") ?></h3>
			<?= _("MyMemory_NeedHelpHelp"); ?>
		</div>
		
	</div>
	
</div>