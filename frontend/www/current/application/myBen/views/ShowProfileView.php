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
		
		$bc[_('Mon Profil')] = null;
		
	} else {
		
		// Benevoles or Associations
		if ($this->_extendedProfile instanceof ProfileAssociation) {
			$bc[_("Associations")] = url("listAssociations");
		} else {
			$bc[_("Bénévoles")] = url("listBenevoles");
		}
		$bc[$this->_user->name] = null;	
	}
	
	// header bar
	//header_bar($bc);
	
	?>
	
	<form data-role="content" method="post" action="#" >
	
		<? if ($this->_extendedProfile instanceof ProfileNiceBenevolat): ?>
		
			<h3>
				<?= _("Le profil de Nice Bénévolat ne peut être changé.") ?>
			<h3>
			
		<? else: ?>		
			<a data-ajax="false" data-role="button" data-theme="e" data-icon="edit" data-inline="true"
				href="<?= url("extendedProfile:edit", array("id" => $this->_extendedProfile->userID)) ?>">
				<?= _("Éditer") ?>
			</a>
					
			<? if (($this->extendedProfile instanceof ProfileNiceBenevolat) && 
				($this->_extendedProfile instanceof ProfileAssociation) && 
				(! is_true($this->_extendedProfile->valid))) :
			?>
				<a data-ajax="false" data-role="button" data-theme="g" data-icon="check" data-inline="true"
					href="<?= url("extendedProfile:validate", array("id" => $this->_extendedProfile->userID)) ?>">
					<?= _("Valider cette association") ?>
				</a>
			<? endif ?>

			<? if ($this->extendedProfile instanceof ProfileNiceBenevolat): ?>	
				<a data-ajax="false" data-role="button" data-theme="r" data-icon="delete" data-inline="true"
					href="<?= url("extendedProfile:delete", array("id" => $this->_extendedProfile->userID)) ?>">
					<?= _("Supprimer") ?>
				</a>
			<? endif ?>
			
			<? global $READ_ONLY; $READ_ONLY=true ?>
			
			<? if ($this->_extendedProfile instanceof ProfileBenevole) : ?>
				<? require('ProfileBenevoleForm.php') ?>
			<? else: ?>
				<? require('ProfileAssociationForm.php') ?>
			<? endif ?>
		<? endif ?> 
		
	</form>
	
</div>
<?php include("footer.php"); ?>
