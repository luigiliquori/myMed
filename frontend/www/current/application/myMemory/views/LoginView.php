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
<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page" id="login" class="ui-page-white">
	<? $title = _("Sign in");
	 print_header_bar(false, false, $title); ?>
	
	<div data-role="content" class="content">
		
		<? print_notification($this->success.$this->error);?>
	
 		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Why Register ?") ?></h3>
			<p><?= _("MyMemoryWhyRegister") ?></p>
		</div>
		
		<form action="?action=login" method="post" data-ajax="false">
		
			<div data-role="collapsible-set" data-theme="b" data-content-theme="d" data-mini="true">
				
				<div data-role="collapsible" data-collapsed="false">
					<h3><?= _("Login") ?></h3>
				
					<input type="hidden" name="signin" value="1" />
					<div style="text-align: left;">Email :</div>
					<input type="text" name="login" id="login" data-theme="c"/>
				    <div style="text-align: left;"><?= _("Password")?> :</div>
				    <input type="password" name="password" id="password" data-inline="true"  data-theme="c"/>  
				    <br />
				    <div data-role="controlgroup" data-type="horizontal">
				 	    <input type="submit" data-role="button" data-mini="true" data-inline="true" data-theme="b" data-icon="signin" value="<?= _("Sign in") ?>" />
						<a href="?action=register" data-role="button" data-inline="true" data-mini="true" data-icon="pencil" data-iconpos="right"><?= _("Register") ?></a>	
					</div>
				    
			    </div>
			</div>			
		</form>

	</div>
	
</div>