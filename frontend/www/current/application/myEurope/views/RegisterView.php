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
<? require_once("header.php"); ?>
<? include("notifications.php"); ?>

<div data-role="page" id="register">
	<? $title = _("Register");
	print_header_bar(true, false, $title); ?>
	
<!--<div data-role="header" data-theme="b">
		<h1><?= _("Account creation") ?></h1>
	</div>
 -->
	<div data-role="content">
	
		<? print_notification($this->success.$this->error); ?>
	
		<!--  Register form -->
		<form action="?action=register" method="post" data-ajax="false">
		
				<label for="prenom"><?= _("First name") ?> : </label>
				<input type="text" name="prenom" value="" />
				<br />
				
				<label for="nom"><?= _("Last name") ?> : </label>
				<input type="text" name="nom" value="" />
				<br />
				
				<label for="email" ><?= _("Email")?><b>*</b> : </label>
				<input type="text" name="email" value="" />
				<br />
				
				<label for="password" ><?= _("Password") ?><b>*</b> : </label>
				<input type="password" name="password" />
				<br />
				
				<label for="password" ><?= _("Password Confirmation") ?><b>*</b> : </label>
				<input type="password" name="confirm" />
				<br />
				
				<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 5px; width:17px;height:17px;"/>
				<span style="position: relative; left: 50px;">
					<a href="application/myMed/doc/CGU_fr.pdf" rel="external"><?= _('I accept the general terms and conditions'); ?></a>
				</span>
				<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
				<div style="text-align: center;">
					<input type="submit" data-role="button" data-theme="g" data-inline="true" data-icon="ok-sign" value="<?= _('Send') ?>" />
				</div>
		
		</form>
	</div>
	
</div>

<? require_once("LoginView.php"); ?>

<? require_once("footer.php"); ?>
