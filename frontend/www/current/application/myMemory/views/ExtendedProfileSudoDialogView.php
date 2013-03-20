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
	<div data-role="page" data-theme="a">
		<div data-role="content" data-theme="c" class="ui-overlay-shadow ui-corner-bottom ui-content ui-body-c" role="main">
			<h1><?= _("ConfirmationNeeded"); ?></h1>
			<p><?= _("MyMemory_SudoDialog") ?></p>
			
			<form id="sudo" action="?action=ExtendedProfile&edit=true" method="post" data-ajax="false" >
	
				<input type="password" name="password" placeholder="<?= _("Password"); ?>" tabindex="1"/>
		
				<input type="submit" name="sudo" value="<?= _("Validate") ?>" data-theme="g" tabindex="2"/>
		
			</form>
			<a href="?action=ExtendedProfile" data-role="button" data-rel="back" data-theme="r" tabindex="3"><?= _("Cancel"); ?></a>
		</div>
	</div>
