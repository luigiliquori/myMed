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
<? require("header.php"); ?>

<div data-role="page"  >	

	<? tab_bar_main("?action=extendedProfile"); ?>
	<?php  include('notifications.php');?>

	<?
	// Build breadcrumb
	$bc = array();
	
	// Own profile ?
	if ($this->_extendedProfile->userID == $this->user->id) {
		$bc[_('Mon Profil')] = url("extendedProfile:show");
	} else {
		$bc[_("Benevoles")] = url("listBenevoles");
		$bc[$this->_user->name] = url("extendedProfile:show", array("id" => $this->_user->id));
	}
	
	$bc[_("Édition")] = null;
	
	// header bar
	//header_bar($bc);

	?>

	<form data-role="content" method="post" data-ajax="false" id="benForm"
		action="<?= url("extendedProfile:update",array("id" => $this->_extendedProfile->userID)) ?>" >
		
		<? require('ProfileBenevoleForm.php') ?>
	
		<? wizard_footbar(_("Mettre à jour le profil")) ?>
	</form>
	
</div>