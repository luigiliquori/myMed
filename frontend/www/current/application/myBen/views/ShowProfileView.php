<? require("header.php"); ?>
<div data-role="page"  >	

	<? 
	// Build breadcrumb
	$bc = array("Accueil" => url("main"));
		
	// Own profile ?
	if ($this->_extendedProfile->userID == $this->user->id) {
		
		$bc['Mon Profil'] = null;
		
	} else {
		
		// Benevoles or Associations
		if ($this->_extendedProfile instanceof ProfileAssociation) {
			$bc["Associations"] = url("listAssociations");
		} else {
			$bc["Benevoles"] = url("listBenevoles");
		}
		$bc[$this->_user->name] = null;	
	}
	
	// header bar
	header_bar($bc);
	
	?>
	
	<form data-role="content" method="post" action="#" >

		<? if ($this->_extendedProfile->userID == $this->user->id) : ?>
			<a data-ajax="false" data-role="button" data-theme="r" data-icon="power" data-inline="true"
				href="<?= url("logout") ?>">
				<? if (isset($_SESSION['launchpad'])) : ?>
					Quitter
				<? else: ?>
					Se délogguer
				<? endif ?>
			</a>
		<? endif ?>
		
		<? if (!$this->_extendedProfile instanceof ProfileNiceBenevolat): ?>			
			<a data-ajax="false" data-role="button" data-theme="e" data-icon="edit" data-inline="true"
				href="<?= url("extendedProfile:edit", array("id" => $this->_extendedProfile->userID)) ?>">
				Éditer
			</a>
					
			<? if (($this->extendedProfile instanceof ProfileNiceBenevolat) && 
				($this->_extendedProfile instanceof ProfileAssociation) && 
				(! is_true($this->_extendedProfile->valid))) :
			?>
				<a data-ajax="false" data-role="button" data-theme="g" data-icon="check" data-inline="true"
					href="<?= url("extendedProfile:validate", array("id" => $this->_extendedProfile->userID)) ?>">
					Valider cette association
				</a>
			<? endif ?>

			<? if ($this->extendedProfile instanceof ProfileNiceBenevolat): ?>	
				<a data-ajax="false" data-role="button" data-theme="r" data-icon="delete" data-inline="true"
					href="<?= url("extendedProfile:delete", array("id" => $this->_extendedProfile->userID)) ?>">
					Supprimer
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