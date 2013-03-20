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
<? 

//
// This view shows both the login and register forms, with two tabs
//
require_once("header.php"); ?>

<div data-role="page" id="login">

	<div data-role="header" data-theme="b">
		<div Style="text-align: center; position: relative; top: 15px;"> Réseau Social Transfrontalier </div>
		<span class="ui-title"></span>
		<? include("notifications.php"); ?>
	</div>
	
	<div data-role="content"  class="content">
		<? print_notification($this->success.$this->error); ?>
		
		<h1><?= APPLICATION_NAME ?></h1>
	
		<form action="?action=login" method="post" data-ajax="false">
		
			<!-- Sign in with an account -->
			<div data-role="collapsible-set" data-theme="b" data-content-theme="d" data-mini="true">	
				<div data-role="collapsible" data-collapsed="false">
					<h3><?= _("Connection") ?></h3>
					<input type="hidden" name="signin" value="1" />
					<div style="text-align: left;"><?= _("E-mail")?><b>*</b> :</div>
				    <input type="text" name="login" id="login" data-theme="c"/>
				    
				    <div style="text-align: left;"><?= _("Password")?><b>*</b> :</div>
				    <input type="password" name="password" id="password" data-theme="c"/>
				    <p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
		 		    <input type="submit" data-role="button" data-mini="true" data-inline="true" data-theme="b" data-icon="signin" value="<?= _("Connect") ?>" />
		 		</div>
			</div>
		</form>
		<br /><br />
		<img alt="Alcotra" src="<?= MYMED_URL_ROOT ?>/system/img/logos/alcotra.png" />
		<br />
		<i>"Ensemble par-delà les frontières"</i>
		<br /><br />
		<img alt="Alcotra" src="<?= MYMED_URL_ROOT ?>/system/img/logos/europe.jpg" />
		
	</div>
	
				
	<div data-role="footer" data-position="fixed" data-theme="d">
		<div data-role="navbar">
			<ul>
				<li><a href="#login" data-transition="none" data-back="true" data-icon="home" class="ui-btn-active ui-state-persist">Connexion</a></li>
				<li><a href="#register" data-transition="none" data-back="true" data-icon="grid">Inscription</a></li>
				<li><a href="#about" data-transition="none" data-icon="info">A propos</a></li>
			</ul>
		</div>
	</div>
	
</div>
	
<? require_once("RegisterView.php"); ?>

<? require_once("AboutView.php"); ?>

<? require_once("footer.php"); ?>