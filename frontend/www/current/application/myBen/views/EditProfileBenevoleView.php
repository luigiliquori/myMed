<? require("header.php"); ?>

<div data-role="page"  >	

	<?
	// Build breadcrumb
	$bc = array("Accueil" => url("main"));
	
	// Own profile ?
	if ($this->_extendedProfile->userID == $this->user->id) {
		$bc['Mon Profil'] = url("extendedProfile:show");
	} else {
		$bc["Benevoles"] = url("listBenevoles");
		$bc[$this->_user->name] = url("extendedProfile:show", array("id" => $this->_user->id));
	}
	
	$bc["Édition"] = null;
	
	// header bar
	header_bar($bc);
	
	
	?>

	<form data-role="content" method="post" data-ajax="false" 
		action="<?= url("extendedProfile:update",array("id" => $this->_extendedProfile->userID)) ?>" >
		
		<? require('ProfileBenevoleForm.php') ?>
	
		<input type=submit name="submit" data-role="button" data-theme="g" 
			value="Mettre à jour le profil" />

	</form>
	
</div>