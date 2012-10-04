<? require("header.php"); ?>

<div data-role="page"  >	

	<? tab_bar_main("?action=extendedProfile"); ?>
	<?
	// Build breadcrumb
	$bc = array();
	
	// Own profile ?
	if ($this->_extendedProfile->userID == $this->user->id) {
		$bc[_('Mon Profil')] = url("extendedProfile:show");
	} else {	
		$bc[_("Associations")] = url("listAssociations");
		$bc[$this->_user->name] = url("extendedProfile:show", array("id" => $this->_user->id));
	}
	
	$bc[_("Édition")] = null;
	
	// header bar
	//header_bar($bc); ?>

	<form 
		data-role="content" 
		method="post" data-ajax="false" 
		action="<?= url("extendedProfile:update", array("id" => $this->_extendedProfile->userID)) ?>" >
		
		<? require('ProfileAssociationForm.php') ?>
	
		<? wizard_footbar(_("Mettre à jour le profil")) ?>

	</form>
	
</div>
