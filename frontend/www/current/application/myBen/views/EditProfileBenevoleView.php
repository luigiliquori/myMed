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