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
<!-- ------------------ -->
<!-- App Main View      -->
<!-- ------------------ -->


<!-- Page view -->
<div data-role="page" id="mainView" >


	<!-- Header bar -->
	<? print_header_bar("logout", "mainViewHelpPopup", false); ?>

	 
	<!-- Page content --> 
	<div data-role="content">
	
		<!-- Notification pop up -->
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error); ?>
			
		<!-- App description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Welcome") ?></h3>
			<p>
				<img src='img/logo.gif' alt='logo' style='float: left; margin:15px;'/>
				<?= _("<b>EURO C.I.N. EEIG</b> (European Economic Interest Grouping), founded in 1994 by the chambers of transborder commerce Cuneo, Imperia and Nice, is now composed of institutions and companies representing the territories of Piedmont, Liguria and Provence-Alpes-Côte d'Azur.<br>In addition to the three founders (Cuneo, Imperia and Nice), the other members are the Chambers of Commerce of Asti, Alessandria, Genoa, Unioncamere Piedmont, Savona Port Authority, Municipality of Cuneo, Cuneo BRE Bank and Spa GEAC.<br>The Group's objectives is the desire to create a comprehensive and common inside and outside of the Euroregion (called Alpes-Maritimes), to promote economic integration, cultural and scientific development and promotion of cross-border flows territorial part of their particularities and traditions.<br>With myEurocin, the Group aims to introduce and encourage visitors most evocative, contemplating nature and well-being, curiosity, historic and artistic and many local products that characterize the Euroregion.<br>MyEurocin content is available to visitors who request their collaboration to improve implementation, providing information and suggestions.<br>Inclusion of new content to the site is possible after authentication.") ?>
			</p>	
		</div>
		
		<!-- App Main menu -->
		<?php if ($_SESSION['user']->is_guest) { ?>
			<!-- User not authenticated -->
			<p Style="text-align: center; font-style:italic;"><?= _("You have to login to access all the menu options") ?></p>
			<a href="index.php?action=extendedProfile" data-icon="signin" data-role="button" data-ajax="false"><?=_("Sign in")?></a><br />
		<?php } ?>
		<?php if (!$_SESSION['user']->is_guest && !isset($_SESSION['myEuroCIN'])) { ?>
			<p Style="text-align: center; font-style:italic;"><?= _("You have to create your extended profile to access other options") ?></p>
  		<?php } ?>
		
		<a href="index.php?action=publish&method=show_user_publications" data-icon="pencil" data-role="button" data-ajax="false"  <?= ($_SESSION['user']->is_guest || !isset($_SESSION['myEuroCIN'])) ? " class='ui-disabled'" : "" ?>><?= _("My publications") ?></a><br />
		<a href="?action=Find&search=true" data-role="button" data-icon="search"><?= _("Search publication") ?></a><br />
		<a href="?action=extendedProfile&method=show_user_profile&user=<?= $_SESSION['user']->id ?>" data-icon="user" rel="external" data-role="button" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?>><?= _("My profile") ?></a><br />

		<? if(isset($_SESSION['myEuroCIN']) && ($_SESSION['myEuroCIN']->permission == '2')): ?>
			<!-- Admin links -->
			<h3>ADMINISTRATION:</h3>
			<a href="?action=Validation&method=show_all_validations" data-role="button" data-theme="e" data-icon="pencil" <?= ($_SESSION['user']->is_guest) ? " class='ui-disabled'" : "" ?>><?= _("Manage validations") ?></a><br />
			<a href="?action=admin" data-role="button" data-icon="pencil" data-theme="e"><?= _("Manage accounts") ?></a><br />
		<? endif;?>
		
		<a href="index.php#aboutView" data-icon="info-sign" data-role="button" data-inline="true" style="position: absolute; right: 10px;"><?=_("Credits")?></a>
		
		<!-- MainView HELP POPUP -->
		<div data-role="popup" id="mainViewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
			<h3><?= _("How it works") ?> ?</h3>
			<ul data-role="listview" data-theme="e">	
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("<<<<< Help topic >>>>>>") ?></strong></p>
						<p><?= _("<<<<< Help description >>>>>>") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("<<<<< Help topic >>>>>>") ?></strong></p>
						<p><?= _("<<<<< Help description >>>>>>>") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("<<<<< Help topic >>>>>>") ?></strong></p>
						<p><?= _("<<<<< Help description >>>>>>") ?></p>
				</li>
			</ul>
			<br />	
			<center><a href="#" data-role="button" data-icon="ok" data-inline="true" data-theme="e" data-rel="back" data-direction="reverse"><?= _("Ok")?></a></center>
		</div>
		
	</div>	
	<!-- End page content -->
	
</div>

<? include_once 'AboutView.php'; ?>
