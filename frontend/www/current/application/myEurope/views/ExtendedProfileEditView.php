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
<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page">
	<? $title = _("Edit Profile");
	 print_header_bar("?action=extendedProfile", "defaultHelpPopup", $title); ?>
	
	
	<div data-role="content">
	<? print_notification($this->success.$this->error); ?>
	
		<form action="?action=ExtendedProfile&method=update" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false">
			<input type="hidden" name="form" value="edit" />
			<input type="hidden" name="id" value="<?= $_SESSION['myEurope']->profile ?>" />
			
			<div data-role="fieldcontain">
				<label for="firstName" style="text-align:right"><?= _("First Name") ?> : </label>
				<input type="text" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>/>
			</div>
			<div data-role="fieldcontain">
				<label for="lastName" style="text-align:right"><?= _("Last Name") ?> : </label>
				<input type="text" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>/>
			</div>
			<div data-role="fieldcontain">
				<label for="birthday" style="text-align:right"><?= _("Birthday") ?> (jj/mm/aaaa) : </label>
				<input type="text" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>/>
			</div>
			<div data-role="fieldcontain">
				<label for="profilePicture" style="text-align:right"><?= _("Profile picture") ?> (url): </label>
				<input type="text" id="profilePicture" name="profilePicture" value="<?= $_SESSION['user']->profilePicture ?>" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>/>
			</div>

			<div data-role="fieldcontain">
				<label for="lang" style="text-align:right"><?= _("Language") ?>	: </label>
				<select id="lang" name="lang" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>>
					<option value="fr" <?= $_SESSION['user']->lang == "fr" ? "selected" : "" ?>><?= _("French")?></option>
					<option value="it" <?= $_SESSION['user']->lang == "it" ? "selected" : "" ?>><?= _("Italian")?></option>
					<option value="en" <?= $_SESSION['user']->lang == "en" ? "selected" : "" ?>><?= _("English")?></option>
				</select>
			</div>
			
			
			<div data-role="fieldcontain">
				<label for="textinputu1" style="text-align:right"><?= _('Organization Name') ?><b>*</b>: </label>
				<input id="textinputu1" name="name" placeholder="" value="<?= $_SESSION['myEurope']->details['name'] ?>" type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="role" class="select" style="text-align:right"><?= _("Your category") ?>:</label>
				<select name="role" id="role">
				<? foreach (Categories::$roles as $k=>$v) :?>
					<option value="<?= $k ?>" <?= $_SESSION['myEurope']->details['role']==$k?'selected="selected"':'' ?>><?= $v ?></option>
				<? endforeach ?>
				</select>
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu2" style="text-align:right"><?= _("Action area")?> : </label>
				<input id="textinputu2" name="activity" placeholder="" value="<?= $_SESSION['myEurope']->details['activity'] ?>" type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu4" style="text-align:right"><?= _('Address') ?>: </label>
				<input id="textinputu4" name="address" placeholder="" value="<?= $_SESSION['myEurope']->details['address'] ?>" type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="area" class="select" style="text-align:right"><?= _("Action territory") ?>:</label>
				<select name="area" id="area">
					<option value="local" <?= $_SESSION['myEurope']->details['area']=="local"?'selected="selected"':'' ?>><?= _("local") ?></option>
					<option value="départemental" <?= $_SESSION['myEurope']->details['area']=="départemental"?'selected="selected"':'' ?>><?= _("departmental")?></option>
					<option value="régional" <?= $_SESSION['myEurope']->details['area']=="régional"?'selected="selected"':'' ?>><?= _("regional")?></option>
					<option value="national" <?= $_SESSION['myEurope']->details['area']=="national"?'selected="selected"':'' ?>><?= _("national")?></option>
					<option value="international" <?= $_SESSION['myEurope']->details['area']=="international"?'selected="selected"':'' ?>><?= _("international")?></option>
				</select>
			</div>
			
			<div data-role="fieldcontain">
				<fieldset name="type" id="type" data-role="controlgroup">
					<legend ><p style="text-align:right"><?= _("Territory type")?> : </p></legend>
				<?  $tokens = explode("|", $_SESSION['myEurope']->details['territoryType']);  
					foreach (Categories::$territorytype as $k=>$v) :
						if(in_array($k, $tokens)){ ?>
							<input type="checkbox" checked name="territoryType[]" id="<?= $k?>" value="<?= $k ?>"><label for="<?= $k?>"><?= $v ?></label>
					 <? }else{?>
							<input type="checkbox" name="territoryType[]" id="<?= $k?>" value="<?= $k ?>"><label for="<?= $k?>"><?= $v ?></label>
					  <?}
				    endforeach ?>
				</fieldset>
			</div>	
			<div data-role="fieldcontain">
				<label for="textinputu5" style="text-align:right"><?= _('Organization email') ?><b>*</b>: </label>
				<input id="textinputu5" name="email" placeholder="" value="<?= $_SESSION['myEurope']->details['email'] ?>" type="email" />
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu6" style="text-align:right"><?= _('Phone') ?>: </label>
				<input id="textinputu6" name="phone" placeholder="" value="<?= $_SESSION['myEurope']->details['phone'] ?>" type="tel" />
			</div>
			<div data-role="fieldcontain">
				<label for="desc" style="text-align:right"><?= _('Description') ?>: </label>
				<textarea id="desc" name="desc" placeholder="<?= _("description, comments")?>"><?= $_SESSION['myEurope']->details['desc'] ?></textarea>
			</div>
			<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-theme="g" data-role="button" data-icon="ok" value="<?= _('Update') ?>"/>
			</div>
		</form>
	</div>
	
	<!-- ----------------- -->
	<!-- DEFAULT HELP POPUP -->
	<!-- ----------------- -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Edit your Profile") ?></h3>
		<p> <?= _("Here you can update your organization profile.") ?></p>
		
	</div>
	
</div>
<? include("footer.php"); ?>