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
<!-- App Main View      -->
<!-- ------------------ -->

<? include("header.php"); ?>

<!-- Page view -->
<div data-role="page" id="mainView" >


	<!-- Header bar -->
	<? include "header-bar.php" ?>
	<? print_header_bar("logout", "mainViewHelpPopup", false); ?>

	<!-- Page content --> 
	<div data-role="content" >
	
		<!-- Notification pop up -->
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error); ?>
			
		<!-- App description -->
		<br/>
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Welcome") ?></h3>
			<p><?= _("Main capsule text") ?></p>	
		</div>
		<br/>
		
		<!-- App Main menu -->
		 <? if ($_SESSION['user']->is_guest) { ?>
			<!-- User not authenticated - Sign in -->
			<p Style="text-align: center; font-style:italic;"><?= _("You have to login to access all the menu options") ?></p>
			<a href="index.php?action=login" data-icon="signin" data-role="button" data-ajax="false"><?=_("Connect")?></a><br />
	 	<? } ?>
  
	 	<a href="?action=main#Map" data-role="button" data-transition="none" data-back="true" data-icon="home" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?> ><?=_("Map")?></a>
	 	<br/>
		<a href="?action=main#search" data-role="button" data-transition="none" data-icon="search" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?> ><?=_("Search")?></a>
		<br/>
		<a href="?action=option" data-role="button" data-transition="none" data-icon="gear" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?> ><?=_("Profile")?></a>
		<br/>
		<!-- About dialog -->
		<a href="?action=main#aboutView" data-icon="info-sign" data-role="button" data-inline="true" style="position: absolute; right: 10px;"><?=_("Credits")?></a>
		
		
		<!-- MainView HELP POPUP -->
		<div data-role="popup" id="mainViewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<h3><?= _("How it works") ?> ?</h3>
			<ul data-role="listview" data-theme="e">	
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("topic") ?></strong></p>
						<p><?= _("description") ?></p>
				</li>
			</ul>
		</div>
	</div>	
	<!-- End page content -->
	
</div>

<?  include("MapView.php"); ?>
<?  include("SearchView.php"); ?>
<? 
/* roadMap  */ include("DetailsView.php"); 
?>
<? include("AboutView.php"); ?>
