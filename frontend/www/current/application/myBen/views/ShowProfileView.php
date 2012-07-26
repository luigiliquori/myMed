<? require("header.php"); ?>

<div data-role="page"  >	

	<? require("header-bar.php") ?>
	
	<div data-theme="e" data-role="header" class="left" >
		<h3>Profil de "<?= $this->_user->name ?>"</h3>
	</div>
	
	<form data-role="content" method="post" action="#" >
	
		
		<a data-ajax="false" data-role="button" data-theme="g" data-icon="edit" data-inline="true"
			href="<?= url("extendedProfile:edit", array("id" => $this->_extendedProfile->id)) ?>">
			Éditer
		</a>
		
		<? if ($this->_extendedProfile->userID == $this->user->id) : ?>
			<a data-ajax="false" data-role="button" data-theme="r" data-icon="power" data-inline="true"
				href="<?= url("logout") ?>">
				Se délogguer
			</a>
		<? endif ?>
		
		<? global $READ_ONLY; $READ_ONLY=true ?>
		
		<? if ($this->_extendedProfile instanceof ProfileBenevole) : ?>
			<? require('ProfileBenevoleForm.php') ?>
		<? else: ?>
			<? require('ProfileAssociationForm.php') ?>
		<? endif ?>
		
	</form>
	
</div>