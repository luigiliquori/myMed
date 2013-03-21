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
<!-- App Login View     -->
<!-- ------------------ -->

<div data-role="page" id="loginView" >

	<!-- Header bar -->
	<? $title = _("Connection");
       print_header_bar("?action=main", false, $title); ?>
	
	<!-- Page content -->
	<div data-role="content" class="content">
		
		<!-- Notification pop up -->
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error);?>
	
 		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Why Register ?") ?></h3>
			<p><?= _("By registering to myEdu you will be able to manage your subscriptions and your adhesions.") ?></p>
			<p><?= _("Don't forget that you have the possibility to use your social network accounts to create your profile: see button")?> "<?= _("Connect with") ?>"</p>
		</div>
		
		<form action="?action=login" method="post" data-ajax="false">
			
			<!-- Sign in with an account -->
			<div data-role="collapsible-set" data-theme="b" data-content-theme="d" data-mini="true">	
				<div data-role="collapsible" data-collapsed="false">
					<h3><?= _("Connection") ?></h3>
					<input type="hidden" name="signin" value="1" />
					<div style="text-align: left;"><?= _("E-mail")?><b>*</b> :</div>
					<input type="text" name="login" id="login" data-theme="c"/>
				    <div style="text-align: left;"><?= _("Password")?><b>*</b> :</div>
				    <input type="password" name="password" id="password" data-inline="true"  data-theme="c"/>  
				    <p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
				    <div data-role="controlgroup" data-type="horizontal">
				 	    <input type="submit" data-role="button" data-mini="true" data-inline="true" data-theme="b" data-icon="signin" value="<?= _("Connect") ?>" />
						<a href="#signinPopup" data-role="button" data-rel="popup" data-inline="true" data-mini="true" data-icon="star"><?= _("Connect with") ?></a>
						<a href="?action=register&method=showRegisterView" data-role="button" data-inline="true" data-mini="true" data-icon="pencil" data-iconpos="right"><?= _("Register") ?></a>	
					</div>
			    </div>
			</div>
			
			<!-- Sign in with social network pop up -->
			<div data-role="popup" id="signinPopup" class="ui-content" data-overlay-theme="e" data-theme="d">
				<ul data-role="listview">
					<li>
						<a href='/lib/socialNetworkAPIs/google/examples/simple/oauth_try.php' title='Google OAuth' rel='external'>
						<img class="ui-li-mymed" src="/system/img/social/google_32.png" />
						Google</a>
					</li>
					<li>
						<a href='/lib/socialNetworkAPIs/facebook/examples/oauth_try.php' title='Facebook OAuth' rel='external'>
							<img class="ui-li-mymed" src="/system/img/social/facebook_32.png" />
						Facebook</a>
					</li>
					<li>
						<a href='/lib/socialNetworkAPIs/twitter/redirect.php' title='Twitter OAuth' rel='external'>
						<img class="ui-li-mymed" src="/system/img/social/twitter_32.png" />
						Twitter</a>
					</li>
				</ul>
			</div>
	
		</form>

	</div> <!-- END page content -->
	
</div> <!-- END page -->
