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
<? include("header.php"); ?>
<div data-role="page">
	
	<? tabs_simple('Profile edit');?>
	<? include("notifications.php"); ?>
	
	
	<div data-role="content">
		<form action="?action=ExtendedProfile&method=update" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false">
			<input type="hidden" name="form" value="edit" />
			<input type="hidden" name="id" value="<?= $_SESSION['myEurope']->profile ?>" />
			<div data-role="fieldcontain">
				<label for="textinputu1"><?= _('Organization Name') ?>: </label>
				<input id="textinputu1" name="name" placeholder="" value="<?= $_SESSION['myEurope']->details['name'] ?>" type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="role" class="select"><?= _("Your category") ?>:</label>
				<select name="role" id="role">
				<? foreach (Categories::$roles as $v) :?>
					<option value="<?= $v ?>" <?= $_SESSION['myEurope']->details['role']==$v?'selected="selected"':'' ?>><?= $v ?></option>
				<? endforeach ?>
				</select>
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu2">Domaine d'action: </label>
				<input id="textinputu2" name="activity" placeholder="" value="<?= $_SESSION['myEurope']->details['activity'] ?>" type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu4"><?= _('Address') ?>: </label>
				<input id="textinputu4" name="address" placeholder="" value="<?= $_SESSION['myEurope']->details['address'] ?>" type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="area" class="select"><?= _("Territoire d'action") ?>:</label>
				<select name="area" id="area">
					<option value="local" <?= $_SESSION['myEurope']->details['area']=="local"?'selected="selected"':'' ?>>local</option>
					<option value="départemental" <?= $_SESSION['myEurope']->details['area']=="départemental"?'selected="selected"':'' ?>>départemental</option>
					<option value="régional" <?= $_SESSION['myEurope']->details['area']=="régional"?'selected="selected"':'' ?>>régional</option>
					<option value="national" <?= $_SESSION['myEurope']->details['area']=="national"?'selected="selected"':'' ?>>national</option>
					<option value="international" <?= $_SESSION['myEurope']->details['area']=="international"?'selected="selected"':'' ?>>international</option>
				</select>
			</div>
			
			<div data-role="fieldcontain">
				<fieldset id="type" data-role="controlgroup">
					<legend>Type de territoire:</legend>
					<input type="checkbox" name="type-urbain" id="check-view-a" value="urbain" checked="checked"/> <label for="check-view-a">urbain</label>
					<input type="checkbox" name="type-rural" id="check-view-b" value="rural" /> <label for="check-view-b">rural</label>
					<input type="checkbox" name="type-montagnard" id="check-view-c" value="montagnard" /> <label for="check-view-c">montagnard</label>
					<input type="checkbox" name="type-maritime" id="check-view-d" value="maritime" /> <label for="check-view-d">maritime</label>
				</fieldset>
			</div>	
			<div data-role="fieldcontain">
				<label for="textinputu5"><?= _('Email') ?>: </label>
				<input id="textinputu5" name="email" placeholder="" value="<?= $_SESSION['myEurope']->details['email'] ?>" type="email" />
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu6"><?= _('Phone') ?>: </label>
				<input id="textinputu6" name="phone" placeholder="" value="<?= $_SESSION['myEurope']->details['phone'] ?>" type="tel" />
			</div>
			<div data-role="fieldcontain">
				<label for="desc"><?= _('Description') ?>: </label>
				<textarea id="desc" name="desc" placeholder="description, commentaires"><?= $_SESSION['myEurope']->details['desc'] ?></textarea>
			</div>
			<br />
			<div data-role="fieldcontain">
				<label for="password"><?= _("Password") ?>:</label>
				<input type="password" id="password" name="password" />
			</div>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-role="button" data-icon="ok" value="<?= _('Update') ?>"/>
			</div>
		</form>
	</div>
</div>
<? include("footer.php"); ?>