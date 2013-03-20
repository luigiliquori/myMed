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
<?php

require_once("header.php");

?>

<div id="updateProfile" data-role="page">

	<?php tab_bar_main("?action=profile", 2); ?>
	<?php include 'notifications.php'; ?>
	
	<div data-role="content">
		<form action="?action=profile" id="updateProfileForm" method="post" data-ajax="false">
		
			<input type="hidden" name="id" value="<?= $_SESSION['user']->id ?>" />			
			
			<label for="firstName"><?= _("First name") ?> : </label>
			<input type="text" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>/>
			
			<label for="lastName"><?= _("Last name") ?> : </label>
			<input type="text" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>/>
			
		 <? if (!isset($_SESSION['user']->email) || empty($_SESSION['user']->email)){ ?>
				<label for="email" ><?= _("E-mail")?> : </label>
				<input type="text" id="email" name="email"/>
		 <? }else{ ?>
		 		<input type="hidden" name="email" value="<?= $_SESSION['user']->email ?>" />
		 <? } ?>
			<label for="birthday" ><?= _("Date of birth") ?> (jj/mm/aaaa) : </label>
			<input type="text" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>/>
			
			<label for="profilePicture" ><?= _("Profile picture") ?> (url): </label>
			<input type="text" id="profilePicture" name="profilePicture" value="<?= $_SESSION['user']->profilePicture ?>" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>/>
			

			<label for="lang" ><?= _("Language") ?>	: </label>
			<select id="lang" name="lang" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>>
				<option value="fr" <?= $_SESSION['user']->lang == "fr" ? "selected" : "" ?>><?= _("French")?></option>
				<option value="it" <?= $_SESSION['user']->lang == "it" ? "selected" : "" ?>><?= _("Italian")?></option>
				<option value="en" <?= $_SESSION['user']->lang == "en" ? "selected" : "" ?>><?= _("English")?></option>
			</select>

			<br>
			<? if (!isset($_SESSION['user']->email) || empty($_SESSION['user']->email)): /*oauthed user have no password for the moment*/ ?>
			<div data-role="fieldcontain">
				<label for="passwordConfirm" ><?= _("Password") ?> : </label>
				<input type="password" id="passwordConfirm" name="passwordConfirm" />
			</div>
			<? endif; ?>
			
		 <? if(!isset($_SESSION['userFromExternalAuth'])): ?>
				<div style="text-align: center;">
					<input type="submit" data-role="button" data-inline="true" data-theme="b" value="<?= _("Update") ?>" data-icon="refresh"/>
				</div>
		 <? endif; ?>
			
		</form>
	</div>
		
</div>

<? include_once 'footer.php'; ?>