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
<? 

//
// This view shows both the login and register forms, with two tabs
//
require_once("header.php"); ?>
</head>
<body>

<div data-role="page" id="login">
	
	<div data-role="header" data-theme="b" data-position="fixed">
		<h1 style="color: white;"><?= _("Sign in")?></h1>	
	</div>
	
	<div data-role="content"  class="content">
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error); ?>
		<br>
		<img alt="<?= APPLICATION_NAME ?>" src="img/icon.png" style="height: 50px; margin-top: -15px;" />
		<h1 style="display: inline-block;vertical-align: 20%;"><?= APPLICATION_NAME ?></h1>
		<br>
	
		<form action="?action=login" method="post" data-ajax="false">
			<div data-role="collapsible-set" data-theme="b" data-content-theme="d" data-mini="true" style="margin: 15px;">	
				<div data-role="collapsible" data-collapsed="false">
					<h3><?= _("Connection") ?></h3>
		
					<input type="hidden" name="signin" value="1" />
				    
				    <div style="text-align: left;"><?= _("E-mail")?><b>*</b> :</div>
				    <input type="text" name="login" id="login" data-theme="c"/>
				    
				    <div style="text-align: left;"><?= _("Password")?><b>*</b> :</div>
				    <input type="password" name="password" id="password" data-theme="c"/>
		 		    <p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
		 		    <div data-role="controlgroup" data-type="horizontal">
		 		    	<input type="submit" data-role="button" data-mini="true" data-inline="true" data-theme="b" data-icon="signin" value="<?= _("Connect") ?>" />
						<a href="#signinPopup" data-role="button" data-rel="popup" data-inline="true" data-mini="true" data-icon="star"><?= _("Connect with") ?></a>			
						<a href="?action=register&method=showRegisterView" data-role="button" data-inline="true" data-mini="true" data-icon="pencil" data-iconpos="right"><?= _("Register") ?></a>
					</div>	
				</div>
			</div>
		</form>
		
		<!-- <form action="?action=login" method="post" data-ajax="false">
				<input type="hidden" name="signin" value="1" />
			    <input type="hidden" name="login" id="login" value="guest_user@yopmail.com"/>
			    <input type="hidden" name="password" id="password" value="1"/>
	 		    <input type="submit" data-role="button" data-inline="true" data-mini="true" data-theme="g" data-icon="signin" value="<?= _("Guest")?>" />
			</form>
		 -->
			
		
		</br>

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
			<!-- 
			<li>
				<a onclick="$('#openIdForm').submit();" title="OpenID">
				<img class="ui-li-mymed" src="/system/img/social/openID_32.png" />
				<form onclick='event.stopPropagation();/* for clicking above and below thetext input without submitting*/' style='padding:8px 0; margin: -15px 0;' id='openIdForm' action='/lib/socialNetworkAPIs/php-openid/examples/consumer-simple/oid_try.php' data-ajax='false'>
					<input id="openIdProvider" type="text"  name="openid_identifier" value="https://www.google.com/accounts/o8/id" placeholder="" />
				</form>
				</a>
			</li>
			 -->
			</ul>
		</div>

		<br /><br />
		
			<img alt="Alcotra" src="../../system/img/logos/alcotra.png" />
			<br />
			<i>"Ensemble par-delà les frontières"</i>
			<br /><br />
			<img alt="Alcotra" src="../../system/img/logos/europe.jpg" />
		
		
	</div>
	
	<!--			
	<div data-role="footer" data-position="fixed" data-theme="d">
		<div data-role="navbar" data-iconpos="left" >
			<ul>
				<li><a href="#login" data-transition="none" data-back="true" data-icon="home" class="ui-btn-active ui-state-persist">Connexion</a></li>
				<li><a href="#register" data-transition="none" data-back="true" data-icon="grid">Inscription</a></li>
				<li><a href="#about" data-transition="none" data-icon="info">A propos</a></li>
			</ul>
		</div>
	</div>
	-->
	</div>
</body>
